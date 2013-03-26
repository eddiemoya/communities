<?php

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */



/** The name of the database for WordPress */
define('DB_NAME', 'communities');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'c-$+b/v+$*>JD- h1!he-dEIi (4 o+mt<Ln,*uI::8I|mbDo+sL?if>e6j-oTJp');
define('SECURE_AUTH_KEY',  'R@)o4}w;w[o<r0n7O5?.KT^;3r-=}N?^ v7ZMvGr&-oN[iS_ ct^rCzU[ke|PVAh');
define('LOGGED_IN_KEY',    '-l*e AY,1UMs7n+_tku,toW`EsPd?X%fVzP+.tGCX6>9#U%piic>_Jl$ Nn$eiRZ');
define('NONCE_KEY',        'INp?Is-?R;[:G=5$7BkX&wF+Xm,`#%iw^9qa+BA=&3;S>&@KHSYr|w|~capstr7y');
define('AUTH_SALT',        '-_hUQ~152FM}%!cFl:~lwUMFX<j?5}oqW*>^@DiAb(ASrn7jYA)! }K<DI]B0H]c');
define('SECURE_AUTH_SALT', 'o]H[,$o?B-DURuR3gt/i{Bx|!-`TA%i,utwj}>xM;+wBwfmh|D`|H)U&7+uDlI4/');
define('LOGGED_IN_SALT',   '3EGl#a9j_@Z|@DDxXt|5ZtC75r(vUi|wlKLIjxTE)wx$I;,O 44,^*YJ(QrS(dsu');
define('NONCE_SALT',       '1sE=()DvxU4G(1HT-bX2|A1X+RscZhuVVx-DAB10YTBOe1/hi|cV~H3ES_am=~>l');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);


//define( 'WP_CONTENT_DIR', '/Volumes/STATIVUS/Projects/Communities/wordpress/wp-content' );
//define( 'WP_PLUGIN_DIR', '/Volumes/STATIVUS/Projects/Communities/wordpress/wp-content/plugins' );
//define( 'PLUGINDIR', '/Volumes/STATIVUS/Projects/Communities/wordpress/wp-content/plugins' );

define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define( 'WP_HOME',    'http://' . $_SERVER['HTTP_HOST'] );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content');
define( 'WP_PLUGIN_URL',  'http://' . $_SERVER['HTTP_HOST'] . '/wp-content/plugins');
//define( 'UPLOADS',  'wp-content/uploads' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** W3 Total Cache **/
define('WP_CACHE', true); 

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
