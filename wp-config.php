<?php
// Begin AIOWPSEC Firewall
if (file_exists('/home/u912106533/domains/sanamente.co/public_html/aios-bootstrap.php')) {
	include_once('/home/u912106533/domains/sanamente.co/public_html/aios-bootstrap.php');
}
// End AIOWPSEC Firewall
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u912106533_sanaNew' );

/** Database username */
define( 'DB_USER', 'u912106533_sanaNew' );

/** Database password */
define( 'DB_PASSWORD', '901019Mm@*' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '?&}qxHIOW2PyI-m?xnb{qH;ajZxzW,p[B3i):X3qpga]4[@>BQJi#p1g[eej>YPe' );
define( 'SECURE_AUTH_KEY',  '^Q6s;L6`+1KcKZ X)S!}+Y#U@qyasZ6[xJA2gb_Oyq>:k)@1!0HEKX=}e*;<[5d;' );
define( 'LOGGED_IN_KEY',    'S3uTP1WsH(]fTzOu64kzO8v1f.,ecMff@B7&P|<[:5yZ&WCXM(!^t&$k9av8eci4' );
define( 'NONCE_KEY',        'XIyPo;.a>u< _4xT,vllmZ _|2.[46rI3i -wMjlpY-ur)44P2eUJ-%eMV&KKbo6' );
define( 'AUTH_SALT',        '`AM)n#<$l)aLm#tEx[|6p,B/03hbC, r`[`9s}+:ld2/`KDOqLnkUy,y]fQt+}Eo' );
define( 'SECURE_AUTH_SALT', '|UrLfROjfe+M3|lqS.1u7Ifa#<!&oTEJ9S{ykIx~w1CvYfDi!nC6q-yC=Y@lj^9p' );
define( 'LOGGED_IN_SALT',   'V&rFEOp]+.b;Y[vt,6x4yB=I$$Irg*NWEegkW_?!zGf+!iHx`yJI/RMM.MJ${HOn' );
define( 'NONCE_SALT',       'thjQBq|=Ze;%-LpyZp!#IoD#t3|/udSyoQE}NrRm<wt_BZ/{Bw-w{O!,=+Fg|5:c' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'smwp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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