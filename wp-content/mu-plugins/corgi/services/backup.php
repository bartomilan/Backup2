<?php

class CorgiBackup
{
	public static function register_routes()
	{
		$base = 'backup';

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base . '/create/(?P<uuid>[a-fA-F0-9-]+)', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::create_backup',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base . '/restore/(?P<uuid>[a-fA-F0-9-]+)', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::restore_backup',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base . '/delete/(?P<uuid>[a-fA-F0-9-]+)', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::delete_backup',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));
	}

	public static function create_backup($request)
	{
		error_reporting(0);

		try {
			$uuid = $request['uuid'];
			$backup_file = CORGI__PLUGIN_DIR . '/' . $uuid . '.sql';

			$settings = array(
				'add-drop-table' => true
			);

			$dumper = new CorgiMysqldump('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, $settings);
			$dumper->start($backup_file);

			$data = array(
				'uuid' => $uuid,
				'file' => $backup_file,
				'status' => 'success',
			);

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Creating backup failed', array('status' => 400));
		}
	}

	public static function restore_backup($request)
	{
		error_reporting(0);

		try {
			$uuid = $request['uuid'];
			$backup_file = CORGI__PLUGIN_DIR . '/' . $uuid . '.sql';

			$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

			$templine = '';
			$handle = fopen($backup_file, 'r');

			if (!$handle) {
				$data = array(
					'uuid' => $uuid,
					'file' => $backup_file,
					'status' => 'error',
					'message' => 'Backup file doesn\'t exist'
				);

				return new WP_REST_Response($data, 409);
			}

			while (!feof($handle)) {
				$line = trim(fgets($handle));

				if (substr($line, 0, 2) == '--' || $line == '') {
					continue;
				}

				$templine .= $line;

				if (substr(trim($line), -1, 1) == ';') {
					mysqli_query($link, $templine);
					$templine = '';
				}
			}

			fclose($handle);
			unlink($backup_file);

			$data = array(
				'uuid' => $uuid,
				'file' => $backup_file,
				'status' => 'success',
			);

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Restoring backup failed', array('status' => 400));
		}
	}

	public static function delete_backup($request)
	{
		error_reporting(0);

		try {
			$uuid = $request['uuid'];
			$backup_file = CORGI__PLUGIN_DIR . '/' . $uuid . '.sql';

			unlink($backup_file);

			$data = array(
				'uuid' => $uuid,
				'file' => $backup_file,
				'status' => 'success',
			);

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Deleting backup failed', array('status' => 400));
		}
	}
}
