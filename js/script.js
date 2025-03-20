jQuery(document).ready(function($) {
  // Function to adjust body margin based on banner height
  function adjustBodyMargin() {
    if ($('#wptb-banner').length) {
      // Make sure the banner is visible for correct measurement
      $('#wptb-banner').css('display', 'block');

      // Get accurate height
      var bannerHeight = $('#wptb-banner').outerHeight(true);
      var adminBarOffset = wptbData.adminBarOffset || 0;

      // Verify we have a reasonable height value (sanity check)
      if (bannerHeight > 0 && bannerHeight < 500) {
        var totalOffset = parseInt(bannerHeight, 10)
        $('body').css('margin-top', totalOffset + 'px');

        // Debug
        console.log('Banner height:', bannerHeight, 'Admin offset:', adminBarOffset, 'Total:', totalOffset);
      } else {
        // Fallback to a safe default if we get an unreasonable height
        console.log('Unreasonable banner height detected:', bannerHeight, 'Using default.');
        $('body').css('margin-top', (50 + adminBarOffset) + 'px');
      }
    }
  }

  // Small delay to ensure DOM is fully rendered
  setTimeout(function() {
    adjustBodyMargin();
  }, 100);

  // Adjust margin on window resize with debounce
  var resizeTimer;
  $(window).on('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      adjustBodyMargin();
    }, 250);
  });

  // Handle banner dismissal
  $('#wptb-dismiss').click(function() {
    $('#wptb-banner').fadeOut(400, function() {
      $('body').css('margin-top', '0');
    });
    window.location.reload();
    document.cookie = 'wptb_dismissed=true; path=/; max-age=86400';
  });

  // One final check after all images and assets load
  $(window).on('load', function() {
    setTimeout(adjustBodyMargin, 200);
  });
});
