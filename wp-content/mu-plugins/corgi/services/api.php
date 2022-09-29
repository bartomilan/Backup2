<?php

/**
 * Register Corgi API Routes
 */
add_action('rest_api_init', function () {
	if (defined('WP_DEBUG') && WP_DEBUG === false) {
		error_reporting(0);
	}

	CorgiBackup::register_routes();
	CorgiFiles::register_routes();
	CorgiHealth::register_routes();
	CorgiIndexing::register_routes();
	CorgiUpdate::register_routes();
	Corgi2FA::register_routes();
});
