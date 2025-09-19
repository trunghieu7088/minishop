<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hieu_shop
 */

?>

		</div><!-- #content -->

		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-6 mb-4">
						<?php
						$branding_text = get_theme_mod( 'footer_branding_text', 'Your trusted partner in beauty and skincare. We bring you the finest cosmetic products to enhance your natural beauty.' );
						$facebook_link = get_theme_mod( 'footer_facebook_link', '#' );
						$instagram_link = get_theme_mod( 'footer_instagram_link', '#' );
						$x_link = get_theme_mod( 'footer_x_link', '#' );
						$youtube_link = get_theme_mod( 'footer_youtube_link', '#' );
						$logo_url = get_theme_mod( 'header_logo_image', '' );
						?>
						<h5>
							<?php if ( $logo_url ) {
								echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" style="max-height: 40px; margin-right: 10px;">';
							}
							else
							{
								echo '<i class="fas fa-gem me-2"></i>';
							}
							?>
							
							<?php bloginfo( 'name' ); ?>
						</h5>
						<p class="mb-4"><?php echo esc_html( $branding_text ); ?></p>
						<div class="social-links">
							<a target="_blank" href="<?php echo esc_url( $facebook_link ); ?>"><i class="fab fa-facebook"></i></a>
							<a target="_blank" href="<?php echo esc_url( $instagram_link ); ?>"><i class="fab fa-instagram"></i></a>
							<a target="_blank" href="<?php echo esc_url( $x_link ); ?>"><i class="fab fa-x"></i></a>
							<a target="_blank" href="<?php echo esc_url( $youtube_link ); ?>"><i class="fab fa-youtube"></i></a>
						</div>
					</div>
					<div class="col-lg-2 col-md-6 mb-4">
						<?php
						if ( is_active_sidebar( 'footer-quick-links' ) ) {
							add_filter( 'wp_nav_menu_args', 'glamour_beauty_add_footer_menu_class' );
							dynamic_sidebar( 'footer-quick-links' );
							remove_filter( 'wp_nav_menu_args', 'glamour_beauty_add_footer_menu_class' );
						} else {
							?>
							<h5>Quick Links</h5>
							<ul class="footer-links">
								<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
								<li><a href="<?php echo esc_url( home_url( '/products' ) ); ?>">Products</a></li>
								<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>">About Us</a></li>
								<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a></li>
							</ul>
							<?php
						}
						?>
					</div>
					<div class="col-lg-2 col-md-6 mb-4">
						<?php
						if ( is_active_sidebar( 'footer-categories' ) ) {
							dynamic_sidebar( 'footer-categories' );
						} else {
							?>
							<h5>Categories</h5>
							<ul class="footer-links">
								<li><a href="#">Skincare</a></li>
								<li><a href="#">Makeup</a></li>
								<li><a href="#">Fragrances</a></li>
								<li><a href="#">Hair Care</a></li>
							</ul>
							<?php
						}
						?>
					</div>
					<div class="col-lg-2 col-md-6 mb-4">
						<?php
						if ( is_active_sidebar( 'footer-customer-service' ) ) {
							dynamic_sidebar( 'footer-customer-service' );
						} else {
							?>
							<h5>Customer Service</h5>
							<ul class="footer-links">
								<li><a href="#">Help Center</a></li>
								<li><a href="#">Shipping Info</a></li>
								<li><a href="#">Returns</a></li>
								<li><a href="#">Size Guide</a></li>
							</ul>
							<?php
						}
						?>
					</div>
					<div class="col-lg-2 col-md-6 mb-4">
						<?php
						if ( is_active_sidebar( 'footer-account' ) ) {
							dynamic_sidebar( 'footer-account' );
						} else {
							?>
							<h5>Account</h5>
							<ul class="footer-links">
								<li><a href="#">My Account</a></li>
								<li><a href="#">Order History</a></li>
								<li><a href="#">Wishlist</a></li>
								<li><a href="#">Newsletter</a></li>
							</ul>
							<?php
						}
						?>
					</div>
				</div>
				<hr class="my-4" style="border-color: #4a5568;">
				<div class="row">
					<div class="col-12 text-center">
						<?php echo hieu_shop_get_footer_text(); ?>
					</div>
				</div>
			</div>
		</footer><!-- #colophon -->

		<?php wp_footer(); ?>

	</body>
</html>