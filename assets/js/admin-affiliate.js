/**
 * Admin JavaScript cho Affiliate Manager
 */

jQuery(document).ready(function($) {
    
    let mediaUploader;
    let currentEditId = null;
    
    // Show message
    function showMessage(message, type = 'success') {
        const $message = $('<div class="affiliate-message ' + type + '">' + message + '</div>');
        $('.hot-news-affiliate-admin h1').after($message);
        $message.addClass('show');
        
        setTimeout(() => {
            $message.fadeOut(() => $message.remove());
        }, 4000);
    }
    
    // Show loading
    function showLoading() {
        $('#affiliate-loading').show();
    }
    
    // Hide loading
    function hideLoading() {
        $('#affiliate-loading').hide();
    }
    
    // Reset form
    function resetForm() {
        $('#affiliate-form')[0].reset();
        $('#affiliate-id').val('');
        $('#affiliate-image').val('');
        $('#image-preview').html('<div class="upload-placeholder"><div class="upload-icon">📷</div><p>Click để chọn hình ảnh</p></div>');
        $('#remove-image-btn').hide();
        currentEditId = null;
    }
    
    // Open modal
    function openModal(title = 'Thêm Affiliate Link') {
        $('#modal-title').text(title);
        $('#affiliate-modal').fadeIn(300);
        $('body').addClass('modal-open');
    }
    
    // Close modal
    function closeModal() {
        $('#affiliate-modal').fadeOut(300);
        $('body').removeClass('modal-open');
        resetForm();
    }
    
    // Validate form
    function validateForm() {
        const title = $('#affiliate-title').val().trim();
        const url = $('#affiliate-url').val().trim();
        const imageUrl = $('#affiliate-image').val().trim();
        
        if (!title) {
            showMessage(hotNewsAffiliate.strings.title_required, 'error');
            $('#affiliate-title').focus();
            return false;
        }
        
        if (!url) {
            showMessage(hotNewsAffiliate.strings.url_required, 'error');
            $('#affiliate-url').focus();
            return false;
        }
        
        if (!imageUrl) {
            showMessage(hotNewsAffiliate.strings.image_required, 'error');
            return false;
        }
        
        // Simple URL validation
        const urlPattern = /^https?:\/\/.+\..+/;
        if (!urlPattern.test(url)) {
            showMessage(hotNewsAffiliate.strings.invalid_url, 'error');
            $('#affiliate-url').focus();
            return false;
        }
        
        return true;
    }
    
    // Add new affiliate button
    $(document).on('click', '.add-new-affiliate', function(e) {
        e.preventDefault();
        resetForm();
        openModal('Thêm Affiliate Link');
    });
    
    // Close modal buttons
    $(document).on('click', '.affiliate-modal-close, #cancel-affiliate', function() {
        closeModal();
    });
    
    // Close modal on backdrop click
    $(document).on('click', '#affiliate-modal', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Image upload
    $(document).on('click', '#upload-image-btn, .image-preview', function(e) {
        e.preventDefault();
        
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        mediaUploader = wp.media({
            title: 'Chọn hình ảnh cho Affiliate',
            button: {
                text: 'Chọn hình ảnh'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#affiliate-image').val(attachment.url);
            $('#image-preview').html('<img src="' + attachment.url + '" alt="Preview">');
            $('#remove-image-btn').show();
        });
        
        mediaUploader.open();
    });
    
    // Remove image
    $(document).on('click', '#remove-image-btn', function(e) {
        e.preventDefault();
        $('#affiliate-image').val('');
        $('#image-preview').html('<div class="upload-placeholder"><div class="upload-icon">📷</div><p>Click để chọn hình ảnh</p></div>');
        $(this).hide();
    });
    
    // Save affiliate
    $(document).on('click', '#save-affiliate', function() {
        if (!validateForm()) {
            return;
        }
        
        const $button = $(this);
        const originalText = $button.text();
        $button.text(hotNewsAffiliate.strings.saving).prop('disabled', true);
        
        const formData = {
            action: currentEditId ? 'hot_news_update_affiliate' : 'hot_news_add_affiliate',
            nonce: hotNewsAffiliate.nonce,
            title: $('#affiliate-title').val().trim(),
            url: $('#affiliate-url').val().trim(),
            image_url: $('#affiliate-image').val().trim(),
            is_active: $('#affiliate-active').is(':checked') ? 1 : 0
        };
        
        if (currentEditId) {
            formData.id = currentEditId;
        }
        
        $.ajax({
            url: hotNewsAffiliate.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    if (currentEditId) {
                        // Update existing row
                        $('tr[data-id="' + currentEditId + '"]').replaceWith(response.data.row_html);
                        showMessage('Affiliate đã được cập nhật thành công!');
                    } else {
                        // Add new row
                        if ($('.no-affiliates').length) {
                            $('.no-affiliates').remove();
                        }
                        $('#affiliates-list').prepend(response.data.row_html);
                        showMessage('Affiliate đã được thêm thành công!');
                    }
                    closeModal();
                    updateStats();
                } else {
                    showMessage(response.data || hotNewsAffiliate.strings.error, 'error');
                }
            },
            error: function() {
                showMessage(hotNewsAffiliate.strings.error, 'error');
            },
            complete: function() {
                $button.text(originalText).prop('disabled', false);
            }
        });
    });
    
    // Edit affiliate
    $(document).on('click', '.edit-affiliate', function() {
        const id = $(this).data('id');
        const $row = $('tr[data-id="' + id + '"]');
        
        // Extract data from row
        const title = $row.find('td:nth-child(3) strong').text();
        const url = $row.find('td:nth-child(4) a').attr('href');
        const imageUrl = $row.find('.affiliate-image-thumb img').attr('src');
        const isActive = $row.find('.toggle-status').is(':checked');
        
        // Populate form
        $('#affiliate-id').val(id);
        $('#affiliate-title').val(title);
        $('#affiliate-url').val(url);
        $('#affiliate-image').val(imageUrl);
        $('#affiliate-active').prop('checked', isActive);
        $('#image-preview').html('<img src="' + imageUrl + '" alt="Preview">');
        $('#remove-image-btn').show();
        
        currentEditId = id;
        openModal('Chỉnh sửa Affiliate Link');
    });
    
    // Delete affiliate
    $(document).on('click', '.delete-affiliate', function() {
        if (!confirm(hotNewsAffiliate.strings.confirm_delete)) {
            return;
        }
        
        const id = $(this).data('id');
        const $row = $('tr[data-id="' + id + '"]');
        
        showLoading();
        
        $.ajax({
            url: hotNewsAffiliate.ajax_url,
            type: 'POST',
            data: {
                action: 'hot_news_delete_affiliate',
                nonce: hotNewsAffiliate.nonce,
                id: id
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if no affiliates left
                        if ($('#affiliates-list tr').length === 0) {
                            $('#affiliates-list').html(
                                '<tr class="no-affiliates">' +
                                '<td colspan="7" style="text-align: center; padding: 40px;">' +
                                '<div class="empty-state">' +
                                '<div class="empty-icon">🔗</div>' +
                                '<h3>Chưa có affiliate link nào</h3>' +
                                '<p>Nhấn "Thêm mới" để tạo affiliate link đầu tiên của bạn</p>' +
                                '<button type="button" class="button button-primary add-new-affiliate">Thêm affiliate link</button>' +
                                '</div>' +
                                '</td>' +
                                '</tr>'
                            );
                        }
                        
                        updateStats();
                    });
                    showMessage(response.data);
                } else {
                    showMessage(response.data || hotNewsAffiliate.strings.error, 'error');
                }
            },
            error: function() {
                showMessage(hotNewsAffiliate.strings.error, 'error');
            },
            complete: function() {
                hideLoading();
            }
        });
    });
    
    // Toggle affiliate status
    $(document).on('change', '.toggle-status', function() {
        const id = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0;
        const $toggle = $(this);
        
        $.ajax({
            url: hotNewsAffiliate.ajax_url,
            type: 'POST',
            data: {
                action: 'hot_news_toggle_affiliate',
                nonce: hotNewsAffiliate.nonce,
                id: id,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    showMessage(response.data);
                    updateStats();
                } else {
                    // Revert toggle
                    $toggle.prop('checked', !status);
                    showMessage(response.data || hotNewsAffiliate.strings.error, 'error');
                }
            },
            error: function() {
                // Revert toggle
                $toggle.prop('checked', !status);
                showMessage(hotNewsAffiliate.strings.error, 'error');
            }
        });
    });
    
    // Update stats
    function updateStats() {
        const totalLinks = $('#affiliates-list tr:not(.no-affiliates)').length;
        const activeLinks = $('#affiliates-list .toggle-status:checked').length;
        let totalClicks = 0;
        
        $('#affiliates-list .click-count').each(function() {
            totalClicks += parseInt($(this).text().replace(/,/g, '')) || 0;
        });
        
        $('.stat-card:nth-child(1) .stat-number').text(totalLinks);
        $('.stat-card:nth-child(2) .stat-number').text(activeLinks);
        $('.stat-card:nth-child(3) .stat-number').text(totalClicks.toLocaleString());
    }
    
    // Prevent form submission on Enter
    $('#affiliate-form').on('submit', function(e) {
        e.preventDefault();
        $('#save-affiliate').click();
    });
    
    // ESC key to close modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#affiliate-modal').is(':visible')) {
            closeModal();
        }
    });
    
    // Auto-resize modal on window resize
    $(window).on('resize', function() {
        if ($('#affiliate-modal').is(':visible')) {
            const windowHeight = $(window).height();
            $('.affiliate-modal-body').css('max-height', (windowHeight * 0.9 - 140) + 'px');
        }
    });
    
    // Sortable table (if needed in future)
    // $('#affiliates-list').sortable({
    //     handle: '.sort-handle',
    //     helper: 'clone',
    //     update: function(event, ui) {
    //         // Update order via AJAX
    //     }
    // });
    
    // Real-time search/filter (future enhancement)
    $('#affiliate-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#affiliates-list tr:not(.no-affiliates)').each(function() {
            const $row = $(this);
            const title = $row.find('td:nth-child(3)').text().toLowerCase();
            const url = $row.find('td:nth-child(4)').text().toLowerCase();
            
            if (title.includes(searchTerm) || url.includes(searchTerm)) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    });
});

