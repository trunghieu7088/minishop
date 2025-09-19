<?php
/**
 * Hieu shop functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Hieu_shop
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function hieu_shop_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'hieu_shop_content_width', 640 );
}
add_action( 'after_setup_theme', 'hieu_shop_content_width', 0 );



/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


//my code start here
function hieu_shop_enqueue_assets() {
    $theme_uri = get_template_directory_uri();
    wp_enqueue_script('jquery');

    wp_enqueue_style( 'bootstrap-base-css', $theme_uri . '/assets/css/bootstrap5.css', array(), null );
    wp_enqueue_style( 'main-style-css', $theme_uri . '/assets/css/main.css', array(), null );

	wp_enqueue_style( 'fontawesome-style', $theme_uri . '/assets/fontawesome/css/all.min.css', array(), null );

	wp_enqueue_script( 'bootstrap-base-js', $theme_uri . '/assets/js/bootstrap5.js', array('jquery'), null, true );

}
add_action( 'wp_enqueue_scripts', 'hieu_shop_enqueue_assets' );

require_once get_template_directory() . '/inc/custom-menu-walker.php';

function glamour_beauty_setup() {
    register_nav_menus( array(
        'menu-1' => esc_html__( 'Primary', 'glamour-beauty' ),
    ) );
}
add_action( 'after_setup_theme', 'glamour_beauty_setup' );


add_theme_support('elementor');
require_once get_template_directory() . '/inc/elementor-widgets/init-widgets.php';

function hieu_shop_get_footer_text() {
    $footer_text = get_theme_mod('hieu_shop_footer_text', ' Minishop Beauty. All rights reserved. Designed with for beautiful people.');
    return $footer_text;
}
require get_template_directory() . '/inc/customizer.php';

require get_template_directory() . '/inc/custom-post-types/product.php';

add_action('wp_head','init_parameter');
function init_parameter()
{
    $product_url=site_url('/product/');
    ?>
    <script>
        let global_product_url='<?php echo $product_url ?>';
    </script>
    <?php
}
