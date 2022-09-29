<?php

if (!CORGI__BRUTE_FORCE_PROTECTION) {
	return;
}

add_action('wp_login_failed', function () {
	if (!isset($_SESSION['corgi']['login_failed_count'])) {
		$_SESSION['corgi']['login_failed_count'] = 1;
	} else {
		$_SESSION['corgi']['login_failed_count'] += 1;
	}

	switch ($_SESSION['corgi']['login_failed_count']) {
		case 3:
			$_SESSION['corgi']['login_ban_time'] = time() + 60; // 1 minute
			break;
		case 5:
			$_SESSION['corgi']['login_ban_time'] = time() + 60 * 5; // 5 minutes
			break;
		case 10:
			$_SESSION['corgi']['login_ban_time'] = time() + 60 * 60; // 1 hour
			break;
		case 20:
			$_SESSION['corgi']['login_ban_time'] = time() + 60 * 60 * 24; // 24 hours
			break;
	}
});

add_action('wp_login', function () {
	if (isset($_SESSION['corgi']['login_failed_count'])) {
		$_SESSION['corgi']['login_failed_count'] = 0;
	}

	if (isset($_SESSION['corgi']['login_ban_time'])) {
		unset($_SESSION['corgi']['login_ban_time']);
	}
});
