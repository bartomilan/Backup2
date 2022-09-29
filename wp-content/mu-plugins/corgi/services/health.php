<?php

class CorgiHealth
{
	public static function register_routes()
	{
		$base = 'health';

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base, array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::get_health',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));
	}

	public static function get_health($request)
	{
		error_reporting(0);

		try {
			global $wp_version;

			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			$plugin_data = get_plugin_data( CORGI__PLUGIN_DIR . '/bridge.php') ;

			$data = array(
				'status' => 'ok',
				'wp_version' => $wp_version,
				'php_version' => phpversion(),
				'bridge_version' => $plugin_data['Version']
			);

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Getting health failed', array('status' => 400));
		}
	}
}
