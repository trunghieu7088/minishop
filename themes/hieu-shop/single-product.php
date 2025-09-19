<?php
/**
 * Single Product Template
 * Template Name: Single Product
 */

get_header();


$product_id = get_query_var('product_id', 1);


$api_url = "https://dummyjson.com/products/{$product_id}";
$response = wp_remote_get($api_url);
$product = array();
$related_products = array();

if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
    $product = json_decode(wp_remote_retrieve_body($response), true);
    
   
    if (!empty($product['category'])) {
        $category = $product['category'];
        $related_url = "https://dummyjson.com/products/category/" . urlencode($category) . "?limit=10";
        $related_response = wp_remote_get($related_url);
        
        if (!is_wp_error($related_response) && wp_remote_retrieve_response_code($related_response) === 200) {
            $related_data = json_decode(wp_remote_retrieve_body($related_response), true);
            $related_products = isset($related_data['products']) ? $related_data['products'] : array();
            
           
            $related_products = array_filter($related_products, function($item) use ($product_id) {
                return $item['id'] != $product_id;
            });
            
          
            $related_products = array_slice($related_products, 0, 4);
        }
    }
}


function generate_star_rating($rating, $show_rating_text = true) {
    $full_stars = floor($rating);
    $has_half_star = ($rating - $full_stars) >= 0.5;
    $stars = '';
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $stars .= '<i class="fas fa-star"></i>';
    }
    
    // Half star
    if ($has_half_star) {
        $stars .= '<i class="fas fa-star-half-alt"></i>';
    }
    
    // Empty stars
    $empty_stars = 5 - $full_stars - ($has_half_star ? 1 : 0);
    for ($i = 0; $i < $empty_stars; $i++) {
        $stars .= '<i class="far fa-star"></i>';
    }
    
    return $show_rating_text ? $stars . " <small>(" . number_format($rating, 1) . ")</small>" : $stars;
}


function calculate_discounted_price($price, $discount_percentage) {
    if ($discount_percentage && $discount_percentage > 0) {
        return number_format($price * (1 - $discount_percentage / 100), 2);
    }
    return number_format($price, 2);
}

// Set page title
if (!empty($product['title'])) {
    add_filter('wp_title', function() use ($product) {
        return $product['title'] . ' - ' . get_bloginfo('name');
    });
}
?>



