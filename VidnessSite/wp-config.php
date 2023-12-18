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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_vidness' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '[~ZZQV{-Ux~KZgv<ovgDuT_m!g?M%7SPw]2u|D7!P7DQ.0,_u:yhny35Z:rfI/BR' );
define( 'SECURE_AUTH_KEY',  '$buS;{7y>tX7}7&j7H-p.tU<x{5|C_IrzAaxbo.f0yZ]-0pxw3JI.QF Y{4Kbx8[' );
define( 'LOGGED_IN_KEY',    'wE5x#cL1|HxkN5e.M3Y9RBXreP5RK^, $1VDUSq;{pkzz:f7_R6ux>d55i5DaY:a' );
define( 'NONCE_KEY',        'OohS*si$|5]+)<(u/;?$OqqpK7u,wH-&V/nLV2eilMJ*$<.At7R^Q#ghL?z|/)0?' );
define( 'AUTH_SALT',        'APK_N0Saecn*UH)+Aw?`S$V3WVQ0Htj5Y;3d#]HDL,VZa}mJ,jrr:uP@Yk:VZ4}l' );
define( 'SECURE_AUTH_SALT', 'Gql8X<:G?]U(h>8jG}1MP2b.xB!At@%Xm<Rk:Xr_:jIobI$xnDjFd XU!wq$O] h' );
define( 'LOGGED_IN_SALT',   '.pZfBw5^,V=18YL#)&YIJ2.AhL;VZ!if#.jGvL^aMQuNgq>Mc/X7{Br(t;lLS5o$' );
define( 'NONCE_SALT',       'u<p!.-^5Sf~S}~lX)rmQ 1!XOb@7NE&tbbUS-f`z{xt$NBtf52=45GAr.x)%Uv$Z' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wvpd_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
