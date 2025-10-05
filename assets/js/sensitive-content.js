/**
 * Sensitive Content Handler
 * 
 * Handles revealing blurred sensitive images and videos
 */

(function($) {
    'use strict';

    // Track revealed items to persist state during session
    const revealedItems = new Set();

    /**
     * Initialize sensitive content handlers
     */
    function initSensitiveContent() {
        // Handle click on view buttons
        $(document).on('click', '.sensitive-view-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $button = $(this);
            const $overlay = $button.closest('.sensitive-content-overlay');
            const $wrapper = $overlay.parent();
            const $blurredItem = $wrapper.find('.sensitive-image-blur, .sensitive-video-blur');
            
            // Reveal the content
            revealContent($overlay, $blurredItem, $wrapper);
        });

        // Find and wrap all sensitive media (images and videos)
        wrapSensitiveMedia();

        // Check session storage for previously revealed items
        restoreRevealedState();
    }

    /**
     * Find and wrap all media (images and videos) marked as sensitive
     */
    function wrapSensitiveMedia() {
        // Debug: Log all media found
        const $allImages = $('img');
        const $allVideos = $('video');
        console.log('🔍 Total images found:', $allImages.length);
        console.log('🔍 Total videos found:', $allVideos.length);
        
        // Find all sensitive images
        const $sensitiveImages = $('.sensitive-image-blur, img[data-sensitive="true"]');
        console.log('🔍 Sensitive images found:', $sensitiveImages.length);
        
        $sensitiveImages.each(function(index) {
            const $img = $(this);
            console.log('📸 Processing sensitive image #' + (index + 1), $img.attr('src'));
            
            // Skip if already wrapped
            if ($img.parent().hasClass('sensitive-media-wrapper') || $img.parent().hasClass('sensitive-image-wrapper')) {
                console.log('⏭️ Already wrapped, skipping');
                return;
            }
            
            // If image is inside a link, wrap the link instead
            if ($img.parent().is('a')) {
                const $link = $img.parent();
                if (!$link.parent().hasClass('sensitive-media-wrapper')) {
                    console.log('🔗 Wrapping link instead of image');
                    wrapElement($link, $img);
                }
            } else {
                console.log('🖼️ Wrapping image directly');
                wrapElement($img, $img);
            }
        });

        // Find all sensitive videos
        const $sensitiveVideos = $('.sensitive-video-blur, video[data-sensitive="true"]');
        console.log('🔍 Sensitive videos found:', $sensitiveVideos.length);
        
        $sensitiveVideos.each(function(index) {
            const $video = $(this);
            console.log('🎬 Processing sensitive video #' + (index + 1), $video.attr('src'));
            
            // Skip if already wrapped
            if ($video.parent().hasClass('sensitive-media-wrapper') || $video.parent().hasClass('sensitive-video-wrapper')) {
                console.log('⏭️ Already wrapped, skipping');
                return;
            }
            
            // Pause video if it's playing
            if ($video[0] && !$video[0].paused) {
                $video[0].pause();
            }
            
            console.log('🎥 Wrapping video directly');
            wrapElement($video, $video);
        });

        // Also check for featured media if it has sensitive class
        const $featuredSensitive = $('.post-featured-image img.sensitive-image-blur, .post-featured-image video.sensitive-video-blur');
        console.log('🎯 Featured sensitive media:', $featuredSensitive.length);
        
        $featuredSensitive.each(function() {
            const $media = $(this);
            const $container = $media.closest('.post-featured-image');
            
            if ($container.length && !$container.find('.sensitive-content-overlay').length) {
                console.log('✨ Adding overlay to featured media');
                const itemId = 'sensitive-featured-' + Math.random().toString(36).substr(2, 9);
                $container.attr('data-item-id', itemId);
                
                const overlayHtml = createOverlayHtml();
                $container.css('position', 'relative').append(overlayHtml);
                
                // Pause video if featured media is video
                if ($media.is('video') && $media[0] && !$media[0].paused) {
                    $media[0].pause();
                }
            }
        });
        
        console.log('✅ Sensitive media processing complete');
    }

    /**
     * Create overlay HTML
     */
    function createOverlayHtml() {
        return `
            <div class="sensitive-content-overlay">
                <div class="sensitive-content-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${hotNewsSensitive.warningText || 'Hình ảnh nhạy cảm'}</p>
                    <button class="btn btn-light btn-sm sensitive-view-btn">
                        <i class="fas fa-eye"></i> ${hotNewsSensitive.viewButtonText || 'Nhấn để xem'}
                    </button>
                </div>
            </div>
        `;
    }

    /**
     * Wrap an element with sensitive content wrapper
     */
    function wrapElement($elementToWrap, $mediaElement) {
        const itemId = 'sensitive-' + Math.random().toString(36).substr(2, 9);
        const attachmentId = $mediaElement.data('attachment-id') || '';
        const isVideo = $mediaElement.is('video');
        const wrapperClass = isVideo ? 'sensitive-video-wrapper' : 'sensitive-image-wrapper';
        
        const $wrapper = $('<div>', {
            'class': wrapperClass + ' sensitive-media-wrapper',
            'data-item-id': itemId,
            'data-attachment-id': attachmentId
        });

        // Wrap the element
        $elementToWrap.wrap($wrapper);

        // Add overlay
        $elementToWrap.parent().append(createOverlayHtml());
    }

    /**
     * Reveal content
     */
    function revealContent($overlay, $blurredItem, $wrapper) {
        // Animate overlay fade out
        $overlay.addClass('hidden');
        
        // Remove blur with animation
        $blurredItem.addClass('revealing');
        
        setTimeout(function() {
            $blurredItem.removeClass('sensitive-content-blur revealing').addClass('revealed');
            $overlay.remove();
        }, 300);

        // Store in session
        const itemId = $wrapper.data('item-id') || 'default';
        revealedItems.add(itemId);
        saveRevealedState();
    }

    /**
     * Save revealed state to session storage
     */
    function saveRevealedState() {
        try {
            const currentPost = getCurrentPostId();
            const storageKey = 'hot_news_revealed_' + currentPost;
            sessionStorage.setItem(storageKey, JSON.stringify([...revealedItems]));
        } catch (e) {
            console.log('Session storage not available');
        }
    }

    /**
     * Restore revealed state from session storage
     */
    function restoreRevealedState() {
        try {
            const currentPost = getCurrentPostId();
            const storageKey = 'hot_news_revealed_' + currentPost;
            const stored = sessionStorage.getItem(storageKey);
            
            if (stored) {
                const revealed = JSON.parse(stored);
                revealed.forEach(function(itemId) {
                    const $wrapper = $('[data-item-id="' + itemId + '"]');
                    if ($wrapper.length) {
                        const $overlay = $wrapper.find('.sensitive-content-overlay');
                        const $blurredItem = $wrapper.find('.sensitive-content-blur');
                        
                        if ($overlay.length && $blurredItem.length) {
                            $overlay.remove();
                            $blurredItem.removeClass('sensitive-content-blur').addClass('revealed');
                        }
                    }
                    revealedItems.add(itemId);
                });
            }
        } catch (e) {
            console.log('Session storage not available');
        }
    }

    /**
     * Get current post ID from body class
     */
    function getCurrentPostId() {
        const bodyClasses = $('body').attr('class') || '';
        const match = bodyClasses.match(/postid-(\d+)/);
        return match ? match[1] : '0';
    }

    // Initialize on document ready
    $(document).ready(function() {
        // Only run on single post pages
        if ($('body').hasClass('single') || $('body').hasClass('single-post')) {
            initSensitiveContent();
        }
    });

})(jQuery);
