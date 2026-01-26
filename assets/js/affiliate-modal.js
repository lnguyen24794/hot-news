/**
 * Affiliate Overlay Script
 * Simple overlay instead of Bootstrap modal
 */

jQuery(document).ready(function($) {
    'use strict';

    // Variables
    let modalTimeout;
    let modalShown = false;
    let exitIntentTriggered = false;
    var currentAffiliate = null;
    
    // Device storage settings
    const STORAGE_KEY = window.location.href;
    const STORAGE_DURATION = 10 * 60 * 1000; // 10 minutes in milliseconds

    // Check if overlay element exists
    if ($('#affiliateOverlay').length === 0) {
        console.error('❌ Affiliate overlay element not found! HTML may not be loaded.');
        return;
    }

    // Check if we should show modal
    if (!hotNewsAffiliateModal.show_modal) {
        console.log('🚫 Affiliate overlay disabled in settings');
        return;
    }

    console.log('🎬 Affiliate Overlay initialized:', hotNewsAffiliateModal);
    console.log('📊 Active affiliates count:', hotNewsAffiliateModal.active_count);

    /**
     * Check if device storage is valid (not expired)
     */
    function isDeviceStorageValid() {
        try {
            const storedData = localStorage.getItem(STORAGE_KEY);
            if (!storedData) {
                return false;
            }
            
            const data = JSON.parse(storedData);
            const currentTime = new Date().getTime();
            const timeElapsed = currentTime - data.timestamp;
            
            if (timeElapsed < STORAGE_DURATION) {
                if (hotNewsAffiliateModal.debug) {
                    const remainingDays = Math.ceil((STORAGE_DURATION - timeElapsed) / (24 * 60 * 60 * 1000));
                    console.log('✅ Device storage valid. Remaining days:', remainingDays);
                }
                return true;
            } else {
                if (hotNewsAffiliateModal.debug) {
                    console.log('⏰ Device storage expired');
                }
                return false;
            }
        } catch (e) {
            if (hotNewsAffiliateModal.debug) {
                console.error('❌ Error reading device storage:', e);
            }
            return false;
        }
    }
    
    /**
     * Save device storage with current timestamp
     */
    function saveDeviceStorage() {
        try {
            const data = {
                timestamp: new Date().getTime(),
                deviceInfo: {
                    userAgent: navigator.userAgent,
                    screenWidth: window.screen.width,
                    screenHeight: window.screen.height
                }
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            
            if (hotNewsAffiliateModal.debug) {
                console.log('💾 Device storage saved:', data);
            }
        } catch (e) {
            if (hotNewsAffiliateModal.debug) {
                console.error('❌ Error saving device storage:', e);
            }
        }
    }
    
    /**
     * Clear device storage
     */
    function clearDeviceStorage() {
        try {
            localStorage.removeItem(STORAGE_KEY);
            if (hotNewsAffiliateModal.debug) {
                console.log('🗑️ Device storage cleared');
            }
        } catch (e) {
            if (hotNewsAffiliateModal.debug) {
                console.error('❌ Error clearing device storage:', e);
            }
        }
    }

    /**
     * Initialize modal delay
     */
    // Check device storage before initializing modal
    if (isDeviceStorageValid()) {
        if (hotNewsAffiliateModal.debug) {
            console.log('🚫 Modal already shown within 3 days. Skipping...');
        }
        return;
    }
    
    modalTimeout = setTimeout(function() {
        if (!modalShown) {
            showAffiliateModal();
        }
    }, parseInt(hotNewsAffiliateModal.delay));
    
    /**
     * Show affiliate overlay
     */
    function showAffiliateModal() {
        if (modalShown) {
            return;
        }

        if (hotNewsAffiliateModal.debug) {
            console.log('🚀 Showing affiliate overlay...');
        }

        modalShown = true;
        
        // Save device storage to prevent showing again for 3 days
        saveDeviceStorage();

        // Show loading state
        showLoadingState();

        // Add blur effect to main content
        $('.site-main, .site-header, .site-footer').addClass('content-blurred');

        // Show overlay with fade in effect
        $('#affiliateOverlay').fadeIn(300);

        if (hotNewsAffiliateModal.debug) {
            console.log('✅ Affiliate overlay displayed, loading affiliate data...');
        }

        // Load affiliate data
        loadRandomAffiliate();
    }
    
    /**
     * Show loading state
     */
    function showLoadingState() {
        $('#affiliate-content, #affiliate-error').hide();
        $('#affiliate-loading').show();
    }

    /**
     * Show content state
     */
    function showContentState() {
        $('#affiliate-loading, #affiliate-error').hide();
        $('.affiliate-content').show();
    }

    /**
     * Show error state
     */
    function showErrorState(message) {
        $('#affiliate-loading, .affiliate-content').hide();
        $('#affiliate-error').show();
        $('#error-message').text(message);
    }

    /**
     * Load random affiliate data
     */
    function loadRandomAffiliate() {
        if (hotNewsAffiliateModal.debug) {
            console.log('🔄 Loading random affiliate...');
        }

        $.ajax({
            url: hotNewsAffiliateModal.ajax_url,
            type: 'POST',
            data: {
                action: 'hot_news_get_random_affiliate',
                nonce: hotNewsAffiliateModal.nonce
            },
            success: function(response) {
                if (hotNewsAffiliateModal.debug) {
                    console.log('📡 AJAX Response:', response);
                }

                if (response.success && response.data) {
                    displayAffiliateContent(response.data);
                    currentAffiliate = response.data;
                } else {
                    const errorMsg = response.data || 'Không tìm thấy affiliate link nào.';
                    if (hotNewsAffiliateModal.debug) {
                        console.log('❌ No affiliates found:', errorMsg);
                    }
                    showErrorState(errorMsg);
                }
            },
            error: function(xhr, status, error) {
                if (hotNewsAffiliateModal.debug) {
                    console.log('💥 AJAX Error:', {xhr: xhr, status: status, error: error});
                }
                showErrorState('Có lỗi xảy ra khi tải nội dung. Vui lòng thử lại sau.');
            }
        });
    }
    
    /**
     * Display affiliate content in overlay
     */
    function displayAffiliateContent(affiliate) {
        if (hotNewsAffiliateModal.debug) {
            console.log('🎨 Displaying affiliate content:', affiliate);
        }
        
        // Update overlay content
        $('#affiliate-popup-image').attr('src', affiliate.image_url).attr('alt', affiliate.title);
        $('#affiliate-popup-title').text(affiliate.title);
        $('#affiliate-popup-link').attr('href', affiliate.url);
        
        // Show content state
        showContentState();
        
        // Track click on affiliate image/link and close overlay
        $('#affiliate-popup-link').off('click').on('click', function(e) {
            e.preventDefault(); // Prevent default to handle closing first
            
            if (hotNewsAffiliateModal.debug) {
                console.log('🖱️ Affiliate image clicked:', affiliate.id);
            }
            
            // Close overlay first
            closeModal();
        });
        
        // Add entrance animation
        setTimeout(function() {
            $('.affiliate-content').addClass('animate-in');
        }, 100);
    }
    
    /**
     * Close overlay
     */
    function closeModal() {
        if (hotNewsAffiliateModal.debug) {
            console.log('❌ Closing affiliate overlay...');
        }

        // Clear timeout if overlay is closed early
        if (modalTimeout) {
            clearTimeout(modalTimeout);
        }

        // Check if affiliate data exists
        if (!currentAffiliate || !currentAffiliate.url) {
            // If no affiliate data, just close the modal
            $('.site-main, .site-header, .site-footer').removeClass('content-blurred');
            $('#affiliateOverlay').fadeOut(300);
            return;
        }

        // Track click
        trackAffiliateClick(currentAffiliate.id);

        // Redirect to ad link first, then hide modal
        if (hotNewsAffiliateModal.debug) {
            console.log('Redirecting to:', currentAffiliate.url);
        }
        
        if (window.screen.width > 768) {
            // Desktop: Open in new tab first, then hide modal
            window.open(currentAffiliate.url, '_blank');
            
            // Remove blur effect
            $('.site-main, .site-header, .site-footer').removeClass('content-blurred');
            
            // Hide overlay with fade out effect
            $('#affiliateOverlay').fadeOut(300);
        } else {
            // Mobile: Navigate directly (page will change, so modal auto-hides)
            window.location.href = currentAffiliate.url;
        }
    }
    
    /**
     * Track affiliate click (optional)
     */
    function trackAffiliateClick(affiliateId) {
        if (!affiliateId) return;

        $.ajax({
            url: hotNewsAffiliateModal.ajax_url,
            type: 'POST',
            data: {
                action: 'hot_news_track_affiliate_click',
                affiliate_id: affiliateId,
                nonce: hotNewsAffiliateModal.nonce
            },
            success: function(response) {
                if (hotNewsAffiliateModal.debug) {
                    console.log('📊 Click tracked:', response);
                }
            },
            error: function() {
                if (hotNewsAffiliateModal.debug) {
                    console.log('⚠️ Click tracking failed');
                }
            }
        });
    }

    // Event Handlers
    
    // Close overlay on click outside or close button
    $(document).on('click', '#affiliateOverlay .affiliate-close-btn, #affiliateOverlay .affiliate-backdrop', function() {
        closeModal();
    });

    // Prevent closing when clicking on the content area
    $(document).on('click', '#affiliateOverlay .affiliate-popup-content', function(e) {
        e.stopPropagation();
    });

    // ESC key to close overlay
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && modalShown) {
            closeModal();
        }
    });
    
    // Track user engagement
    $(document).on('scroll click touchstart', function() {
        userEngaged = true;
    });
    
    // Track clicks on content
    $(document).on('click', 'a, button', function() {
        userEngaged = true;
    });
    
    // Exit intent detection
    $(document).on('mouseleave', function(e) {
        if (e.clientY < 0 && !exitIntentTriggered && !modalShown && !isDeviceStorageValid()) {
            exitIntentTriggered = true;
            // Show overlay immediately on exit intent
            if (modalTimeout) {
                clearTimeout(modalTimeout);
            }
            showAffiliateModal();
        }
    });

    // Performance optimization: Preload images
    function preloadImage(src) {
        const img = new Image();
        img.src = src;
    }
    
    // Optional: Preload a random affiliate image for better UX
    if (hotNewsAffiliateModal.show_modal) {
        setTimeout(function() {
            $.ajax({
                url: hotNewsAffiliateModal.ajax_url,
                type: 'POST',
                data: {
                    action: 'hot_news_get_random_affiliate',
                    nonce: hotNewsAffiliateModal.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        preloadImage(response.data.image_url);
                    }
                }
            });
        }, 1000);
    }
    
    // Debug info
    if (hotNewsAffiliateModal.debug) {
        const storedData = localStorage.getItem(STORAGE_KEY);
        let storageInfo = 'Not set';
        
        if (storedData) {
            try {
                const data = JSON.parse(storedData);
                const remainingTime = STORAGE_DURATION - (new Date().getTime() - data.timestamp);
                const remainingDays = Math.ceil(remainingTime / (24 * 60 * 60 * 1000));
                storageInfo = `Valid for ${remainingDays} more days`;
            } catch (e) {
                storageInfo = 'Invalid';
            }
        }
        
        console.log('🔧 Debug Info:', {
            show_modal: hotNewsAffiliateModal.show_modal,
            delay: hotNewsAffiliateModal.delay,
            active_count: hotNewsAffiliateModal.active_count,
            device_storage: storageInfo,
            current_time: new Date().toLocaleTimeString()
        });
    }
});