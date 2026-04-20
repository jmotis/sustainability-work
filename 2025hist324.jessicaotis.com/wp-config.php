<?php

// BEGIN Solid Security - Do not modify or remove this line
// Solid Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
define( 'FORCE_SSL_ADMIN', true ); // Redirect All HTTP Page Requests to HTTPS - Security > Settings > Enforce SSL
// END Solid Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'czt+NDkgdnw1MSVVQC8tPyhASiYuLy9tQyFDYDZSOjZPQTQ4cSNXUGQ6cFZ4eDIyKy1hZG1eUUoxUmdRN3h3dA==' );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
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
define( 'DB_NAME', 'jessicao_pjt41' );

/** MySQL database username */
define( 'DB_USER', 'jessicao_pjt41' );

/** MySQL database password */
define( 'DB_PASSWORD', 'K.2GO6mQU1xNTgxZn3Y06' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'sn0WkXfZpgKuohBkgups9145pDWXrTr56K8kMpoWUwlZ730JGwwRPjKhjIqTCK8U');
define('SECURE_AUTH_KEY',  '3jy5Ci9gEI1F8BsaEulAY6B3hjQvSc3dVNyiQa6J1ADwMUFv8OR6caRwlm3igvoy');
define('LOGGED_IN_KEY',    '5RBtLrDUOSC1j3X50aAvn29O4uSRNlnEOVO4MFTbihyc7QCsAhkDRClRqDAnLWSf');
define('NONCE_KEY',        'so7z1kHmULWCHLzAEl5ckbAe37hXhI1aYbKA9EuPvTFTCs9LXNG3LhkDhp6Y3jfJ');
define('AUTH_SALT',        'BLBYveQw53UspNU3Jygrm6CamEoLf6ZeUgS5xdtgP0ebRO7HVyJMZRAZLL9TZpZK');
define('SECURE_AUTH_SALT', 'EjJwtfV0ywRfy4hIhy6wH2iX2aQEEMqCNhc9lVdX4QcepRjiaCxKWGPpKzdbjmDz');
define('LOGGED_IN_SALT',   'foODH7hf2zqHIEGwm1A8D7P8ZV73f57uTqe87XZx85WlxoYN9qNO980bc2n9ufcG');
define('NONCE_SALT',       'OoFvZF4GxoLJj0HKkEUIlhmSRwGVeTK6hBvA1bZDx3BpjxpOUvG43549bWVO81am');

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
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'rtlr_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';