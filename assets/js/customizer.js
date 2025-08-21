/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function($) {
    'use strict';

    // Site title and description.
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });
    
    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Header text color.
    wp.customize('header_textcolor', function(value) {
        value.bind(function(to) {
            if ('blank' === to) {
                $('.site-title, .site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });

    // Primary color
    wp.customize('hot_news_primary_color', function(value) {
        value.bind(function(to) {
            var style = '<style id="hot-news-primary-color">:root { --primary-color: ' + to + '; } a { color: ' + to + '; } .nav-bar, .nav-bar .navbar { background: ' + to + ' !important; } .back-to-top { background: ' + to + '; } .hot-news-badge { background: linear-gradient(45deg, ' + to + ', ' + to + '80); }</style>';
            $('#hot-news-primary-color').remove();
            $('head').append(style);
        });
    });

    // Secondary color
    wp.customize('hot_news_secondary_color', function(value) {
        value.bind(function(to) {
            var style = '<style id="hot-news-secondary-color">:root { --secondary-color: ' + to + '; } .back-to-top:hover { background: ' + to + '; }</style>';
            $('#hot-news-secondary-color').remove();
            $('head').append(style);
        });
    });

    // Contact email
    wp.customize('hot_news_contact_email', function(value) {
        value.bind(function(to) {
            $('.tb-contact p:first-child').html('<i class="fas fa-envelope"></i>' + to);
        });
    });

    // Contact phone
    wp.customize('hot_news_contact_phone', function(value) {
        value.bind(function(to) {
            $('.tb-contact p:last-child').html('<i class="fas fa-phone-alt"></i>' + to);
        });
    });

    // Footer copyright
    wp.customize('hot_news_footer_copyright', function(value) {
        value.bind(function(to) {
            $('.footer-bottom .copyright p').html(to);
        });
    });

    // Newsletter title
    wp.customize('hot_news_newsletter_title', function(value) {
        value.bind(function(to) {
            $('.newsletter').closest('.footer-widget').find('.title').text(to);
        });
    });

    // Newsletter description
    wp.customize('hot_news_newsletter_description', function(value) {
        value.bind(function(to) {
            $('.newsletter p').text(to);
        });
    });

})(jQuery);
