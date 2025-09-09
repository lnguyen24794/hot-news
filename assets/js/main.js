(function ($) {
    "use strict";
    
    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 150) {
            $('.nav-bar').addClass('nav-sticky');
        } else {
            $('.nav-bar').removeClass('nav-sticky');
        }
    });
    
    
    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 768) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });
    
    
    // Top News Slider
    $('.tn-slider').slick({
        autoplay: true,
        infinite: true,
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1
    });
    
    
    // Category News Slider
    $('.cn-slider').slick({
        autoplay: false,
        infinite: true,
        dots: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
    
    
    // Related News Slider
    $('.sn-slider').slick({
        autoplay: false,
        infinite: true,
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
    
    
    // Like/Dislike functionality
    $('.like-btn, .dislike-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var postId = button.data('post-id');
        var actionType = button.data('action');
        
        // Disable button to prevent multiple clicks
        button.prop('disabled', true);
        
        $.ajax({
            url: hot_news_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'hot_news_like_dislike',
                post_id: postId,
                action_type: actionType,
                nonce: hot_news_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update count
                    button.find('.reaction-count').text(response.data.new_count);
                    
                    // Show success message
                    alert(response.data.message);
                    
                    // Change button style to indicate action completed
                    if (actionType === 'like') {
                        button.removeClass('btn-outline-success').addClass('btn-success');
                    } else {
                        button.removeClass('btn-outline-danger').addClass('btn-danger');
                    }
                } else {
                    alert(response.data);
                    button.prop('disabled', false);
                }
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
                button.prop('disabled', false);
            }
        });
    });
    
    // Analytics tracking
    var startTime = Date.now();
    var maxScroll = 0;
    var pageHeight = $(document).height() - $(window).height();
    
    // Track scroll depth
    $(window).scroll(function() {
        var scrollTop = $(window).scrollTop();
        var scrollPercent = Math.round((scrollTop / pageHeight) * 100);
        
        if (scrollPercent > maxScroll) {
            maxScroll = scrollPercent;
        }
    });
    
    // Track time on page and scroll depth when user leaves
    $(window).on('beforeunload', function() {
        var timeOnPage = Math.round((Date.now() - startTime) / 1000); // seconds
        
        // Send analytics data via AJAX
        if (typeof hot_news_ajax !== 'undefined') {
            $.ajax({
                url: hot_news_ajax.ajax_url,
                type: 'POST',
                async: false,
                data: {
                    action: 'hot_news_track_interaction',
                    time_on_page: timeOnPage,
                    scroll_depth: maxScroll,
                    nonce: hot_news_ajax.nonce
                }
            });
        }
    });
    
    // Track clicks on important elements
    $('a, button, .like-btn, .dislike-btn').on('click', function() {
        var element = $(this);
        var elementType = element.prop('tagName').toLowerCase();
        var elementClass = element.attr('class') || '';
        var elementText = element.text().trim().substring(0, 50);
        
        if (typeof hot_news_ajax !== 'undefined') {
            $.ajax({
                url: hot_news_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'hot_news_track_interaction',
                    interaction_type: 'click',
                    interaction_value: elementType + '|' + elementClass + '|' + elementText,
                    nonce: hot_news_ajax.nonce
                }
            });
        }
    });
    
    // Load More Posts Functionality
    var isLoading = false;
    var loadMoreBtn = $('#load-more-btn');
    var loadingIndicator = $('#loading-indicator');
    var noMorePosts = $('#no-more-posts');
    var newsFeedContainer = $('#news-feed-container');
    
    // Load More Button Click Handler
    loadMoreBtn.on('click', function(e) {
        e.preventDefault();
        
        if (isLoading) {
            return;
        }
        
        loadMorePosts();
    });
    
    // Infinite Scroll Detection
    var infiniteScrollEnabled = true;
    var scrollThreshold = 300; // pixels from bottom
    
    $(window).scroll(function() {
        if (!infiniteScrollEnabled || isLoading || !loadMoreBtn.is(':visible')) {
            return;
        }
        
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        var documentHeight = $(document).height();
        
        // Check if user is near bottom of page
        if (scrollTop + windowHeight >= documentHeight - scrollThreshold) {
            loadMorePosts();
        }
    });
    
    // Load More Posts Function
    function loadMorePosts() {
        if (isLoading) {
            return;
        }
        
        isLoading = true;
        
        var currentPage = parseInt(loadMoreBtn.data('page'));
        var maxPages = parseInt(loadMoreBtn.data('max-pages'));
        
        // Show loading indicator
        loadMoreBtn.hide();
        loadingIndicator.show();
        
        // Prepare data for AJAX request
        var ajaxData = {
            action: 'hot_news_load_more_posts',
            page: currentPage,
            nonce: hot_news_ajax.load_more_nonce,
            category: loadMoreBtn.data('category') || '',
            tag: loadMoreBtn.data('tag') || '',
            author: loadMoreBtn.data('author') || 0,
            year: loadMoreBtn.data('year') || 0,
            month: loadMoreBtn.data('month') || 0,
            day: loadMoreBtn.data('day') || 0
        };
        
        $.ajax({
            url: hot_news_ajax.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                if (response.success) {
                    // Append new posts with animation
                    var newPosts = $(response.data.posts_html);
                    newPosts.hide();
                    newsFeedContainer.append(newPosts);
                    
                    // Animate new posts
                    newPosts.each(function(index) {
                        var post = $(this);
                        setTimeout(function() {
                            post.fadeIn(400);
                        }, index * 100);
                    });
                    
                    // Update page number
                    loadMoreBtn.data('page', currentPage + 1);
                    
                    // Check if there are more posts
                    if (response.data.has_more && currentPage < maxPages) {
                        // Show load more button again
                        setTimeout(function() {
                            loadingIndicator.hide();
                            loadMoreBtn.show();
                        }, 500);
                    } else {
                        // No more posts
                        loadingIndicator.hide();
                        noMorePosts.show();
                        infiniteScrollEnabled = false;
                    }
                } else {
                    // Error occurred
                    loadingIndicator.hide();
                    noMorePosts.show();
                    infiniteScrollEnabled = false;
                    
                    // Show error message
                    showNotification('error', response.data || 'Có lỗi xảy ra khi tải tin tức');
                }
            },
            error: function(xhr, status, error) {
                loadingIndicator.hide();
                loadMoreBtn.show();
                
                // Show error message
                showNotification('error', 'Không thể tải thêm tin tức. Vui lòng thử lại!');
            },
            complete: function() {
                isLoading = false;
            }
        });
    }
    
    // Notification System
    function showNotification(type, message) {
        var notificationClass = type === 'error' ? 'alert-danger' : 'alert-success';
        var notification = $('<div class="alert ' + notificationClass + ' alert-dismissible fade show notification-toast" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>');
        
        // Add to page
        $('body').append(notification);
        
        // Position notification
        notification.css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            zIndex: 9999,
            maxWidth: '300px'
        });
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            notification.alert('close');
        }, 5000);
    }
    
    // Smooth scroll animation for new posts
    function scrollToNewPosts(element) {
        $('html, body').animate({
            scrollTop: element.offset().top - 100
        }, 600, 'easeInOutExpo');
    }
    
    // Lazy loading for images (optional enhancement)
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            var imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            $('.lazy').each(function() {
                imageObserver.observe(this);
            });
        }
    }
    
    // Initialize lazy loading
    initLazyLoading();
    
    // Refresh lazy loading when new posts are loaded
    $(document).on('DOMNodeInserted', '.news-feed-item', function() {
        initLazyLoading();
    });
    
})(jQuery);

