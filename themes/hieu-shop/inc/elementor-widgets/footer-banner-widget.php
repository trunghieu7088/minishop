<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class Hieu_Shop_Footer_Banner_Widget extends Widget_Base {

    public function get_name() {
        return 'footer-banner-widget';
    }

    public function get_title() {
        return __( 'Footer Banner', 'hieu-shop' );
    }

    public function get_icon() {
        return 'eicon-call-to-action';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'hieu-shop' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'banner_title',
            [
                'label' => __( 'Banner Title', 'hieu-shop' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Ready to Enhance Your Beauty?', 'hieu-shop' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'banner_description',
            [
                'label' => __( 'Banner Description', 'hieu-shop' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Join thousands of satisfied customers and discover your perfect beauty routine today.', 'hieu-shop' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'hieu-shop' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Start Shopping', 'hieu-shop' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Button Background Color', 'hieu-shop' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b9d',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Button Text Color', 'hieu-shop' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => __( 'Button URL', 'hieu-shop' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-site.com/products', 'hieu-shop' ),
                'default' => [
                    'url' => '#products',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'hieu-shop' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b9d',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $banner_title = esc_html( $settings['banner_title'] );
        $banner_description = esc_html( $settings['banner_description'] );
        $button_text = esc_html( $settings['button_text'] );
        $button_color = esc_attr( $settings['button_color'] );
        $button_text_color = esc_attr( $settings['button_text_color'] );
        $button_url = $settings['button_url']['url'];
        $background_color = esc_attr( $settings['background_color'] );
        ?>
        <section class="footer-cta" style="background-color: <?php echo $background_color; ?>;">
            <div class="container">
                <div class="row text-center">
                    <div class="col-12">
                        <h3 class="text-white mb-3"><?php echo $banner_title; ?></h3>
                        <p class="text-white mb-4"><?php echo $banner_description; ?></p>
                        <a href="<?php echo esc_url( $button_url ); ?>" class="btn btn-footer-cta" style="background-color: <?php echo $button_color; ?>; color: <?php echo $button_text_color; ?>;">
                            <i class="fas fa-shopping-bag me-2"></i><?php echo $button_text; ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}