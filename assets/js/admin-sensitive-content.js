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
        // Hook into the attachment details view
        var AttachmentDetailsOriginal = wp.media.view.Attachment.Details.TwoColumn;
        
        wp.media.view.Attachment.Details.TwoColumn = AttachmentDetailsOriginal.extend({
            template: function(view) {
                var html = AttachmentDetailsOriginal.prototype.template.call(this, view);
                
                // Only add checkbox for images
                if (view.model && view.model.get('type') === 'image') {
                    var attachmentId = view.model.get('id');
                    var blurValue = view.model.get('blur_sensitive_image') || '';
                    var isChecked = blurValue === '1' ? 'checked' : '';
                    
                    var checkboxHtml = `
                        <label class="setting blur-sensitive-image-setting" style="padding: 12px 0; border-top: 1px solid #ddd; margin-top: 10px;">
                            <span class="name" style="min-width: 30%; max-width: 30%;">⚠️ Làm mờ hình ảnh</span>
                            <input type="checkbox" 
                                   class="blur-sensitive-checkbox" 
                                   data-attachment-id="${attachmentId}"
                                   value="1" 
                                   ${isChecked} />
                            <span style="display: block; font-size: 12px; color: #666; margin-top: 5px; max-width: 65%;">
                                Đánh dấu hình ảnh này là nhạy cảm (sẽ bị làm mờ khi hiển thị)
                            </span>
                        </label>
                    `;
                    
                    // Inject checkbox into the settings area
                    html = html.replace('</div>', checkboxHtml + '</div>');
                }
                
                return html;
            },
            
            render: function() {
                AttachmentDetailsOriginal.prototype.render.apply(this, arguments);
                
                // Attach change event handler
                var self = this;
                this.$el.find('.blur-sensitive-checkbox').on('change', function() {
                    var attachmentId = $(this).data('attachment-id');
                    var isChecked = $(this).is(':checked') ? '1' : '0';
                    
                    // Update model
                    self.model.set('blur_sensitive_image', isChecked);
                    
                    // Save to database via AJAX
                    saveBlurSetting(attachmentId, isChecked);
                });
                
                return this;
            }
        });
    }

    /**
     * Save blur setting via AJAX
     */
    function saveBlurSetting(attachmentId, value) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'save_blur_sensitive_image',
                attachment_id: attachmentId,
                blur_value: value,
                nonce: hotNewsSensitiveAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    console.log('Blur setting saved for attachment #' + attachmentId);
                    
                    // Show temporary success message
                    showSuccessNotice('Cài đặt làm mờ đã được lưu');
                } else {
                    console.error('Failed to save blur setting:', response.data);
                    showErrorNotice('Lỗi khi lưu cài đặt');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                showErrorNotice('Lỗi kết nối');
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
