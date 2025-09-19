<?php
function register_product_post_type() {
    $labels = array(
        'name' => __('Products'),
        'singular_name' => __('Product'),
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite' => array('slug' => 'product', 'with_front' => false), // Tránh xung đột với trang
    );
    register_post_type('product', $args);
}
add_action('init', 'register_product_post_type');


function add_custom_query_vars($vars) {
    $vars[] = 'product_id';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');


add_action('init', function () {
    add_rewrite_rule(
        '^product/([0-9]+)/?$',
        'index.php?product_id=$matches[1]',
        'top'
    );
});

// Filter template để ưu tiên single-product.php
function register_single_product_template($template) {
    global $wp_query;
    if (get_query_var('product_id')) {
        $new_template = locate_template(array('single-product.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'register_single_product_template');

// Flush rewrite rules khi kích hoạt theme
function flush_rewrite_rules_on_activation() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');