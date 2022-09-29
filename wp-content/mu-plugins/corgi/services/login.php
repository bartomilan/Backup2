<?php

/*
 * Block Not-Corgi-Login-URL Logins
 */
add_filter('authenticate', function ($user, $username, $password) {
	$corgi_2fa_code = isset($_POST['corgi-2fa-code']) ? $_POST['corgi-2fa-code'] : null;

	// Brute Force Protection
	if (CORGI__BRUTE_FORCE_PROTECTION && isset($_SESSION['corgi']['login_ban_time']) && $_SESSION['corgi']['login_ban_time'] > time()) {
		$diff = $_SESSION['corgi']['login_ban_time'] - time();

		if ($diff < 60) {
			$time = $diff . ' s';
		} else if ($diff >= 60 && $diff < 60 * 60) {
			$time = round($diff / 60) . ' min';
		} else {
			$time = round($diff / (60 * 60)) . ' h';
		}

		return new WP_Error('denied', __('<strong>Error</strong>: Too many failed login attempts. Try again after ' . $time . '.'));
	}

	// Login not from Corgi Login URL
	if (CORGI__LOGIN_URL && (!isset($_SESSION['corgi']['login']) || !$_SESSION['corgi']['login'])) {
		if (CORGI__BRUTE_FORCE_PROTECTION) {
			$_SESSION['corgi']['login_ban_time'] = time() + 60 * 60; // 1 hour
		}

		return new WP_Error('denied', __('<strong>Error</strong>: You have no permission to login.'));
		// TODO: Add IP to Corgi BlackList
	};

	// Username is not email
	if (CORGI__LOGIN_BY_EMAIL && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
		return new WP_Error('invalid_email', __('<strong>Error</strong>: You must provide a valid email address.'));
	}

	// Login with 2FA
	if (CORGI__LOGIN_WITH_2FA && (!isset($_SESSION['corgi']['2fa_code']) || !$_SESSION['corgi']['2fa_code'] || $_SESSION['corgi']['2fa_code'] != $corgi_2fa_code)) {
		return new WP_Error('wrong_2fa_code', __('<strong>Error</strong>: The 2FA SMS code is wrong.'));
	}

	return $user;
}, 99, 3);
