<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!class_exists('Hieu_Shop_Hero_Banner_Widget')) {
    class Hieu_Shop_Hero_Banner_Widget extends Widget_Base {

        public function get_name() {
            return 'hieu_shop_hero_banner';
        }

        public function get_title() {
            return __('Hero Banner', 'hieu-shop');
        }

        public function get_icon() {
            return 'eicon-banner';
        }

        public function get_categories() {
            return ['general'];
        }

        protected function register_controls() {
            $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Content', 'hieu-shop'),
                ]
            );

            $this->add_control(
                'background_image',
                [
                    'label' => __('Background Image', 'hieu-shop'),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => '',
                    ],
                ]
            );

            $this->add_control(
                'hero_title',
                [
                    'label' => __('Hero Title', 'hieu-shop'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Discover Your Natural Beauty', 'hieu-shop'),
                ]
            );

            $this->add_control(
                'hero_subtitle',
                [
                    'label' => __('Hero Subtitle', 'hieu-shop'),
                    'type' => Controls_Manager::TEXTAREA,
                    'default' => __('Premium cosmetics and skincare products that enhance your natural radiance. Explore our curated collection of beauty essentials.', 'hieu-shop'),
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __('Button Text', 'hieu-shop'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Shop Now', 'hieu-shop'),
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label' => __('Button Icon', 'hieu-shop'),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fas fa-star',
                ]
            );

            $this->add_control(
                'button_url',
                [
                    'label' => __('Button URL', 'hieu-shop'),
                    'type' => Controls_Manager::URL,
                    'default' => [
                        'url' => '#products',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $background_url = $settings['background_image']['url'];
            $title = $settings['hero_title'];
            $subtitle = $settings['hero_subtitle'];
            $button_text = $settings['button_text'];
            $button_icon = $settings['button_icon'];
            $button_url = $settings['button_url']['url'];

            ?>
            <section id="home" class="hero-banner" style="background-image: url(<?php echo esc_url($background_url); ?>);">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="hero-content">
                                <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
                                <p class="hero-subtitle"><?php echo esc_html($subtitle); ?></p>
                                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-hero">
                                    <i class="<?php echo esc_attr($button_icon); ?> me-2"></i><?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-center">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        }
    }
}