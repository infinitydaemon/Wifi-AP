(function($) {
  "use strict";

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    $(".sidebar").hasClass("toggled") && $('.sidebar .collapse').collapse('hide');
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(() => {
    $(window).width() < 768 && $('.sidebar .collapse').collapse('hide');
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    $(window).width() > 768 && (
      this.scrollTop += (e.originalEvent.wheelDelta || -e.originalEvent.detail) < 0 ? 30 : -30,
      e.preventDefault()
    );
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    const scrollDistance = $(this).scrollTop();
    $('.scroll-to-top').fadeToggle(scrollDistance > 100 ? "slow" : 0);
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    const $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: $($anchor.attr('href')).offset().top
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery);
