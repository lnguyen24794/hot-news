/**
 * Admin Media Modal - Add Sensitive Content Checkbox
 * 
 * Adds "Blur Image" checkbox to media modal in post editor
 */

(function($) {
    'use strict';

    // Wait for WordPress media to be ready
    $(document).ready(function() {
        if (typeof wp !== 'undefined' && wp.media && wp.media.view) {
            initMediaModalCheckbox();
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
                if (!model || model.get('type') !== 'image') {
                    console.log('⏭️ Not an image, skipping');
                    return this;
                }
                
                var attachmentId = model.get('id');
                var blurValue = model.get('blur_sensitive_image') || '';
                var isChecked = blurValue === '1';
                
                console.log('📸 Image attachment:', {
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
                $settings.find('.blur-sensitive-image-setting').remove();
                
                // Create checkbox HTML
                var checkboxHtml = `
                    <label class="setting blur-sensitive-image-setting" style="padding: 12px 0; border-top: 1px solid #ddd; margin-top: 10px; display: block;">
                        <span class="name" style="display: inline-block; min-width: 30%; vertical-align: top; font-weight: 600;">⚠️ Làm mờ hình ảnh</span>
                        <span style="display: inline-block; width: 65%;">
                            <input type="checkbox" 
                                   class="blur-sensitive-checkbox" 
                                   data-attachment-id="${attachmentId}"
                                   value="1" 
                                   ${isChecked ? 'checked' : ''} />
                            <span style="display: block; font-size: 12px; color: #666; margin-top: 5px;">
                                Đánh dấu hình ảnh này là nhạy cảm (sẽ bị làm mờ khi hiển thị)
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
                    var isChecked = $checkbox.is(':checked') ? '1' : '0';
                    
                    console.log('🔄 Checkbox changed:', {
                        attachmentId: attachmentId,
                        isChecked: isChecked
                    });
                    
                    // Update model
                    self.model.set('blur_sensitive_image', isChecked);
                    
                    // Save to database via AJAX
                    saveBlurSetting(attachmentId, isChecked);
                });
                
                return this;
            }
        });
        
        console.log('✅ Media modal checkbox initialized');
    }

    /**
     * Save blur setting via AJAX
     */
    function saveBlurSetting(attachmentId, value) {
        console.log('💾 Saving blur setting:', {
            attachmentId: attachmentId,
            value: value,
            nonce: hotNewsSensitiveAdmin.nonce
        });
        
        $.ajax({
            url: hotNewsSensitiveAdmin.ajaxurl || ajaxurl,
            type: 'POST',
            data: {
                action: 'save_blur_sensitive_image',
                attachment_id: attachmentId,
                blur_value: value,
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

})(jQuery);
