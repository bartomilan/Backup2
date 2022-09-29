<?php

class CorgiUpdate
{
	public static function register_routes()
	{
		$base = 'update';

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base, array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::get_updates',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base . '/run/(?P<type>[a-z]+)', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::run_update',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));
	}

	public static function get_updates($request)
	{
		error_reporting(0);

		try {
			global $wp_version;

			require_once(ABSPATH . 'wp-admin/includes/update.php');
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');

			$updates = array();

			$cores = get_core_updates();
			$plugins = get_plugin_updates();
			$themes = get_theme_updates();

			foreach ($cores as $core) {
				if ($wp_version === $core->version && $core->locale === get_locale()) {
					continue;
				}

				$updates[] = array(
					'type' => 'core',
					'slug' => 'wordpress',
					'name' => 'Wordpress',
					'version' => $core->version,
					'current_version' => $wp_version,
					'locale' => $core->locale,
					'requires_wp' => null,
					'tested_wp' => null,
					'requires_php' => $core->php_version,
				);
			}

			foreach ($plugins as $id => $plugin) {
				$updates[] = array(
					'type' => 'plugin',
					'slug' => $id,
					'name' => $plugin->Name,
					'version' => $plugin->update->new_version,
					'current_version' => $plugin->Version,
					'requires_wp' => $plugin->update->requires ? $plugin->update->requires : null,
					'tested_wp' => $plugin->update->tested ? $plugin->update->tested : null,
					'requires_php' => $plugin->update->requires_php ? $plugin->update->requires_php : null,
				);
			}

			foreach ($themes as $id => $theme) {
				$theme_data = wp_get_theme($id);

				$updates[] = array(
					'type' => 'theme',
					'slug' => $id,
					'name' => $theme_data->Name,
					'version' => $theme->update['new_version'],
					'current_version' => $theme_data->Version,
					'requires_wp' => $theme->update['requires_wp'] ? $theme->update['requires_wp'] : null,
					'tested_wp' => null,
					'requires_php' => $theme->update['requires_php'] ? $theme->update['requires_php'] : null
				);
			}

			$data = array(
				'wp_version' => $wp_version,
				'php_version' => phpversion(),
				'updates' => $updates
			);

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Getting updates failed', array('status' => 400));
		}
	}

	public static function run_update($request)
	{
		error_reporting(0);

		try {
			require_once(ABSPATH . 'wp-admin/includes/admin.php');
			require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

			$type = $request['type'];
			$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
			$version = isset($_GET['version']) ? $_GET['version'] : false;
			$locale = isset($_GET['locale']) ? $_GET['locale'] : 'en_US';

			$activation_result = null;
			$is_active = null;

			switch ($type) {
				case 'core':
					require_once(ABSPATH . 'wp-admin/includes/class-core-upgrader.php');

					$update = find_core_update($version, $locale);
					$upgrader = new Core_Upgrader();
					$result = $upgrader->upgrade($update);
					break;
				case 'plugin' :
					require_once(ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php');

					$is_active = is_plugin_active($slug);

					$upgrader = new Plugin_Upgrader();
					$result = $upgrader->upgrade($slug);

					sleep(10);

					if ($is_active) {
						$activation_result = activate_plugin($slug, '', false, true);
					}

					break;
				case 'theme':
					require_once(ABSPATH . 'wp-admin/includes/class-theme-upgrader.php');

					$upgrader = new Theme_Upgrader();
					$result = $upgrader->upgrade($slug);
					break;
			}

			$status = 'success';

			if ($result->errors || $result->error_data || $activation_result) {
				$status = 'error';
			}

			$final_result = [
				'update' => $result,
			];

			if ($type === 'plugin') {
				$final_result['was_active'] = $is_active;
				$final_result['activation'] = $activation_result;
			}

			$data = array(
				'status' => $status,
				'type' => $type,
				'slug' => $slug,
				'version' => $version,
				'locale' => $locale,
				'result' => $final_result
			);

			sleep(10);

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Running update failed', array('status' => 400));
		}
	}
}
