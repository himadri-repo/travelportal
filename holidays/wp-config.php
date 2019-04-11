<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'i4964855_wp1');

/** MySQL database username */
define('DB_USER', 'i4964855_wp1');

/** MySQL database password */
define('DB_PASSWORD', 'M.WkbSRBWmnWd8OOrwN40');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'IEf2JEyrRileglcPfW8NBposIfPUItbwufhaxrvM4keUtKrwSXrCqYepCKw5D8vJ');
define('SECURE_AUTH_KEY',  'MPRYR03qmU55X2zx7fWGDzc88Fg09iIAQuL7wRBHloK5UVR9WhFYyOU3ZG27cSev');
define('LOGGED_IN_KEY',    'QQlzMbjRS2Df14NvEWonswJXIHSKv2utbBoyeac6IUt5iBNcjTCOLCqzjdexXDXh');
define('NONCE_KEY',        'aAhABtWzRcMerd22515AzQbfSvIFCiylblkaB2xE7LLUZvaGmznG9l2HR3dosrhK');
define('AUTH_SALT',        'Ma77RTq3a05PFQWbyMzoZ7iSN4kt0yVCjVZz39mkvhnSjLym1JJvv4D6n56phQVh');
define('SECURE_AUTH_SALT', 'wPboC54bMpDTR8Plt7VorS5E4Mqwx5DZ2T5FJ5Luavf9lawRKNgtik41U1H8hEaU');
define('LOGGED_IN_SALT',   'aaFt5BDqySPiMixOkObGzFzIEzAWRcW0JdVFHuse3tpL0WlWu4TdnBI6ESZ917No');
define('NONCE_SALT',       'BkW0Ch06iA9Nky5uL0M1cMgdDanCEsNa77pDvAiDbsizJHM1PfC6a5QjVR18v4Xo');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
