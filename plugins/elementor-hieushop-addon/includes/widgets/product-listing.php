<?php

namespace Elementor_HieuShop_Addon;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Product_Listing_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'product-listing';
    }

    public function get_title()
    {
        return esc_html__('Product Listing', 'elementor-product-listing-addon');
    }

    public function get_icon()
    {
        return 'eicon-products';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'elementor-product-listing-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'elementor-product-listing-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Our Premium Collection', 'elementor-product-listing-addon'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'elementor-product-listing-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Discover the finest beauty products carefully selected for you', 'elementor-product-listing-addon'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
    ?>
        <div id="products" class="products-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="section-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                        <p class="section-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></p>
                    </div>
                </div>

                <!-- Filter and Sort Controls -->
                <div class="row mb-4">
                    <div class="col-lg-8 col-md-7">
                        <div class="filter-buttons">
                            <button class="btn filter-btn active" data-category="all">All Products</button>
                            <button class="btn filter-btn" data-category="beauty">Beauty</button>
                            <button class="btn filter-btn" data-category="fragrances">Fragrances</button>
                            <button class="btn filter-btn" data-category="skincare">Skincare</button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <div class="sort-controls">
                            <label for="sortSelect" class="form-label" style="font-weight: 600; color: var(--text-dark);">Sort by:</label>
                            <select class="form-select" id="sortSelect">
                                <option value="default">Default</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating-high">Rating: High to Low</option>
                                <option value="rating-low">Rating: Low to High</option>
                                <option value="name-az">Name: A to Z</option>
                                <option value="name-za">Name: Z to A</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row" id="products-container">
                    <!-- Loading Spinner -->
                    <div class="col-12">
                        <div class="loading-spinner">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button class="btn btn-outline-primary btn-lg" id="load-more" style="display: none;">
                            <i class="fas fa-plus me-2"></i>Load More Products
                        </button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
