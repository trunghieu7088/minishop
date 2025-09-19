<?php
/**
 * Plugin Name:      Elementor HieuShop Addon
 * Description:      Custom Elementor addon for Hieushop
 * Plugin URI:       https://elementor.com/
 * Version:          1.0.0
 * Author:           Hieu Developer
 * Author URI:       https://hieubinh.com/
 * Text Domain:      elementor-hieushop-addon
 * Requires Plugins: elementor
 * Elementor tested up to: 3.25.0
 * Elementor Pro tested up to: 3.25.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function elementor_hieushop_addon() {

	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	\Elementor_HieuShop_Addon\Plugin::instance();

}
add_action( 'plugins_loaded', 'elementor_hieushop_addon' );