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
            const $blurredItem = $wrapper.find('.sensitive-content-blur');
            
            // Reveal the content
            revealContent($overlay, $blurredItem, $wrapper);
        });

        // Apply blur to content images and videos on page load
        if (typeof hotNewsSensitive !== 'undefined' && hotNewsSensitive.isSensitive) {
            wrapContentMedia();
        }

        // Check session storage for previously revealed items
        restoreRevealedState();
    }

    /**
     * Wrap images and videos in content with sensitive wrapper
     */
    function wrapContentMedia() {
        const $content = $('.entry-content, .post-content');
        
        if ($content.length === 0) {
            return;
        }

        // Wrap images
        $content.find('img').each(function() {
            const $img = $(this);
            
            // Skip if already wrapped or is within a link
            if ($img.parent().hasClass('sensitive-image-wrapper') || 
                $img.parent().is('a')) {
                if ($img.parent().is('a')) {
                    const $link = $img.parent();
                    if (!$link.parent().hasClass('sensitive-image-wrapper')) {
                        wrapElement($link);
                    }
                }
                return;
            }
            
            wrapElement($img);
        });

        // Wrap videos
        $content.find('video').each(function() {
            const $video = $(this);
            
            if ($video.parent().hasClass('sensitive-image-wrapper')) {
                return;
            }
            
            wrapElement($video);
        });

        // Wrap iframes (embedded videos)
        $content.find('iframe').each(function() {
            const $iframe = $(this);
            
            // Check if it's a video iframe (YouTube, Vimeo, etc.)
            const src = $iframe.attr('src') || '';
            if (src.includes('youtube.com') || 
                src.includes('youtu.be') || 
                src.includes('vimeo.com') ||
                src.includes('dailymotion.com')) {
                
                if ($iframe.parent().hasClass('sensitive-image-wrapper')) {
                    return;
                }
                
                wrapElement($iframe);
            }
        });
    }

    /**
     * Wrap an element with sensitive content wrapper
     */
    function wrapElement($element) {
        const itemId = 'sensitive-' + Math.random().toString(36).substr(2, 9);
        
        const $wrapper = $('<div>', {
            'class': 'sensitive-image-wrapper',
            'data-item-id': itemId
        });

        // Add blur class to element
        $element.addClass('sensitive-content-blur');

        // Wrap the element
        $element.wrap($wrapper);

        // Add overlay
        const overlayHtml = `
            <div class="sensitive-content-overlay">
                <div class="sensitive-content-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${hotNewsSensitive.warningText || 'Nội dung nhạy cảm'}</p>
                    <button class="btn btn-light btn-sm sensitive-view-btn">
                        <i class="fas fa-eye"></i> ${hotNewsSensitive.viewButtonText || 'Nhấn để xem'}
                    </button>
                </div>
            </div>
        `;

        $element.parent().append(overlayHtml);
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

    /**
     * Handle featured image blur on archive pages
     */
    function initArchiveSensitiveImages() {
        // Add data attribute to identify sensitive posts in lists
        $('.news-feed-item, .news-item-card, .card, .related-post-item, .tab-news-item, .popular-post-item').each(function() {
            const $item = $(this);
            const $sensitiveImg = $item.find('img.sensitive-content-blur, .sensitive-image-wrapper');
            
            if ($sensitiveImg.length > 0) {
                $item.attr('data-has-sensitive', 'true');
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initSensitiveContent();
        initArchiveSensitiveImages();
    });

    // Re-initialize after AJAX loads (for infinite scroll)
    $(document).on('hot_news_posts_loaded', function() {
        initArchiveSensitiveImages();
    });

})(jQuery);
