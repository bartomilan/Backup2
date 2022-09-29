<?php

class CorgiIndexing
{
	public static function register_routes()
	{
		$base = 'indexing';

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base, array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::get_indexing',
				'permission_callback' => 'corgi_check_permission',
				'args' => array(),
			)
		));
	}

	public static function get_indexing($request)
	{
		error_reporting(0);

		if (0 == get_option('blog_public')) {
			$indexing = false;
		} else {
			$indexing = true;
		}

		try {
			$data = array(
				'status' => 'ok',
				'indexing' => $indexing
			);

			return new WP_REST_Response($data, 200);
		} catch (Exception $e) {
			return new WP_Error('error', 'Getting indexing failed', array('status' => 400));
		}
	}
}
