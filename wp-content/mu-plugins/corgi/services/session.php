<?php

/**
 * Init session
 */
if (session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
	session_start();
};

if (!isset($_SESSION['corgi'])) {
	$_SESSION['corgi'] = array();
	$_SESSION['corgi']['created'] = time();
	$_SESSION['corgi']['updated'] = $_SESSION['corgi']['created'];
} else if (time() - $_SESSION['corgi']['updated'] > 60 * 60 * 24 ) { // 24 hours
	unset($_SESSION['corgi']);

	try {
		session_destroy();
	} catch (Exception $e) {}

	session_start();
	$_SESSION['corgi'] = array();
	$_SESSION['corgi']['created'] = time();
	$_SESSION['corgi']['updated'] = $_SESSION['corgi']['created'];
} else {
	$_SESSION['corgi']['updated'] = time();
}
