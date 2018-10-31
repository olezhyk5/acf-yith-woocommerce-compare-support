<?php
/**
 * Plugin Name: Advanced Custom Fields YITH WooCommerce Compare support
 * Description: This plugin allows adding Advanced custom fields to the YITH Woocommerce compare table.
 * Author: Oleh Odeshchak
 * Version: 1.0.0
 * Author URI: http://thewpdev.org/
 * WC tested up to: 4.9.8
 *
 * @author Oleh Odeshchak
 * @package Advanced Custom Fields YITH WooCommerce Compare support
 * @version 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Define common constants
 */
if ( ! defined( 'YWCA_DIR_URL' ) )  define( 'YWCA_DIR_URL',  plugins_url( '', __FILE__ ) );
if ( ! defined( 'YWCA_DIR_PATH' ) ) define( 'YWCA_DIR_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'YWCA_VERSION' ) )  define( 'YWCA_VERSION', '1.0.0' );


/**
 * Check if required plugins are active
 */
if ( is_plugin_active( 'yith-woocommerce-compare/init.php' ) && ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) || is_plugin_active( 'advanced-custom-fields/acf.php' ) ) ) {
	require_once YWCA_DIR_PATH . 'advanced-custom-fields-yith-woocommerce-compare-support.php';
} else {
	add_action( 'admin_notices', 'ywca_admin_notice' );
}


if ( ! function_exists( 'ywca_admin_menu' ) ) {
	/**
	 * Admin notice text
	 */
	function ywca_admin_notice() {
		?>
		<div class="error">
			<p><?php _e( 'YITH WooCommerce Compare ACF Support is enabled but not effective. It requires WooCommerce, YITH WooCommerce Compare and Advanced Custom Fields in order to work.', 'ywca-plugin' ); ?></p>
		</div>
	<?php
	}
}