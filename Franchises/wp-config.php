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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db4ng8tmtvgmud' );

/** MySQL database username */
define( 'DB_USER', 'uqhpqfshkixqy' );

/** MySQL database password */
define( 'DB_PASSWORD', '3xmrgano4ujd' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'i#O1U?<4HiCsB0]_%%w4J>y}@nL@JA0.E5g5Fg!JJSzel32.,D#OGEK N$U]r GR' );
define( 'SECURE_AUTH_KEY',   '!al[u^g&g{8p,-5eN7:U-TSk+Z%$)-*R~:(nX)@qt-)M4LegdjB]2HkC;h0 @=E%' );
define( 'LOGGED_IN_KEY',     'LmN!x _*g(zv%~P~}2n~aQ{2nM)+7,1[BELPEH9*1a~.Egh]O&,M|<@2PM5=U0j>' );
define( 'NONCE_KEY',         'PhPxr*CeRif@& QWN6s^e#*a&jlDT4<r]_Oy_xWid8JSC)GV;<P.NPa-GM*J=K/S' );
define( 'AUTH_SALT',         'OqF[dqy04J/.S7<|r-.R1KZWvTYUj^*GI60ZO3.rMT)b?rZ[6tb(o gK/;*$6w&C' );
define( 'SECURE_AUTH_SALT',  '87n]R_]x`^(^1Uh3q8!Ct6B gdM5$J_iFj9!7KqZ+ceFryEPk{rTpamwJ5L*9~bu' );
define( 'LOGGED_IN_SALT',    'AJtkc%EvcP#v)^rCP7(:pLrwsD)M*pmA|VOSiEMW>+pF8AS!gRG2p4cK[o1/.G~g' );
define( 'NONCE_SALT',        '0qP%. KAFcI>F}*-WC?wM7i(oUfcS+o=L.`%w.[QwtxsN7rqe:4w,ufh/M)kWM-c' );
define( 'WP_CACHE_KEY_SALT', 'YWKGPN5lgmLIY5pZ)ongJW2G}+j)QL.ER1B7m^kPjSn0%]E[euW@d5U`y.1IpSHs' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
