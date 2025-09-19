<?php
/**
 * Hieu shop Theme Customizer
 *
 * @package Hieu_shop
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hieu_shop_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'hieu_shop_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'hieu_shop_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'hieu_shop_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function hieu_shop_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function hieu_shop_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function hieu_shop_customize_preview_js() {
	wp_enqueue_script( 'hieu-shop-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'hieu_shop_customize_preview_js' );

//custom code

function glamour_beauty_default_menu() {
    ?>
    <ul id="primary-menu" class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/products' ) ); ?>">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/about' ) ); ?>">About</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a></li>
    </ul>
    <?php
}

function glamour_beauty_add_footer_menu_class($args) {
    $args['menu_class'] = 'footer-links'; // Thêm class footer-links cho menu
    return $args;
}

function glamour_beauty_customize_register( $wp_customize ) {
    // Section for Header Logo
    $wp_customize->add_section( 'header_logo_section', array(
        'title'    => __( 'Header Logo', 'glamour-beauty' ),
        'priority' => 20,
    ) );

    $wp_customize->add_setting( 'header_logo_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_logo_image', array(
        'label'    => __( 'Logo Image', 'glamour-beauty' ),
        'section'  => 'header_logo_section',
        'settings' => 'header_logo_image',
    ) ) );

    // Section for Footer Branding
    $wp_customize->add_section( 'footer_branding_section', array(
        'title'    => __( 'Footer Branding', 'glamour-beauty' ),
        'priority' => 30,
    ) );

    // Setting for Branding Text
    $wp_customize->add_setting( 'footer_branding_text', array(
        'default'           => 'Your trusted partner in beauty and skincare. We bring you the finest cosmetic products to enhance your natural beauty.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'footer_branding_text', array(
        'label'    => __( 'Branding Text', 'glamour-beauty' ),
        'section'  => 'footer_branding_section',
        'type'     => 'textarea',
    ) );

    // Setting for Social Links
    $wp_customize->add_setting( 'footer_facebook_link', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_facebook_link', array(
        'label'    => __( 'Facebook Link', 'glamour-beauty' ),
        'section'  => 'footer_branding_section',
        'type'     => 'url',
    ) );

    $wp_customize->add_setting( 'footer_instagram_link', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_instagram_link', array(
        'label'    => __( 'Instagram Link', 'glamour-beauty' ),
        'section'  => 'footer_branding_section',
        'type'     => 'url',
    ) );

    $wp_customize->add_setting( 'footer_x_link', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_x_link', array(
        'label'    => __( 'X Link', 'glamour-beauty' ),
        'section'  => 'footer_branding_section',
        'type'     => 'url',
    ) );

    $wp_customize->add_setting( 'footer_youtube_link', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_youtube_link', array(
        'label'    => __( 'YouTube Link', 'glamour-beauty' ),
        'section'  => 'footer_branding_section',
        'type'     => 'url',
    ) );

    $wp_customize->add_section('hieu_shop_footer_section', array(
        'title'    => __('Footer heading text', 'hieu-shop'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('hieu_shop_footer_text', array(
        'default'   => '© Minishop All rights reserved. Designed with the heart for beautiful people.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hieu_shop_footer_text', array(
        'label'    => __('Footer Text', 'hieu-shop'),
        'section'  => 'hieu_shop_footer_section',
        'type'     => 'textarea',
    ));
}

function glamour_beauty_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Quick Links', 'glamour-beauty' ),
        'id'            => 'footer-quick-links',
        'description'   => esc_html__( 'Add widgets here to appear in the footer quick links section.', 'glamour-beauty' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Categories', 'glamour-beauty' ),
        'id'            => 'footer-categories',
        'description'   => esc_html__( 'Add widgets here to appear in the footer categories section.', 'glamour-beauty' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Customer Service', 'glamour-beauty' ),
        'id'            => 'footer-customer-service',
        'description'   => esc_html__( 'Add widgets here to appear in the footer customer service section.', 'glamour-beauty' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Account', 'glamour-beauty' ),
        'id'            => 'footer-account',
        'description'   => esc_html__( 'Add widgets here to appear in the footer account section.', 'glamour-beauty' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ) );
}

add_action( 'customize_register', 'glamour_beauty_customize_register' );
add_action( 'widgets_init', 'glamour_beauty_widgets_init' );
