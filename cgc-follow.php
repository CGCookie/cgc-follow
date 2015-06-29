<?php
/**
 *
 * @package   CGC Follow 2.0
 * @author    Nick Haskins <nick@cgcookie.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 *
 * Plugin Name:       CGC Follow
 * Plugin URI:        http://cgcookie.com
 * Description:       Creates a social following/follower system
 * Version:           5.0
 * GitHub Plugin URI: https://github.com/cgcookie/cgc-follow
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Set some constants
define('CGC_FOLLOW_VERSION', '5.0');
define('CGC_FOLLOW_DIR', plugin_dir_path( __FILE__ ));
define('CGC_FOLLOW_URL', plugins_url( '', __FILE__ ));

require_once( plugin_dir_path( __FILE__ ) . 'public/class-cgc-follow.php' );

register_activation_hook( __FILE__, array( 'CGC_Follow', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CGC_Follow', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'CGC_Follow', 'get_instance' ) );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-cgc-follow-admin.php' );
	add_action( 'plugins_loaded', array( 'CGC_Follow_Admin', 'get_instance' ) );

}
