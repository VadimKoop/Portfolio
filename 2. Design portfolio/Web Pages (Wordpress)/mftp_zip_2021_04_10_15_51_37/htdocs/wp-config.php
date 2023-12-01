<?php
define( 'WP_CACHE', true );
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
define( 'DB_NAME', 'd101567sd422907' );

/** MySQL database username */
define( 'DB_USER', 'd101567sa381131' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Himagnat123!' );

/** MySQL hostname */
define( 'DB_HOST', 'd101567.mysql.zonevs.eu' );

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
define( 'AUTH_KEY',          'MuaylQZU)Soc!52AgHD`Mb8mM]v4k2Y?pjshNu;WqW@?*dz_qiDGBuI3v~%n{R0H' );
define( 'SECURE_AUTH_KEY',   'h(,U!tuPq!sN[wghyKJeUwyI4s$bU|)I?E/BWt8~>b!Y,42kcA%%a5E=ac$4hL,e' );
define( 'LOGGED_IN_KEY',     '6xUy(z.AC*y{irM=8C;[;{E~;^)_1fGHFW^U<@Y3mOrfu8DG{ ([9hX[}6$ `?GA' );
define( 'NONCE_KEY',         'R>0 w&3WvWwemq0k]l7xnNAR>(=OZYRA^C#jTj(1qEw%S<1[t.LGgM~cPq)Z4 B^' );
define( 'AUTH_SALT',         'N/,6,|SxL{NS1:kV&=Go@hMje5a{zwB5Cd:PG33Wg35m*YMb,cIM?x_By7L{YLM-' );
define( 'SECURE_AUTH_SALT',  '(FxUe cSQF4FTLQz|P~s6FVD9ABIdLEo5;vT33I%71[5B^fFh.A~7,w]uxd@}+)v' );
define( 'LOGGED_IN_SALT',    'A@CRjSN&N&4?)J@`cFt|i#GUFS.$#G+++dczA99C`,ylMqM>w/|L;dFgV#.0xhT$' );
define( 'NONCE_SALT',        '@zk+HW!nk^5TX/L~`7n-^rFS[Qq`+pjOJQ<v3@gme0fa2t_>hD#O^wI<rc7A0/zj' );
define( 'WP_CACHE_KEY_SALT', 'oeOPirW6]Hs>QkOR$Jr4=  UuC9zV.Z_e4 ^im3<ioI 4iwHC=:YsyMFwwA}ol(4' );

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
