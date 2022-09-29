<?php

if (!CORGI__LOGIN_URL) {
	return;
}

/*
 *  Add Corgi Login URL
 */
add_filter('generate_rewrite_rules', function ($wp_rewrite) {
	$wp_rewrite->rules = array_merge(
		[CORGI__LOGIN_URL . '/?$' => 'index.php?corgi_' . CORGI__LOGIN_URL . '=' . 1],
		$wp_rewrite->rules
	);

	return $wp_rewrite->rules;

//	$current_setting = get_option('permalink_structure');
//	update_option("rewrite_rules", FALSE);
//	$wp_rewrite->set_permalink_structure($current_setting);
//	$wp_rewrite->flush_rules(true);

//	update_option( "rewrite_rules", FALSE );
//	$wp_rewrite->flush_rules();
});

add_filter('query_vars', function ($query_vars) {
	$query_vars[] = 'corgi_' . CORGI__LOGIN_URL;
	return $query_vars;
});

add_action('template_redirect', function () {
	$authorized = !!intval(get_query_var('corgi_' . CORGI__LOGIN_URL));

	if ($authorized) {
		$_SESSION['corgi']['login'] = true;

		echo '
			<html>
			<head>
				<script>window.location.replace("/wp-admin");</script>
			</head>
			</html>
		';

		exit();
	}
}, 99);


flush_rewrite_rules(false);

/**
 * Hide Original Login Form
 */
add_action('login_enqueue_scripts', function () {
	if (!isset($_SESSION['corgi']['login']) || !$_SESSION['corgi']['login']) {
		?>
		<style type="text/css">
			#login {
				opacity: 0;
				visibility: hidden;
				pointer-events: none;
			}
		</style>
		<?php
	}
});

