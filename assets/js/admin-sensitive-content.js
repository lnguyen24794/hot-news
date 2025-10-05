/**
 * Admin Media Modal - Add Sensitive Content Checkbox
 * 
 * Adds "Blur Image" checkbox to media modal in post editor
 */

(function($) {
    'use strict';

    // Wait for WordPress media to be ready
    $(document).ready(function() {
        console.log('🚀 Admin Sensitive Content Script Loaded');
        
        if (typeof wp !== 'undefined' && wp.media && wp.media.view) {
            initMediaModalCheckbox();
        }
        
        // For Gutenberg/Block Editor - add class when image block is inserted
        if (typeof wp !== 'undefined' && wp.data && wp.blocks) {
            initBlockEditorSupport();
        }
    });

    /**
     * Initialize checkbox in media modal
     */
    function initMediaModalCheckbox() {
        console.log('🎬 Initializing media modal checkbox');
        
        // Hook into the attachment details view
        var AttachmentDetailsOriginal = wp.media.view.Attachment.Details.TwoColumn;
        
        wp.media.view.Attachment.Details.TwoColumn = AttachmentDetailsOriginal.extend({
            render: function() {
                console.log('🖼️ Rendering attachment details');
                
                // Call original render
                AttachmentDetailsOriginal.prototype.render.apply(this, arguments);
                
                var model = this.model;
                if (!model) {
                    return this;
                }
                
                var mediaType = model.get('type');
                var isImage = mediaType === 'image';
                var isVideo = mediaType === 'video';
                
                // Only show for images and videos
                if (!isImage && !isVideo) {
                    console.log('⏭️ Not an image or video, skipping');
                    return this;
                }
                
                var attachmentId = model.get('id');
                var blurMeta = isImage ? 'blur_sensitive_image' : 'blur_sensitive_video';
                var blurValue = model.get(blurMeta) || '';
                var isChecked = blurValue === '1';
                var label = isImage ? '⚠️ Làm mờ hình ảnh' : '⚠️ Làm mờ video';
                var description = isImage ? 
                    'Đánh dấu hình ảnh này là nhạy cảm (sẽ bị làm mờ khi hiển thị)' : 
                    'Đánh dấu video này là nhạy cảm (sẽ bị làm mờ khi hiển thị)';
                
                console.log(`📸 ${mediaType} attachment:`, {
                    id: attachmentId,
                    blurValue: blurValue,
                    isChecked: isChecked
                });
                
                // Find the settings container
                var $settings = this.$el.find('.settings');
                if ($settings.length === 0) {
                    $settings = this.$el.find('.attachment-info');
                }
                
                // Remove old checkbox if exists
                $settings.find('.blur-sensitive-media-setting').remove();
                
                // Create checkbox HTML
                var checkboxHtml = `
                    <label class="setting blur-sensitive-media-setting" style="padding: 12px 0; border-top: 1px solid #ddd; margin-top: 10px; display: block;">
                        <span class="name" style="display: inline-block; min-width: 30%; vertical-align: top; font-weight: 600;">${label}</span>
                        <span style="display: inline-block; width: 65%;">
                            <input type="checkbox" 
                                   class="blur-sensitive-checkbox" 
                                   data-attachment-id="${attachmentId}"
                                   data-media-type="${mediaType}"
                                   value="1" 
                                   ${isChecked ? 'checked' : ''} />
                            <span style="display: block; font-size: 12px; color: #666; margin-top: 5px;">
                                ${description}
                            </span>
                        </span>
                    </label>
                `;
                
                // Append checkbox
                $settings.append(checkboxHtml);
                console.log('✅ Checkbox added to modal');
                
                // Attach change event handler
                var self = this;
                this.$el.find('.blur-sensitive-checkbox').on('change', function() {
                    var $checkbox = $(this);
                    var attachmentId = $checkbox.data('attachment-id');
                    var mediaType = $checkbox.data('media-type');
                    var isChecked = $checkbox.is(':checked') ? '1' : '0';
                    
                    console.log('🔄 Checkbox changed:', {
                        attachmentId: attachmentId,
                        mediaType: mediaType,
                        isChecked: isChecked
                    });
                    
                    // Update model
                    var blurMeta = mediaType === 'image' ? 'blur_sensitive_image' : 'blur_sensitive_video';
                    self.model.set(blurMeta, isChecked);
                    
                    // Save to database via AJAX
                    saveBlurSetting(attachmentId, isChecked, mediaType);
                });
                
                return this;
            }
        });
        
        console.log('✅ Media modal checkbox initialized');
    }

    /**
     * Save blur setting via AJAX
     */
    function saveBlurSetting(attachmentId, value, mediaType) {
        mediaType = mediaType || 'image';
        var action = mediaType === 'video' ? 'save_blur_sensitive_video' : 'save_blur_sensitive_image';
        
        console.log('💾 Saving blur setting:', {
            attachmentId: attachmentId,
            value: value,
            mediaType: mediaType,
            action: action,
            nonce: hotNewsSensitiveAdmin.nonce
        });
        
        $.ajax({
            url: hotNewsSensitiveAdmin.ajaxurl || ajaxurl,
            type: 'POST',
            data: {
                action: action,
                attachment_id: attachmentId,
                blur_value: value,
                media_type: mediaType,
                nonce: hotNewsSensitiveAdmin.nonce
            },
            success: function(response) {
                console.log('✅ AJAX Response:', response);
                
                if (response.success) {
                    console.log('✅ Blur setting saved for attachment #' + attachmentId);
                    console.log('📊 Saved value:', response.data.saved_value);
                    
                    // Show temporary success message
                    showSuccessNotice('Đã lưu cài đặt làm mờ');
                } else {
                    console.error('❌ Failed to save:', response.data);
                    showErrorNotice('Lỗi khi lưu cài đặt');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                showErrorNotice('Lỗi kết nối: ' + error);
            }
        });
    }

    /**
     * Show success notice
     */
    function showSuccessNotice(message) {
        showNotice(message, 'success');
    }

    /**
     * Show error notice
     */
    function showErrorNotice(message) {
        showNotice(message, 'error');
    }

    /**
     * Show temporary notice
     */
    function showNotice(message, type) {
        var $notice = $('<div>', {
            'class': 'notice notice-' + type + ' is-dismissible sensitive-notice',
            'css': {
                'position': 'fixed',
                'top': '32px',
                'right': '20px',
                'z-index': '999999',
                'padding': '12px',
                'background': type === 'success' ? '#00a32a' : '#d63638',
                'color': '#fff',
                'border-radius': '4px',
                'box-shadow': '0 2px 8px rgba(0,0,0,0.2)'
            },
            'html': '<p style="margin: 0; color: #fff;">' + message + '</p>'
        });

        $('body').append($notice);

        // Auto remove after 3 seconds
        setTimeout(function() {
            $notice.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    /**
     * Add Gutenberg/Block Editor support
     */
    function initBlockEditorSupport() {
        console.log('🎨 Initializing Block Editor support');
        
        // Subscribe to block editor changes
        var previousBlocks = [];
        
        wp.data.subscribe(function() {
            var blocks = wp.data.select('core/block-editor').getBlocks();
            
            blocks.forEach(function(block) {
                // Handle both image and video blocks
                var isImageBlock = block.name === 'core/image';
                var isVideoBlock = block.name === 'core/video';
                
                if ((isImageBlock || isVideoBlock) && block.attributes.id) {
                    var attachmentId = block.attributes.id;
                    var className = block.attributes.className || '';
                    var blockKey = block.clientId + '_' + attachmentId;
                    
                    // Skip if already processed
                    if (previousBlocks.indexOf(blockKey) !== -1) {
                        return;
                    }
                    
                    // Check if this media is marked as sensitive
                    var attachment = wp.media.attachment(attachmentId);
                    if (attachment) {
                        attachment.fetch().done(function() {
                            var blurMeta = isImageBlock ? 'blur_sensitive_image' : 'blur_sensitive_video';
                            var blurClass = isImageBlock ? 'sensitive-image-blur' : 'sensitive-video-blur';
                            var blurValue = attachment.get(blurMeta);
                            
                            if (blurValue === '1') {
                                // Add sensitive class if not already there
                                if (className.indexOf(blurClass) === -1) {
                                    var mediaType = isImageBlock ? 'image' : 'video';
                                    console.log('📸 Adding sensitive class to block ' + mediaType + ' #' + attachmentId);
                                    className = (className + ' ' + blurClass).trim();
                                    
                                    // Update block attributes
                                    wp.data.dispatch('core/block-editor').updateBlockAttributes(
                                        block.clientId,
                                        {
                                            className: className
                                        }
                                    );
                                    
                                    previousBlocks.push(blockKey);
                                }
                            }
                        });
                    }
                }
            });
        });
        
        console.log('✅ Block Editor support initialized');
    }

})(jQuery);