// Add CSS for modal-open class
jQuery(document).ready(function($) {
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            body.modal-open {
                overflow: hidden;
            }
            
            .affiliate-modal-content {
                position: relative;
                z-index: 100001;
            }
            
            .affiliates-table tr[data-id] {
                transition: all 0.3s ease;
            }
            
            .affiliates-table tr[data-id]:hover {
                transform: translateX(2px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .form-group input:invalid {
                border-color: #dc3545;
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.3);
            }
            
            .form-group input:valid {
                border-color: #28a745;
            }
            
            .toggle-slider {
                position: relative;
            }
            
            .toggle-slider::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 6px;
                transform: translateY(-50%);
                font-size: 10px;
                color: white;
                font-weight: bold;
                transition: all 0.3s ease;
                opacity: 0;
            }
            
            .toggle-switch input:checked + .toggle-slider::after {
                content: '✓';
                left: 30px;
                opacity: 1;
            }
            
            .affiliate-image-thumb {
                position: relative;
                overflow: hidden;
            }
            
            .affiliate-image-thumb::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0);
                transition: background 0.3s ease;
                z-index: 1;
            }
            
            .affiliate-image-thumb:hover::before {
                background: rgba(0, 0, 0, 0.1);
            }
        `)
        .appendTo('head');
});
