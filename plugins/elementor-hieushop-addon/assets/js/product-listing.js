(function ($) {
  $(document).ready(function() {
            let allProducts = [];
            let displayedProducts = [];
            let currentPage = 0;
            const productsPerPage = 8;
            let cartCount = 0;
            let currentFilter = 'all';
            let currentSort = 'default';

            // Fetch products from DummyJSON API
            async function fetchProducts() {
                try {
                    const response = await $.ajax({
                        url: 'https://dummyjson.com/products',
                        method: 'GET'
                    });
                    
                    allProducts = response.products.filter(product => 
                        product.category.toLowerCase().includes('beauty') || 
                        product.category.toLowerCase().includes('fragrances') ||
                        product.category.toLowerCase().includes('skincare') ||
                        product.title.toLowerCase().includes('beauty') ||
                        product.title.toLowerCase().includes('cosmetic')
                    );
                    
                    // If no beauty products found, use all products
                    if (allProducts.length === 0) {
                        allProducts = response.products;
                    }
                    
                    const filteredAndSortedProducts = getFilteredAndSortedProducts();
                    displayProducts(filteredAndSortedProducts);
                } catch (error) {
                    console.error('Error fetching products:', error);
                    $('#products-container').html(`
                        <div class="col-12 text-center">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Unable to load products. Please try again later.
                            </div>
                        </div>
                    `);
                }
            }

            // Sort products
            function sortProducts(products, sortType) {
                let sortedProducts = [...products];
                
                switch(sortType) {
                    case 'price-low':
                        sortedProducts.sort((a, b) => {
                            const priceA = a.discountPercentage ? 
                                (a.price * (1 - a.discountPercentage / 100)) : a.price;
                            const priceB = b.discountPercentage ? 
                                (b.price * (1 - b.discountPercentage / 100)) : b.price;
                            return priceA - priceB;
                        });
                        break;
                    case 'price-high':
                        sortedProducts.sort((a, b) => {
                            const priceA = a.discountPercentage ? 
                                (a.price * (1 - a.discountPercentage / 100)) : a.price;
                            const priceB = b.discountPercentage ? 
                                (b.price * (1 - b.discountPercentage / 100)) : b.price;
                            return priceB - priceA;
                        });
                        break;
                    case 'rating-high':
                        sortedProducts.sort((a, b) => (b.rating || 4.5) - (a.rating || 4.5));
                        break;
                    case 'rating-low':
                        sortedProducts.sort((a, b) => (a.rating || 4.5) - (b.rating || 4.5));
                        break;
                    case 'name-az':
                        sortedProducts.sort((a, b) => a.title.localeCompare(b.title));
                        break;
                    case 'name-za':
                        sortedProducts.sort((a, b) => b.title.localeCompare(a.title));
                        break;
                    default:
                        // Keep original order
                        break;
                }
                
                return sortedProducts;
            }

            // Get filtered and sorted products
            function getFilteredAndSortedProducts() {
                let filteredProducts;
                
                if (currentFilter === 'all') {
                    filteredProducts = allProducts;
                } else {
                    filteredProducts = allProducts.filter(product => 
                        product.category.toLowerCase().includes(currentFilter.toLowerCase())
                    );
                }
                
                return sortProducts(filteredProducts, currentSort);
            }

            // Display products
            function displayProducts(products, append = false) {
                if (!append) {
                    $('#products-container').empty();
                    displayedProducts = [];
                    currentPage = 0;
                }

                const startIndex = currentPage * productsPerPage;
                const endIndex = startIndex + productsPerPage;
                const productsToShow = products.slice(startIndex, endIndex);

                displayedProducts = [...displayedProducts, ...productsToShow];

                productsToShow.forEach(product => {
                    const productCard = createProductCard(product);
                    $('#products-container').append(productCard);
                });

                // Show/hide load more button
                if (endIndex < products.length) {
                    $('#load-more').show();
                } else {
                    $('#load-more').hide();
                }

                currentPage++;

                // Add animation to new cards
                $('.product-card').addClass('animate__fadeInUp');
            }

            // Create product card HTML
            function createProductCard(product) {
                const rating = generateStarRating(product.rating || 4.5);
                const category = product.category || 'Beauty';
                const discountedPrice = product.discountPercentage ? 
                    (product.price * (1 - product.discountPercentage / 100)).toFixed(2) : 
                    product.price;

                return `
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            <img src="${product.thumbnail}" alt="${product.title}" class="product-image">
                            <div class="product-info">
                                <span class="product-category">${category}</span>
                                <h3 class="product-title">${product.title}</h3>
                                <div class="product-rating">${rating}</div>
                                <div class="product-price">
                                    $${discountedPrice}
                                    ${product.discountPercentage ? `<small class="text-muted text-decoration-line-through ms-2">$${product.price}</small>` : ''}
                                </div>
                                <a class="btn btn-add-cart" href="${global_product_url}${product.id}">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Generate star rating
            function generateStarRating(rating) {
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 >= 0.5;
                let stars = '';

                for (let i = 0; i < fullStars; i++) {
                    stars += '<i class="fas fa-star"></i>';
                }

                if (hasHalfStar) {
                    stars += '<i class="fas fa-star-half-alt"></i>';
                }

                const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
                for (let i = 0; i < emptyStars; i++) {
                    stars += '<i class="far fa-star"></i>';
                }

                return stars + ` <small>(${rating})</small>`;
            }

            // Filter products by category
            $('.filter-btn').click(function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                
                currentFilter = $(this).data('category');
                const filteredAndSortedProducts = getFilteredAndSortedProducts();
                displayProducts(filteredAndSortedProducts);
            });

            // Sort products
            $('#sortSelect').change(function() {
                currentSort = $(this).val();
                const filteredAndSortedProducts = getFilteredAndSortedProducts();
                displayProducts(filteredAndSortedProducts);
            });

            // Load more products
            $('#load-more').click(function() {
                const filteredAndSortedProducts = getFilteredAndSortedProducts();
                displayProducts(filteredAndSortedProducts, true);
            });

            // Smooth scrolling for navigation links
            $('a[href^="#"]').click(function(e) {
                e.preventDefault();
                const target = $($(this).attr('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 1000);
                }
            });

            // Navbar background on scroll
            $(window).scroll(function() {
                if ($(window).scrollTop() > 50) {
                    $('.navbar').addClass('scrolled');
                } else {
                    $('.navbar').removeClass('scrolled');
                }
            });

            // Initialize
            fetchProducts();
        });

      
})(jQuery);