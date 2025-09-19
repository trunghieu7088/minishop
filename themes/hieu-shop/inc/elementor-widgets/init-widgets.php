<?php
if (!defined('ABSPATH')) {
    exit; 
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

add_action('elementor/widgets/register', 'hieu_shop_register_hero_banner_widget');
function hieu_shop_register_hero_banner_widget($widgets_manager) {
    require_once __DIR__ . '/hero-banner-widget.php';
    $widgets_manager->register(new \Hieu_Shop_Hero_Banner_Widget());
}


add_action( 'elementor/widgets/register', 'hieu_shop_register_footer_banner_widget' );
function hieu_shop_register_footer_banner_widget($widgets_manager) {
    require_once __DIR__ . '/footer-banner-widget.php';
    $widgets_manager->register(new \Hieu_Shop_Footer_Banner_Widget());
}
