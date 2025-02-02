(function($) {
    "use strict"; // Enforces strict JavaScript rules for safer code

    let ajaxRequestCount = 0;
    let initialLoadComplete = false;

    $(window).on('load', function() {
        const preloader = $('#preloader');
        
        if (preloader.length) {
            // Wait for all initial AJAX calls before hiding preloader
            let checkAjaxComplete = setInterval(() => {
                if (ajaxRequestCount === 0) {
                    preloader.fadeOut('slow');
                    initialLoadComplete = true;
                    clearInterval(checkAjaxComplete);
                }
            }, 200); // Check every 200ms
        }
    });

    $(document).ready(function() {
        $('.modal').modal({
            show: false
        });

        $(document).ajaxStart(function() {
            ajaxRequestCount++;

            // Show preloader only if it's not already visible (to prevent double loading)
            if (initialLoadComplete && $('#preloader').is(':hidden')) {
                $('#preloader').fadeIn();
            }
        });

        $(document).ajaxStop(function() {
            ajaxRequestCount--;

            // Ensure preloader hides only when all AJAX calls are finished
            if (ajaxRequestCount === 0) {
                $('#preloader').fadeOut();
            }
        });
    });

    // Variables for navigation and header elements
    const navSections = $('section'); // All sections to monitor scroll position
    const mainNav = $('.nav-menu, #mobile-nav'); // Main and mobile nav menus
    const header = $('#header'); // Fixed header element
    const backToTop = $('.back-to-top'); // Back-to-top button
    let scrollTimeout; // Timeout to optimize scroll event handling

    // Function to update active nav link based on scroll position
    function updateNavState() {
        const curPos = $(window).scrollTop() + 200; // Current scroll position with offset

        navSections.each(function() {
            const section = $(this);
            const top = section.offset().top;
            const bottom = top + section.outerHeight();

            // Check if the current scroll position is within the section
            if (curPos >= top && curPos <= bottom) {
                mainNav.find('li').removeClass('active');
                mainNav.find(`a[href="#${section.attr('id')}"]`).parent('li').addClass('active');
            }
        });

        // Mark "Home" as active if scroll position is near the top
        if (curPos < 300) {
            mainNav.find('li:first').addClass('active');
        }
    }

    // Function to toggle header styles and back-to-top button visibility on scroll
    function toggleHeaderClass() {
        if ($(window).scrollTop() > 100) {
            header.addClass('header-scrolled'); // Add a class for styling the scrolled header
            backToTop.fadeIn('slow'); // Show the back-to-top button
        } else {
            header.removeClass('header-scrolled'); // Remove scrolled style
            backToTop.fadeOut('slow'); // Hide the back-to-top button
        }
    }

    // Optimized scroll event listener
    $(window).on('scroll', function() {
        if (scrollTimeout) clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            updateNavState(); // Update nav link active state
            toggleHeaderClass(); // Adjust header style and back-to-top visibility
        }, 100); // Add slight delay to reduce processing
    });

    // Ensure header styling is correct on page load
    if ($(window).scrollTop() > 100) {
        header.addClass('header-scrolled');
    }

    // Smooth scrolling for back-to-top button
    backToTop.click(function() {
        $('html, body').scrollTop(0);
        return false;
    });

    // Smooth scrolling for nav links
    const scrolltoOffset = header.outerHeight() - 2; // Offset to consider fixed header
    $(document).on('click', '.nav-menu a, .mobile-nav a, .scrollto', function(e) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            e.preventDefault();
            var target = $(this.hash);
            if (target.length) {
                var scrollto = target.offset().top - scrolltoOffset;

                // Special case: Scroll to the very top for the "Home" link
                if ($(this).attr("href") === '#header') {
                    scrollto = 0;
                }

                $('html, body').scrollTop(scrollto);


                // Update active nav item
                if ($(this).parents('.nav-menu, .mobile-nav').length) {
                    $('.nav-menu .active, .mobile-nav .active').removeClass('active');
                    $(this).closest('li').addClass('active');
                }

                // Close the mobile nav if it's open
                if ($('body').hasClass('mobile-nav-active')) {
                    $('body').removeClass('mobile-nav-active');
                    $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
                    $('.mobile-nav-overly').fadeOut();
                }
                return false;
            }
        }
    });

    // Mobile navigation setup
    if ($('.nav-menu').length) {
        const mobileNav = $('.nav-menu').clone().prop({ class: 'mobile-nav d-lg-none' }); // Clone nav for mobile
        $('body').append(mobileNav).prepend('<button type="button" class="mobile-nav-toggle d-lg-none"><i class="icofont-navigation-menu"></i></button>');
        $('body').append('<div class="mobile-nav-overly"></div>');

        // Toggle mobile nav visibility
        $(document).on('click', '.mobile-nav-toggle', function() {
            $('body').toggleClass('mobile-nav-active');
            $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
            $('.mobile-nav-overly').toggle();
        });

        // Close mobile nav when clicking outside
        $(document).click(function(e) {
            const container = $('.mobile-nav, .mobile-nav-toggle');
            if (!container.is(e.target) && container.has(e.target).length === 0 && $('body').hasClass('mobile-nav-active')) {
                $('body').removeClass('mobile-nav-active');
                $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
                $('.mobile-nav-overly').fadeOut();
            }
        });
    } else {
        // Hide mobile nav toggle if no nav menu is present
        $('.mobile-nav, .mobile-nav-toggle').hide();
    }

})(jQuery);