<?php if (empty($product) || !isset($product['id'])): ?>
    <!-- Error State -->
    <section class="error-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Unable to load product details. Please try again later.
                    </div>
                    <a href="<?php echo home_url(); ?>" class="btn btn-add-cart">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
   

    <!-- Product Section -->
    <section class="product-section">
        <div class="container">
            <div class="row">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="product-gallery">
                        <div class="position-relative">
                            <?php $main_image = !empty($product['images']) ? $product['images'][0] : $product['thumbnail']; ?>
                            <img id="main-image" 
                                 src="<?php echo esc_url($main_image); ?>" 
                                 alt="<?php echo esc_attr($product['title']); ?>" 
                                 class="main-image">
                            <button class="zoom-icon" data-bs-toggle="modal" data-bs-target="#imageModal">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </div>
                        <?php if (!empty($product['images']) && count($product['images']) > 1): ?>
                            <div class="thumbnail-container">
                                <?php foreach ($product['images'] as $index => $image): ?>
                                    <img src="<?php echo esc_url($image); ?>" 
                                         alt="<?php echo esc_attr($product['title']); ?>" 
                                         class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                                         data-image="<?php echo esc_url($image); ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <span class="product-category">
                            <?php echo esc_html(ucwords(str_replace('-', ' ', $product['category'] ?? 'Beauty'))); ?>
                        </span>
                        <h1 class="product-title"><?php echo esc_html($product['title']); ?></h1>
                        
                        <?php if (!empty($product['rating'])): ?>
                            <div class="product-rating">
                                <?php echo generate_star_rating($product['rating']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex align-items-center mb-3">
                            <?php
                            $original_price = $product['price'];
                            $discount_percentage = isset($product['discountPercentage']) ? $product['discountPercentage'] : 0;
                            $discounted_price = calculate_discounted_price($original_price, $discount_percentage);
                            ?>
                            <span class="product-price">$<?php echo $discounted_price; ?></span>
                            
                            <?php if ($discount_percentage > 0): ?>
                                <span class="original-price">$<?php echo number_format($original_price, 2); ?></span>
                                <span class="discount-badge"><?php echo round($discount_percentage); ?>% OFF</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="product-description"><?php echo esc_html($product['description']); ?></p>

                        <!-- Product Actions -->
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="add-to-cart-form">
                            <?php wp_nonce_field('add_to_cart_nonce', 'cart_nonce'); ?>
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="product_id" value="<?php echo esc_attr($product['id']); ?>">
                            <input type="hidden" name="product_title" value="<?php echo esc_attr($product['title']); ?>">
                            <input type="hidden" name="product_price" value="<?php echo esc_attr($discounted_price); ?>">
                            <input type="hidden" name="product_image" value="<?php echo esc_attr($main_image); ?>">
                            
                           
                        </form>

                        <!-- Product Features -->
                        <div class="product-features">
                            <div class="feature-item">
                                <i class="fas fa-shipping-fast feature-icon"></i>
                                <span>Free shipping on orders over $50</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-undo feature-icon"></i>
                                <span>30-day return policy</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-award feature-icon"></i>
                                <span>Premium quality guaranteed</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-leaf feature-icon"></i>
                                <span>Cruelty-free and natural ingredients</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Customer Reviews</h2>
                    <p class="section-subtitle">What our customers say about this product</p>
                </div>
            </div>

            <?php if (!empty($product['reviews']) && is_array($product['reviews'])): ?>
                <!-- Reviews Summary -->
                <div class="row mb-5">
                    <div class="col-lg-4">
                        <div class="reviews-summary">
                            <?php
                            $total_reviews = count($product['reviews']);
                            $average_rating = array_sum(array_column($product['reviews'], 'rating')) / $total_reviews;
                            ?>
                            <div class="overall-rating">
                                <span class="rating-score"><?php echo number_format($average_rating, 1); ?></span>
                                <div class="rating-stars"><?php echo generate_star_rating($average_rating, false); ?></div>
                                <p class="rating-text">Based on <?php echo $total_reviews; ?> reviews</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="rating-breakdown">
                            <?php
                            // Calculate rating breakdown
                            $rating_counts = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0);
                            foreach ($product['reviews'] as $review) {
                                $rating_key = floor($review['rating']);
                                if (isset($rating_counts[$rating_key])) {
                                    $rating_counts[$rating_key]++;
                                }
                            }
                            
                            for ($i = 5; $i >= 1; $i--):
                                $count = $rating_counts[$i];
                                $percentage = $total_reviews > 0 ? ($count / $total_reviews) * 100 : 0;
                            ?>
                                <div class="rating-bar-item">
                                    <div class="rating-bar-stars">
                                        <?php echo generate_star_rating($i, false); ?>
                                    </div>
                                    <div class="rating-bar-progress">
                                        <div class="rating-bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <div class="rating-bar-count"><?php echo $count; ?></div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- Individual Reviews -->
                <div class="row">
                    <div class="col-12">
                        <?php foreach ($product['reviews'] as $review): ?>
                            <?php
                            $review_date = date('F j, Y', strtotime($review['date']));
                            $reviewer_name = $review['reviewerName'];
                            $name_parts = explode(' ', $reviewer_name);
                            $initials = strtoupper(substr($name_parts[0], 0, 1));
                            if (count($name_parts) > 1) {
                                $initials .= strtoupper(substr(end($name_parts), 0, 1));
                            }
                            ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar"><?php echo $initials; ?></div>
                                        <div class="reviewer-details">
                                            <h6><?php echo esc_html($reviewer_name); ?></h6>
                                            <div class="review-rating"><?php echo generate_star_rating($review['rating'], false); ?></div>
                                        </div>
                                    </div>
                                    <div class="review-date"><?php echo $review_date; ?></div>
                                </div>
                                <p class="review-comment"><?php echo esc_html($review['comment']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-12">
                        <div class="no-reviews">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <h5>No Reviews Yet</h5>
                            <p>Be the first to review this product!</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
        <section class="related-products">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="section-title">Related Products</h2>
                        <p class="section-subtitle">Discover more products you might love</p>
                    </div>
                </div>

                <div class="row">
                    <?php foreach ($related_products as $related_product): ?>
                        <?php
                        $related_original_price = $related_product['price'];
                        $related_discount = isset($related_product['discountPercentage']) ? $related_product['discountPercentage'] : 0;
                        $related_final_price = calculate_discounted_price($related_original_price, $related_discount);
                        $product_url=site_url('/product/').$related_product['id'];
                        ?>
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                            <div class="product-card">
                                <a href="<?php echo $product_url; ?>">
                                    <img src="<?php echo esc_url($related_product['thumbnail']); ?>" 
                                         alt="<?php echo esc_attr($related_product['title']); ?>" 
                                         class="product-image">
                                </a>
                                <div class="product-card-info">
                                    <span class="product-card-category">
                                        <?php echo esc_html(ucwords(str_replace('-', ' ', $related_product['category'] ?? 'Beauty'))); ?>
                                    </span>
                                    <h3 class="product-card-title">
                                        <a href="<?php echo $product_url; ?>">
                                            <?php echo esc_html($related_product['title']); ?>
                                        </a>
                                    </h3>
                                    <div class="product-rating">
                                        <?php echo generate_star_rating($related_product['rating'] ?? 4.5); ?>
                                    </div>
                                    <div class="product-card-price">
                                        $<?php echo $related_final_price; ?>
                                        <?php if ($related_discount > 0): ?>
                                            <small class="text-muted text-decoration-line-through ms-2">
                                                $<?php echo number_format($related_original_price, 2); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                        <a href="<?php echo $product_url; ?>" class="btn btn-add-cart">
                                            View
                                        </a>
                                    
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-close"></i></button>
                <div class="modal-body p-0">
                    <img id="modal-image" 
                         src="<?php echo esc_url($main_image); ?>" 
                         alt="<?php echo esc_attr($product['title']); ?>" 
                         class="modal-image">
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thumbnail click handler
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('main-image');
    const modalImage = document.getElementById('modal-image');
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const newImage = this.getAttribute('data-image');
            if (mainImage && modalImage && newImage) {
                mainImage.src = newImage;
                modalImage.src = newImage;
                
                // Update active thumbnail
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
    
    // Quantity controls
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const quantityInput = document.getElementById('quantity');
    
    if (decreaseBtn && increaseBtn && quantityInput) {
        increaseBtn.addEventListener('click', function() {
            const currentQty = parseInt(quantityInput.value) || 1;
            const maxQty = parseInt(quantityInput.getAttribute('max')) || 10;
            if (currentQty < maxQty) {
                quantityInput.value = currentQty + 1;
            }
        });
        
        decreaseBtn.addEventListener('click', function() {
            const currentQty = parseInt(quantityInput.value) || 1;
            const minQty = parseInt(quantityInput.getAttribute('min')) || 1;
            if (currentQty > minQty) {
                quantityInput.value = currentQty - 1;
            }
        });
        
        // Handle quantity input changes
        quantityInput.addEventListener('change', function() {
            const value = parseInt(this.value) || 1;
            const min = parseInt(this.getAttribute('min')) || 1;
            const max = parseInt(this.getAttribute('max')) || 10;
            
            if (value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
            }
        });
        
        // Prevent non-numeric input
        quantityInput.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                e.preventDefault();
            }
        });
    }
    
    // Wishlist toggle
    const wishlistBtn = document.getElementById('add-to-wishlist');
    if (wishlistBtn) {
        // Check if item is already in wishlist
        const productId = wishlistBtn.getAttribute('data-product-id');
        const wishlist = JSON.parse(sessionStorage.getItem('wishlist') || '[]');
        
        if (wishlist.includes(productId)) {
            const icon = wishlistBtn.querySelector('i');
            icon.classList.remove('far');
            icon.classList.add('fas');
            wishlistBtn.innerHTML = '<i class="fas fa-heart me-2"></i>Remove from Wishlist';
        }
        
        wishlistBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const productId = this.getAttribute('data-product-id');
            let wishlist = JSON.parse(sessionStorage.getItem('wishlist') || '[]');
            
            if (icon.classList.contains('fas')) {
                // Remove from wishlist
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.innerHTML = '<i class="far fa-heart me-2"></i>Add to Wishlist';
                wishlist = wishlist.filter(id => id !== productId);
                showToast('Removed from wishlist', 'info');
            } else {
                // Add to wishlist
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.innerHTML = '<i class="fas fa-heart me-2"></i>Remove from Wishlist';
                if (!wishlist.includes(productId)) {
                    wishlist.push(productId);
                }
                showToast('Added to wishlist', 'success');
            }
            
            sessionStorage.setItem('wishlist', JSON.stringify(wishlist));
        });
    }
    
    // Add to cart form submissions
    const cartForms = document.querySelectorAll('form[action*="admin-post.php"]');
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const productTitle = this.querySelector('input[name="product_title"]');
            
            if (submitBtn && !submitBtn.disabled) {
                // Animate button
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                submitBtn.disabled = true;
                
                // Show success message after short delay
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
                    submitBtn.classList.add('btn-success');
                    
                    if (productTitle) {
                        showToast(`${productTitle.value} added to cart`, 'success');
                    }
                    
                    // Reset button after delay
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.classList.remove('btn-success');
                        submitBtn.disabled = false;
                    }, 2000);
                }, 500);
            }
        });
    });
    
    // Handle URL parameter success message
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('cart_added') === '1') {
        showToast('Product added to cart successfully!', 'success');
        // Clean URL
        const newUrl = window.location.pathname + '?product_id=' + (urlParams.get('product_id') || '1');
        window.history.replaceState({}, '', newUrl);
    }
});

// Toast notification function
function showToast(message, type = 'success') {
    const toastColor = type === 'success' ? 'bg-success' : type === 'info' ? 'bg-info' : 'bg-primary';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'info' ? 'fa-info-circle' : 'fa-bell';
    
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast custom-toast align-items-center text-white ${toastColor} border-0`;
    toast.style.cssText = 'position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 250px;';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas ${icon} me-2"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.toast').remove()"></button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Animate in
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'transform 0.3s ease';
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }
    }, 4000);
}
</script>

<?php

get_footer();
?>