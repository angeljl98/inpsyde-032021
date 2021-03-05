(function ($) {
  $(document).ready(function () {
    $('body').on('click', 'div[data-for="ssl-zen-notice"] button', function () {
      $.ajax({
        url: ajaxurl,
        data: {
          'action': 'ssl_zen_event_on_notice_dismiss',
          'ssl_zen_notice_nonce': ssl_zen_notice_nonce.nonce
        },
        success: function (data) {
        },
        error: function (errorThrown) {
        }
      })
    })
  });
})(jQuery);
