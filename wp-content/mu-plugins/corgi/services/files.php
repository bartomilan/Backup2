<?php

class CorgiFiles
{
	public static function register_routes()
	{
		$base = 'files';

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base, array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::get_files',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));
	}

	public static function get_files($request)
	{
		try {
			$data = corgi_get_files('./');

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Getting files failed', array('status' => 400));
		}
	}
}
