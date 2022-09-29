<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'baza22536_29' );

/** MySQL database username */
define( 'DB_USER', 'admin22536_29' );

/** MySQL database password */
define( 'DB_PASSWORD', '9No,kqNNz6' );

/** MySQL hostname */
define( 'DB_HOST', '22536.m.tld.pl' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'SWbZKRKBhYXTuMMPcGQ1C7PWWaOaAdrDuZuht407L34xVnuzhhjnFU9Odvh8Og6z');
define('SECURE_AUTH_KEY',  '5yBzF3vkGSuZ0RUtBo1stdVaooybk7BEoQLDLdBUzTvHdfcN0RbtXQ9jlIo5EGsz');
define('LOGGED_IN_KEY',    'RhE0MaNUOxXYZmTDym2bgaQ1BFT66MczbAZ9m4m196UBwo0r9OTjdOcne8doKCk2');
define('NONCE_KEY',        'gEll80Xi2z4XhYBLnXD4kJgD2i95JJi8EIsyFddDNJ7s38zE7BcLMGtnkTECy0ah');
define('AUTH_SALT',        'DjkXQiSfgeSUODwpBYo1NbHIKmIkrmZJMeOd9gKAJcEhYzJKRCfTxS9Pymq5GYqF');
define('SECURE_AUTH_SALT', 'iu8h1lv7N6ty8rTgge1DAA7doyCpBriY6UFE7hOpAPHv9Ihdc13JHoScg4QfrEBy');
define('LOGGED_IN_SALT',   'XXhaChQvRLXlIimjR6kU19eeGBqHi9Brtnu2AGnIWXXFekoCEB6DLenIeQMSSCdO');
define('NONCE_SALT',       'TyIrnN1jDxyVxNWo2HMfBW0BOwcXjljalciX8JB09PCvxo5MW3HjMtAHIPEN66Kp');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed externally by Installatron.
 * If you remove this define() to re-enable WordPress's automatic background updating
 * then it's advised to disable auto-updating in Installatron.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'h1wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';



/* Corgi Settings */
/* Important: don't edit or remove this section! */
define( 'CORGI__SECRET', '0FKGG58-2JJMCPC-KY7ZHJN-XD1GDAC' );
define( 'CORGI__API_URL', 'https://test-api.corgi.pro' );
define( 'CORGI__LOGIN_URL', null );
define( 'CORGI__LOGIN_BY_EMAIL', false );
define( 'CORGI__LOGIN_WITH_2FA', false );
define( 'CORGI__BRUTE_FORCE_PROTECTION', false );

require WPMU_PLUGIN_DIR.'/corgi/bridge.php';
/* End of Corgi Settings */