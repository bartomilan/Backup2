<?php
/**
 * Plugin Name:       Corgi
 * Plugin URI:        https://corgi.pro
 * Description:       Make your website secure with the most comprehensive security app ever built
 * Version:           0.8.1
 * Author:            Corgi
 * Author URI:        https://corgi.pro
 */

define('CORGI__PLUGIN_DIR', WPMU_PLUGIN_DIR . '/corgi');
define('CORGI__HELPER_DIR', CORGI__PLUGIN_DIR . '/helpers');
define('CORGI__LIB_DIR', CORGI__PLUGIN_DIR . '/libs');
define('CORGI__SCRIPT_DIR', CORGI__PLUGIN_DIR . '/scripts');
define('CORGI__SERVICE_DIR', CORGI__PLUGIN_DIR . '/services');
define('CORGI__REST_VERSION', '1');
define('CORGI__REST_NAMESPACE', 'corgi/v' . CORGI__REST_VERSION);
define('CORGI__GET_2FA_CODE_URL', CORGI__API_URL . '/website/code?secret=' . CORGI__SECRET);
define('CORGI__SEND_2FA_CODE_URL', '/wp-json/corgi/v1/2fa-code');

require_once(CORGI__LIB_DIR . '/mysqldump.php');

require_once(CORGI__HELPER_DIR . '/check-permission.php');
require_once(CORGI__HELPER_DIR . '/files.php');
require_once(CORGI__HELPER_DIR . '/ip.php');

require_once(CORGI__SERVICE_DIR . '/api.php');
require_once(CORGI__SERVICE_DIR . '/backup.php');
require_once(CORGI__SERVICE_DIR . '/brute_force_protection.php');
require_once(CORGI__SERVICE_DIR . '/files.php');
require_once(CORGI__SERVICE_DIR . '/health.php');
require_once(CORGI__SERVICE_DIR . '/indexing.php');
require_once(CORGI__SERVICE_DIR . '/login.php');
require_once(CORGI__SERVICE_DIR . '/login_url.php');
require_once(CORGI__SERVICE_DIR . '/login_with_2fa.php');
require_once(CORGI__SERVICE_DIR . '/session.php');
require_once(CORGI__SERVICE_DIR . '/update.php');
