'use strict';

require('bxslider/dist/jquery.bxslider');

function initSearchBox() {
    var $formSearch = $('#form-search');
    var $searchField = $('.search-field');

    $searchField.keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();

            if ($searchField.val() === '') {
                $searchField.focus();
            } else {
                $formSearch.submit();
            }
        }
    })
}

function initProtectedContent() {
    $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
    });
}

function initGoToTop() {
    var $goToTop = $('.go-to-top');

    $goToTop.click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
}

function initPartnerSlider() {
    $('.bxslider').show().bxSlider({
        auto: true,
        autoControls: false,
        stopAutoOnClick: true,
        pager: false,
        controls: true,
        minSlides: 1,
        maxSlides: 4,
        moveSlides: 1,
        slideWidth: 262,
        onSliderLoad: function() {
            $('.bxslider-wrapper').css("visibility", "visible");
        }
    });
}

function initNewsSlider() {
    $('.post-sidebar-bxslider').bxSlider({
        mode: 'vertical',
        auto: true,
        speed: 300,
        autoControls: false,
        stopAutoOnClick: true,
        pager: false,
        controls: false,
        minSlides: 5,
        maxSlides: 5,
        moveSlides: 1,
        slideWidth: 375
    });
}

function initFixedMenu() {
    $(window).scroll(function() {
        var $nav = $("#nav");
        var $scrollUp = $('.td-scroll-up');
        var scroll = $(window).scrollTop();
    
        if (scroll >= 42) {
            $nav.addClass("navbar-fixed-top");
            $scrollUp.removeClass("hidden");
        } else {
            $nav.removeClass("navbar-fixed-top");
            $scrollUp.addClass("hidden");
        }
    });
}

exports.init = function () {
    initSearchBox();
    initPartnerSlider();
    initNewsSlider();
    initProtectedContent();
    initGoToTop();
    initFixedMenu();
};