<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hieu_shop
 */

?>
<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Glamour_Beauty
 * @since 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="site">
		<header id="masthead" class="site-header">
			<nav class="navbar navbar-expand-lg fixed-top">
				<div class="container">
					<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" id="navbar-brand" rel="home">
					<!--	<i class="fas fa-gem me-2"></i><?php bloginfo( 'name' ); ?> -->
						<?php
						$logo_url = get_theme_mod( 'header_logo_image', '' );
						if ( $logo_url ) {
							echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" style="max-height: 40px; margin-right: 10px;">';
						}
						?>
						<?php bloginfo( 'name' ); ?>
					</a>
					
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
						<span class="navbar-toggler-icon"></span>
					</button>
					
					<div class="collapse navbar-collapse" id="navbarNav">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'navbar-nav ms-auto',
							'fallback_cb'    => 'glamour_beauty_default_menu',
							'walker'         => new Glamour_Beauty_Nav_Walker(),
						) );
						?>
					</div>
				</div>
			</nav>
		</header><!-- #masthead -->

		<div id="content" class="site-content">