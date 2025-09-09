/**
 * Admin JavaScript cho Google Ads Manager
 */

jQuery(document).ready(function($) {
    
    // Tab switching functionality
    $('.ads-nav-tabs .nav-tab').on('click', function(e) {
        e.preventDefault();
        
        var targetTab = $(this).attr('href');
        
        // Remove active class from all tabs and panes
        $('.ads-nav-tabs .nav-tab').removeClass('nav-tab-active');
        $('.ads-tab-pane').removeClass('active');
        
        // Add active class to clicked tab and corresponding pane
        $(this).addClass('nav-tab-active');
        $(targetTab).addClass('active');
        
        // Save current tab to localStorage
        localStorage.setItem('hot_news_ads_active_tab', targetTab);
    });
    
    // Restore active tab from localStorage
    var savedTab = localStorage.getItem('hot_news_ads_active_tab');
    if (savedTab && $(savedTab).length) {
        $('.ads-nav-tabs .nav-tab').removeClass('nav-tab-active');
        $('.ads-tab-pane').removeClass('active');
        
        $('a[href="' + savedTab + '"]').addClass('nav-tab-active');
        $(savedTab).addClass('active');
    }
    
    // Auto Ads toggle functionality
    $('input[name="hot_news_google_ads_options[auto_ads_enabled]"]').on('change', function() {
        var isChecked = $(this).is(':checked');
        var $manualTabs = $('.ads-nav-tabs a[href="#homepage"], .ads-nav-tabs a[href="#single"], .ads-nav-tabs a[href="#archive"]');
        
        if (isChecked) {
            // Auto Ads enabled - disable manual tabs
            $manualTabs.addClass('disabled').attr('data-tooltip', 'Tắt Auto Ads để sử dụng Manual Ads');
            showAutoAdsNotice();
        } else {
            // Auto Ads disabled - enable manual tabs
            $manualTabs.removeClass('disabled').removeAttr('data-tooltip');
            hideAutoAdsNotice();
        }
    });
    
    // Prevent clicking on disabled tabs
    $('.ads-nav-tabs').on('click', '.nav-tab.disabled', function(e) {
        e.preventDefault();
        showTooltip($(this), $(this).attr('data-tooltip'));
    });
    
    // AdSense Client ID validation
    $('input[name="hot_news_google_ads_options[adsense_client_id]"]').on('blur', function() {
        var clientId = $(this).val().trim();
        var $feedback = $(this).siblings('.client-id-feedback');
        
        if ($feedback.length === 0) {
            $feedback = $('<div class="client-id-feedback"></div>');
            $(this).after($feedback);
        }
        
        if (clientId === '') {
            $feedback.removeClass('valid invalid').empty();
        } else if (validateAdSenseClientId(clientId)) {
            $feedback.removeClass('invalid').addClass('valid').html('<i class="dashicons dashicons-yes-alt"></i> Client ID hợp lệ');
        } else {
            $feedback.removeClass('valid').addClass('invalid').html('<i class="dashicons dashicons-warning"></i> Client ID không đúng định dạng (ví dụ: ca-pub-1234567890123456)');
        }
    });
    
    // Textarea auto-resize and code validation
    $('textarea').each(function() {
        autoResizeTextarea($(this));
        
        // Add placeholder cho các textarea AdSense code
        var fieldName = $(this).attr('name');
        if (fieldName && fieldName.includes('_ad_code')) {
            $(this).attr('placeholder', getAdSensePlaceholder());
        }
    });
    
    $('textarea').on('input', function() {
        autoResizeTextarea($(this));
        validateAdSenseCode($(this));
    });
    
    // Save form with AJAX
    $('#submit').on('click', function(e) {
        var $form = $(this).closest('form');
        var $button = $(this);
        
        // Add loading state
        $button.prop('disabled', true).val('Đang lưu...');
        
        // Show loading indicator
        if ($('.ads-saving-indicator').length === 0) {
            $form.prepend('<div class="ads-saving-indicator"><i class="dashicons dashicons-update"></i> Đang lưu cài đặt...</div>');
        }
    });
    
    // Initialize tooltips
    initTooltips();
    
    // Check initial state
    $('input[name="hot_news_google_ads_options[auto_ads_enabled]"]').trigger('change');
    
    /**
     * Helper Functions
     */
    
    function showAutoAdsNotice() {
        if ($('.auto-ads-notice').length === 0) {
            var notice = '<div class="auto-ads-notice ads-admin-notice success">' +
                        '<strong>Chế độ Auto Ads được kích hoạt!</strong> ' +
                        'Google sẽ tự động hiển thị quảng cáo trên website của bạn. ' +
                        'Các tab Manual Ads sẽ bị vô hiệu hóa.' +
                        '</div>';
            $('.tab-content').prepend(notice);
        }
    }
    
    function hideAutoAdsNotice() {
        $('.auto-ads-notice').remove();
    }
    
    function validateAdSenseClientId(clientId) {
        // Định dạng AdSense Client ID: ca-pub-xxxxxxxxxxxxxxxx (16 chữ số)
        var pattern = /^ca-pub-\d{16}$/;
        return pattern.test(clientId);
    }
    
    function validateAdSenseCode($textarea) {
        var code = $textarea.val().trim();
        var $feedback = $textarea.siblings('.code-feedback');
        
        if ($feedback.length === 0) {
            $feedback = $('<div class="code-feedback"></div>');
            $textarea.after($feedback);
        }
        
        if (code === '') {
            $feedback.removeClass('valid invalid warning').empty();
            return;
        }
        
        // Check for basic AdSense code patterns
        var hasGoogleAds = code.includes('googlesyndication.com') || code.includes('adsbygoogle');
        var hasScript = code.includes('<script') && code.includes('</script>');
        var hasIns = code.includes('<ins') && code.includes('</ins>');
        
        if (hasGoogleAds && (hasScript || hasIns)) {
            $feedback.removeClass('invalid warning').addClass('valid').html('<i class="dashicons dashicons-yes-alt"></i> Mã AdSense hợp lệ');
        } else if (code.length > 10) {
            $feedback.removeClass('valid invalid').addClass('warning').html('<i class="dashicons dashicons-info"></i> Hãy đảm bảo đây là mã AdSense chính xác');
        } else {
            $feedback.removeClass('valid warning').addClass('invalid').html('<i class="dashicons dashicons-warning"></i> Mã không hợp lệ');
        }
    }
    
    function autoResizeTextarea($textarea) {
        $textarea.css('height', 'auto');
        $textarea.css('height', ($textarea[0].scrollHeight + 5) + 'px');
    }
    
    function getAdSensePlaceholder() {
        return '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-xxxxxxxxxxxxxxxx" crossorigin="anonymous"></script>\n' +
               '<ins class="adsbygoogle"\n' +
               '     style="display:block"\n' +
               '     data-ad-client="ca-pub-xxxxxxxxxxxxxxxx"\n' +
               '     data-ad-slot="xxxxxxxxxx"\n' +
               '     data-ad-format="auto"\n' +
               '     data-full-width-responsive="true"></ins>\n' +
               '<script>\n' +
               '     (adsbygoogle = window.adsbygoogle || []).push({});\n' +
               '</script>';
    }
    
    function showTooltip($element, message) {
        var $tooltip = $('<div class="ads-tooltip">' + message + '</div>');
        $('body').append($tooltip);
        
        var offset = $element.offset();
        $tooltip.css({
            top: offset.top - $tooltip.outerHeight() - 10,
            left: offset.left + ($element.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
        }).fadeIn(200);
        
        setTimeout(function() {
            $tooltip.fadeOut(200, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    function initTooltips() {
        // Add hover tooltips for form fields
        $('input[type="text"], textarea').hover(
            function() {
                var $this = $(this);
                var description = $this.siblings('.description').text();
                if (description && !$this.data('tooltip-shown')) {
                    $this.data('tooltip-shown', true);
                    var $tooltip = $('<div class="ads-field-tooltip">' + description + '</div>');
                    $this.after($tooltip);
                }
            },
            function() {
                $(this).data('tooltip-shown', false);
                $(this).siblings('.ads-field-tooltip').remove();
            }
        );
    }
    
    // Copy AdSense code functionality
    $(document).on('click', '.copy-code-btn', function() {
        var $textarea = $(this).siblings('textarea');
        $textarea.select();
        document.execCommand('copy');
        
        var $btn = $(this);
        var originalText = $btn.text();
        $btn.text('Đã copy!').addClass('copied');
        
        setTimeout(function() {
            $btn.text(originalText).removeClass('copied');
        }, 2000);
    });
    
    // Add copy buttons to code textareas
    $('textarea[name*="_ad_code"]').each(function() {
        if ($(this).siblings('.copy-code-btn').length === 0) {
            $(this).after('<button type="button" class="button copy-code-btn">Copy Code</button>');
        }
    });
    
    // Preview ads functionality
    $('.preview-ad-btn').on('click', function() {
        var $textarea = $(this).siblings('textarea');
        var code = $textarea.val().trim();
        
        if (code === '') {
            alert('Vui lòng nhập mã AdSense trước khi xem trước');
            return;
        }
        
        // Open preview in new window
        var previewWindow = window.open('', 'ads-preview', 'width=800,height=600');
        previewWindow.document.write(
            '<html><head><title>Xem trước quảng cáo</title>' +
            '<style>body{font-family:Arial,sans-serif;padding:20px;background:#f0f0f0;}</style>' +
            '</head><body>' +
            '<h3>Xem trước quảng cáo</h3>' +
            '<div style="background:white;padding:20px;border-radius:5px;box-shadow:0 2px 5px rgba(0,0,0,0.1);">' +
            code +
            '</div>' +
            '<p style="margin-top:20px;color:#666;font-size:14px;">Đây là bản xem trước. Quảng cáo thực tế có thể khác nhau trên website.</p>' +
            '</body></html>'
        );
        previewWindow.document.close();
    });
    
    // Add preview buttons to code textareas
    $('textarea[name*="_ad_code"]').each(function() {
        if ($(this).siblings('.preview-ad-btn').length === 0) {
            $(this).after('<button type="button" class="button preview-ad-btn" style="margin-left:10px;">Xem trước</button>');
        }
    });
});

// CSS for dynamic elements
jQuery(document).ready(function($) {
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .ads-nav-tabs .nav-tab.disabled {
                opacity: 0.5;
                cursor: not-allowed;
                background: #f5f5f5 !important;
                color: #999 !important;
            }
            
            .client-id-feedback {
                margin-top: 5px;
                font-size: 13px;
                font-weight: 500;
            }
            
            .client-id-feedback.valid {
                color: #28a745;
            }
            
            .client-id-feedback.invalid {
                color: #dc3545;
            }
            
            .code-feedback {
                margin-top: 5px;
                font-size: 13px;
                font-weight: 500;
                padding: 5px 10px;
                border-radius: 3px;
                display: inline-block;
            }
            
            .code-feedback.valid {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            
            .code-feedback.invalid {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            
            .code-feedback.warning {
                background: #fff3cd;
                color: #856404;
                border: 1px solid #ffeaa7;
            }
            
            .ads-tooltip {
                position: absolute;
                background: #333;
                color: white;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 12px;
                z-index: 10000;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            
            .ads-tooltip:after {
                content: '';
                position: absolute;
                top: 100%;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 0;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 5px solid #333;
            }
            
            .ads-field-tooltip {
                position: absolute;
                background: #f8f9fa;
                border: 1px solid #ddd;
                padding: 5px 8px;
                font-size: 11px;
                color: #666;
                border-radius: 3px;
                max-width: 200px;
                z-index: 100;
                margin-top: 2px;
            }
            
            .ads-saving-indicator {
                background: #0073aa;
                color: white;
                padding: 10px 15px;
                border-radius: 4px;
                margin-bottom: 20px;
                font-weight: 500;
            }
            
            .ads-saving-indicator .dashicons {
                animation: spin 2s linear infinite;
            }
            
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .copy-code-btn,
            .preview-ad-btn {
                margin-top: 8px !important;
                font-size: 12px !important;
                padding: 4px 12px !important;
                height: auto !important;
                line-height: 1.3 !important;
            }
            
            .copy-code-btn.copied {
                background: #28a745 !important;
                color: white !important;
            }
        `)
        .appendTo('head');
});
