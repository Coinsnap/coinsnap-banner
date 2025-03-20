jQuery(document).ready(function($) {
  $('#wptb-dismiss').click(function() {
    $('#wptb-banner').fadeOut();
    document.cookie = 'wptb_dismissed=true; path=/; max-age=86400';
    $('body').css('margin-top', '0');
  });
});
