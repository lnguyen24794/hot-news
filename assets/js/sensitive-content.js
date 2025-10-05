/**
 * Sensitive Content Handler
 * 
 * Handles revealing blurred sensitive images (per-image basis)
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
            const $blurredItem = $wrapper.find('.sensitive-image-blur');
            
            // Reveal the content
            revealContent($overlay, $blurredItem, $wrapper);
        });

        // Find and wrap all images with sensitive-image-blur class
        wrapSensitiveImages();

        // Check session storage for previously revealed items
        restoreRevealedState();
    }

    /**
     * Find and wrap all images marked as sensitive
     */
    function wrapSensitiveImages() {
        // Find all images with sensitive-image-blur class
        $('.sensitive-image-blur, img[data-sensitive="true"]').each(function() {
            const $img = $(this);
            
            // Skip if already wrapped
            if ($img.parent().hasClass('sensitive-image-wrapper')) {
                return;
            }
            
            // If image is inside a link, wrap the link instead
            if ($img.parent().is('a')) {
                const $link = $img.parent();
                if (!$link.parent().hasClass('sensitive-image-wrapper')) {
                    wrapElement($link, $img);
                }
            } else {
                wrapElement($img, $img);
            }
        });

        // Also check for featured image if it has sensitive class
        $('.post-featured-image img.sensitive-image-blur').each(function() {
            const $img = $(this);
            const $container = $img.closest('.post-featured-image');
            
            if ($container.length && !$container.find('.sensitive-content-overlay').length) {
                const itemId = 'sensitive-featured-' + Math.random().toString(36).substr(2, 9);
                $container.attr('data-item-id', itemId);
                
                const overlayHtml = createOverlayHtml();
                $container.css('position', 'relative').append(overlayHtml);
            }
        });
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
    function wrapElement($elementToWrap, $imageElement) {
        const itemId = 'sensitive-' + Math.random().toString(36).substr(2, 9);
        const attachmentId = $imageElement.data('attachment-id') || '';
        
        const $wrapper = $('<div>', {
            'class': 'sensitive-image-wrapper',
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
