function preventDefault(event) {
  event.preventDefault();
}

(function ($) {
  $(document).ready(function () {
    // Define variable upon ready
    var $body = $('body'),
            $formPricing = $('#frmpricing'),
            $formStep1 = $('#frmstep1'),
            $formStep2 = $('#frmstep2'),
            $formStep3 = $('#frmstep3'),
            $formStep4 = $('#frmstep4'),
            $formSettings = $('#frmSettings'),
            $formSys = $('#frmsysreq'),
            dnsCheckerInterval;

    // Set toggles and tooltips
    $('[data-toggle="tooltip"]').tooltip();
    $('#fix_cloudflare').bootstrapToggle();

    // Primary next button click event
    $body.on('click', 'a.primary.next', function (e) {
      e.preventDefault();
      // Check if button is live
      if ($(this).hasClass('disabled')) {
        return false;
      }
      var $form = $('.ssl-zen-container form');
      if ($form.valid()) {
        showLoading($body);
        $form.submit();
      }
    });

    // System requirements step
    if ($formSys.length) {
      $body.on('click', 'a#next, a#reCheck', function (e) {
        e.preventDefault();
        $formSys.submit();
      });
      // If everything is ok, implement auto submit
      var $next = $('a#next');
      if ($next.length) {
        setTimeout(function () {
          $next.trigger('click');
        }, 3000)
      }
    }

    // Pricing page
    if ($formPricing.length) {
      // Prepare click event for free plan to submit the form
      $body.on('click', '#frmpricing .free > a', function (e) {
        e.preventDefault();
        $formPricing.submit();
      });
    }

    // Step 1
    if ($formStep1.length) {
      // Set validation
      $formStep1.validate({
        rules: {
          ssl_zen_cpanel_username: {
            required: true,
            remote: {
              url: ajaxurl,
              type: "POST",
              cache: false,
              dataType: "json",
              data: {
                action: 'ssl_zen_cpanel_check_credentials_ajax',
                username: function () {
                  return jQuery("#ssl_zen_cpanel_username").val();
                },
                password: function () {
                  return jQuery("#ssl_zen_cpanel_password").val();
                },
              },
              dataFilter: function (response) {
                if (jQuery("#ssl_zen_cpanel_password").val() != '') {
                  jQuery("#ssl_zen_cpanel_password").valid();
                  return response === 'true';
                }
                return true;
              }
            }
          },
          ssl_zen_cpanel_password: {
            required: true,
            remote: {
              url: ajaxurl,
              type: "POST",
              cache: false,
              dataType: "json",
              data: {
                action: 'ssl_zen_cpanel_check_credentials_ajax',
                username: function () {
                  return jQuery("#ssl_zen_cpanel_username").val();
                },
                password: function () {
                  return jQuery("#ssl_zen_cpanel_password").val();
                },
              },
              dataFilter: function (response) {
                return response === 'true';
              }
            }
          }
        },
        messages: {
          email: {
            required: "Enter your Email",
            email: "Please enter a valid email address.",
          },
          ssl_zen_cpanel_username: {
            required: "Enter your cPanel username",
            remote: "",
          },
          ssl_zen_cpanel_password: {
            required: "Enter your cPanel password",
            remote: "Username or Password incorrect",
          },
        }
      });

      // www valid checker
      $body.on('change', '#frmstep1 #include_www', function (e) {
        var $this = $(this),
                baseDomainName = $('#base_domain_name').val(),
                messageContainerEL = $formStep1.find('.message-container'),
                nextButton = $formStep1.find('a.next');
        if ($this.prop("checked")) {
          if (baseDomainName !== '') {
            // Disable next button while check
            nextButton.addClass('disabled');
            $.ajax({
              url: ajaxurl,
              method: "POST",
              data: {
                'action': 'ssl_zen_check_for_dns_records',
                'domain': baseDomainName,
                'nonce': $('input[name=ssl_zen_generate_certificate_nonce]').val()
              },
              success: function (dataJson) {
                var data = JSON.parse(dataJson),
                        message;
                if (typeof data['status'] !== 'undefined') {
                  if (data['status']) {
                    // Enable next button, in case if it was disabled
                    nextButton.removeClass('disabled');
                  } else {
                    // Show error message and disable next button
                    nextButton.addClass('disabled');
                    message = '<div class="message warning">' + data['message'] + '</div>';
                  }
                } else {
                  message = '<div class="message error mt-4">Error</div>';
                }
                if (message) {
                  messageContainerEL.html(message);
                }
              },
              error: function (errorThrown) {
                // Network error
                messageContainerEL.html('<div class="message error">Error</div>');
              }
            })
          }
        } else {
          // Enable next button , remove message
          nextButton.removeClass('disabled');
          messageContainerEL.html('');
        }
      });
    }

    // Step 2
    if ($formStep2.length) {
      $body.on('click', '.update-dns-stackpath', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var url = e.target.href;
        var dcWindow = window.open(url, '', 'resizable=yes, width=980, height=700, top=50, left=50');
        var timer = setInterval(checkWindowClosed, 500);

        function checkWindowClosed() {
          if (dcWindow.closed) {
            $('.scan-dns-stackpath').click();
            clearInterval(timer);
          }
        }
      });

      // Prepare click event for variants
      $body.on('click', '#frmstep2 .ssl-zen-domain-verification-variant-container', function (e) {
        e.preventDefault();
        // Set proper selected class to the container
        $('#frmstep2 .ssl-zen-domain-verification-variant-container').removeClass('selected');
        $(this).addClass('selected');
        // Set hidden input value
        if ($(this).hasClass('http')) {
          // Yes then set HTTP(1)
          $('#ssl_zen_domain_verification').val('http');
        } else {
          // Else set DNS(2)
          $('#ssl_zen_domain_verification').val('dns');
        }
      });

      // Prepare click events for variant tabs
      $body.on('click', '#frmstep2 .ssl-zen-domain-verification-variant-tabs li', function (e) {
        e.preventDefault();
        // Set proper active class to the tab
        $('#frmstep2 .ssl-zen-domain-verification-variant-tabs li').removeClass('active');
        $(this).addClass('active');

        // Show tab container
        $('.ssl-zen-domain-verification-variant-tab-container').removeClass('d-none');
        if ($(this).hasClass('http')) {
          // Yes then hide DNS container
          $('.ssl-zen-domain-verification-variant-tab-container.dns').addClass('d-none');
          $('#frmstep2 .ssl-zen-steps-container').removeClass('p-0');
        } else {
          // Yes then hide HTTP container
          $('.ssl-zen-domain-verification-variant-tab-container.http').addClass('d-none');
          $('#frmstep2 .ssl-zen-steps-container').addClass('p-0');
        }
      });

      // Scan dns record click event scan-dns
      $body.on('click', '#frmstep2 .scan-dns, #frmstep2 .scan-http', function (e) {
        e.preventDefault();
        var $this = $(this),
                variant = $this.hasClass('scan-dns') ? 'dns' : 'http',
                variantContainerEl = $('.ssl-zen-domain-verification-variant-tab-container.' + variant);

        // Decline job if button is disabled
        if ($this.hasClass('disabled')) {
          return;
        }
        showLoading($body);
        // Prepare ajax call
        $.ajax({
          url: ajaxurl,
          data: {
            'action': 'ssl_zen_domain_verification',
            'variant': variant,
            'nonce': $('input[name=ssl_zen_verify_nonce]').val()
          },
          success: function (dataJson) {
            hideLoading($body);
            var data = JSON.parse(dataJson),
                    message;
            if (typeof data['status'] !== 'undefined') {
              if (data['status']) {
                // Further actions
                $('#frmstep2 a.primary.next').removeClass('disabled');
                message = '<div class="message success">' + data['message'] + '</div>';
                // Show success message
                $('.ssl-zen-domain-verification-variant-tabs li.' + variant).removeClass('error');
                // Disable the current button
                $this.addClass('disabled');
              } else {
                // Show error message and mark variant as error
                $('.ssl-zen-domain-verification-variant-tabs li.' + variant).addClass('error');
                message = '<div class="message warning">' + data['message'] + '</div>';

                // Enable count down timer and disable button if the variant was DNS (same if time param exist)
                if (data['time']) {
                  sslDnsCheckTimeLeft = data['time'];
                  dnsCheckerInterval = setInterval(dnsTimer, 1000);
                  $this.addClass('disabled');
                  // Show timer container
                  $formStep2.find('.time-wait').removeClass('d-none');
                }
              }
            } else {
              message = '<div class="message error">Error</div>';
            }
            variantContainerEl.find('.message-container').html(message);
          },
          error: function (errorThrown) {
            hideLoading($body);
            // Network error
            variantContainerEl.find('.message-container').html('<div class="message error">Error</div>');
          }
        })
      });

      // Copy DNS files
      $body.on('click', '#frmstep2 i.copy', function (e) {
        var row = $(this).parent().hasClass('first') ? 'first' : 'second';
        var selector = $(this).prev().hasClass('acme') ? '#frmstep2 .record.' + row + ' input.acme' : '#frmstep2 .record.' + row + ' input.txt';
        //TODO add JS translation
        if (copy(selector)) {
          $formStep2.find('.message-container').html('<div class="message success">Copied successfully.</div>');
        } else {
          $formStep2.find('.message-container').html('<div class="message error">Failed to copy.</div>');
        }
        // Hide the message
        setTimeout(function () {
          $formStep2.find('.message-container').html('');
        }, 3000)
      });

      // Enable timer for next DNS check , in case the option is active
      if (typeof sslDnsCheckTimeLeft !== 'undefined') {
        // Show timer container
        $formStep2.find('.time-wait').removeClass('d-none');
        // Enable timer with interval
        dnsCheckerInterval = setInterval(dnsTimer, 1000);
      }

      function dnsTimer() {
        // Time calculations for days, hours, minutes and seconds
        var minutes = Math.floor((sslDnsCheckTimeLeft % (3600)) / (60));
        var seconds = Math.floor((sslDnsCheckTimeLeft % (60)));

        // Concat the 0 in case if
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        // If the count down is over, enable the button
        if (sslDnsCheckTimeLeft < 0) {
          clearInterval(dnsCheckerInterval);
          $formStep2.find('a.scan-dns').removeClass('disabled');
          $formStep2.find('.time-wait').addClass('d-none');
          $formStep2.find('.time-wait .ms').html('');
          return;
        }

        // Fill the time into the DOM
        $formStep2.find('.time-wait .ms').html(minutes + ":" + seconds);

        // Decrease interval
        sslDnsCheckTimeLeft--;
      }
    }

    // Step 3
    if ($formStep3.length) {

      // Get certs
      $body.on('click', '#frmstep3 div.filename', function (e) {
        e.preventDefault();
        var $this = $(this);
        $($this.parent().find('i.copy')).trigger('click');
      });

      // Get certs
      $body.on('click', '#frmstep3 i.copy', function (e) {
        e.preventDefault();
        var $this = $(this);
        var fileName = $this.data('content');

        showLoading($body);

        // Prepare ajax call
        $.ajax({
          url: ajaxurl,
          method: 'GET',
          data: {
            'action': 'ssl_zen_cert_files',
            'file_name': fileName,
            'nonce': $('input[name=ssl_zen_install_certificate_nonce]').val()
          },
          success: function (dataJson) {
            var data = JSON.parse(dataJson);
            var message;
            hideLoading($body);
            if (typeof data['status'] !== 'undefined') {
              if (data['status'] && data['file']) {
                // Further actions
                $body.prepend($('.ssl-zen-copy-certs-wrapper').clone());
                var certWrapper = $('body > .ssl-zen-copy-certs-wrapper');
                certWrapper.removeClass('d-none').addClass('d-flex');
                $(certWrapper.find('.body textarea')).val(data['file']);
                certWrapper.find('.title').html(fileName);
              } else {
                // Show error message and mark variant as error
                message = '<div class="message warning">' + data['message'] + '</div>'
              }
            } else {
              message = '<div class="message error">Error</div>'
            }
            // variantContainerEl.find('.message-container').html(message);
          },
          error: function (errorThrown) {
            hideLoading($body)
            // Network error
            // variantContainerEl.find('.message-container').html('<div class="message error">Error</div>');
          }
        })
      });

      // Copy certs
      $body.on('click', '.ssl-zen-copy-certs-wrapper span.copy', function (e) {
        e.preventDefault();
        if (copy('body > .ssl-zen-copy-certs-wrapper div.body > textarea')) {
          $('body > .ssl-zen-copy-certs-wrapper div.head > .message.success').removeClass('d-none');
        } else {
          $('body > .ssl-zen-copy-certs-wrapper div.head > .message.error').removeClass('d-none');
        }
      });

      // Close certs copy container
      $body.on('click', '.ssl-zen-copy-certs-wrapper span.close', function (e) {
        e.preventDefault();
        $(this).parent().parent().parent().remove();
      });
    }

    // Step 4
    if ($formStep4.length) {
      // Check renew confirm
      $body.on('change', '#ssl_zen_renew_confirm', function (e) {
        // Enable next button
        if ($(this).is(':checked')) {
          $('#frmstep4 a.primary.next').removeClass('disabled');
        } else {
          $('#frmstep4 a.primary.next').addClass('disabled');
        }
      });
    }

    // Step Settings
    if ($formSettings.length) {

      // Enable data toggles
      $('.toggle-event').bootstrapToggle({
        on: '',
        off: '',
        style: 'ios',
        size: 'mini'
      });

      // Enable debug log
      $('#enable_debug').change(function () {
        var $this = $(this);

        // Show loading
        showLoading($body);

        // Prepare ajax call
        $.ajax({
          url: ajaxurl,
          method: 'GET',
          data: {
            'action': 'ssl_zen_settings_debug',
            'enable_debug': $this.prop("checked") ? 1 : 0,
            'nonce': $('input[name=ssl_zen_settings_nonce]').val()
          },
          success: function (data) {
            if (data.data) {
              $('.message-container-2').html(data.data.notice);
            }
            hideLoading($body);
          },
          error: function (errorThrown) {
            hideLoading($body)
          }
        })
      });


      // Settings tab change
      $body.on('click', '#frmSettings .ssl-zen-settings-tab-container > li', function (e) {
        var $this = $(this);
        var tab = $this.data('tab');

        // Activate tab and container
        $('#frmSettings .ssl-zen-settings-tab-container > li').removeClass('active');
        $this.addClass('active');
        $('#frmSettings div.ssl-zen-settings-container').addClass('d-none');
        $('div.ssl-zen-settings-container.' + tab + '-container').removeClass('d-none');
      });

      // Renew click
      $body.on('click', '#frmSettings a.renew', function (e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) {
          return false;
        } else {
          showLoading($body);
          $formSettings.prepend('<input type="hidden" name="ssl_zen_renew_certificate" value="1">');
          $formSettings.submit();
        }
      });

      // Save click
      $body.on('click', '#frmSettings a.save', function (e) {
        e.preventDefault();
        showLoading($body);
        $formSettings.submit();
      });

      // Save click
      $body.on('click', '#frmSettings a.deactivate', function (e) {
        e.preventDefault();
        if (confirm('Are you sure you want to deactivate the plugin')) {
          showLoading($body);
          $formSettings.prepend('<input type="hidden" name="ssl_zen_deactivate_plugin" value="1">');
          $formSettings.submit();
        }
      });

      // generic form button linked to a hidden field
      $body.on('click', '#frmSettings a.sslzen-form-button', function (e) {
        e.preventDefault();
        showLoading($body);
        var thisElement = $(this);
        var linkedHiddenElement = $(thisElement.data('hidden'));
        var linkedHiddenValue = thisElement.data('hidden-value');
        linkedHiddenElement.val(linkedHiddenValue);
        $formSettings.submit();
      });
    }

    initializeClipboard();

    initializeTimer();

    initGenericAutoFields();

    initRemindLaterTimeout();
  });

  function initRemindLaterTimeout() {
    if ($('.remind').length) {
      setTimeout(function () {
        $('.remind').show();
      }, 10000);
    }
  }

  function initGenericAutoFields() {
    var $body = $('body');
    // generic text field, which compares its value with an attribute
    // and then takes action on a linked element if comparison succeeds
    $('.sslzen-compare-value-enable').on('keyup', function (e) {
      var compareWith = $(this).attr('data-compare-with');
      var operator = $(this).attr('data-compare-operator');
      var value = $(this).val();
      var enableWhat = $(this).attr('data-trigger');
      var goAhead = false;

      switch (operator) {
        case '!=':
          goAhead = value !== '' && compareWith !== value;
          break;
              // add more cases later
      }
      if (goAhead) {
        $(enableWhat).removeClass('disabled');
      } else {
        $(enableWhat).addClass('disabled');
      }
    });
  }

  /**
   * Show loading container
   * @param $body
   */
  function showLoading($body) {
    $body.prepend('<div class="ssl-loading-wrap d-flex align-items-center justify-content-center"><div></div></div>');
  }

  /**
   * Hide loading container
   * @param $body
   */
  function hideLoading($body) {
    $body.find('.ssl-loading-wrap').remove();
  }

  /**
   * Use clipboard (from core) to copy stuff.
   */
  function initializeClipboard() {
    var clipboard1 = new ClipboardJS('.copy-clipboard');
    clipboard1.on('success', function (e) {
      $('.message-container').html('<div class="message success">' + params.l10n.copied_success + '</div>');
      setTimeout(function () {
        $('.message-container').html('');
      }, 2000);
    });
  }

  /**
   * This will initialize timer as an automatic discoverable entity if an element contains
   * class timer-automatic.
   *
   * If it additionally contains
   * - timer-automatic-fire, it will click the element associated with it.
   * - timer-automatic-fire-ajax, it will fire an ajax request associated with it.
   * Ajax parameters will be available as data-ajax-* attributes on the clickable element.
   *
   * attribute data-time: the time to start with
   * attribute data-button: link/button to enable when the time is up
   */
  var sslCheckTimeLeft, sslCheckerInterval;

  function initializeTimer() {
    if ($('.timer-automatic').length > 0 && $('.timer-automatic.d-none').length === 0) {
      var timer = $('.timer-automatic');

      // need to call a custom JS function when ajax returns?
      var jsFunction = window[timer.attr('data-function')];

      sslCheckTimeLeft = timer.attr('data-time');
      var button = $(timer.attr('data-button'));
      sslCheckerInterval = setInterval(function () {
        if (!countdownTimer()) {
          if (!timer.hasClass('timer-automatic-enable-no')) {
            button.removeClass('disabled');
          }
          timer.addClass('d-none');
          timer.find('.ms').html('');
          if (timer.hasClass('timer-automatic-fire-ajax')) {
            $.ajax({
              url: ajaxurl,
              type: 'POST',
              data: JSON.parse(button.attr('data-ajax-data')),
              success: function (data) {
                console.log(data);
                if (typeof jsFunction === 'function') {
                  jsFunction(data);
                }
                if (data.success) {
                  $('.message-container-2').html(data.data.notice);
                  // location.reload();
                  initializeLocationRedirection();
                } else {
                  if ('' !== data.data.notice) {
                    $('.message-container-2').html(data.data.notice);
                  }
                  timer.removeClass('d-none');
                  initializeTimer();
                }
              },
              complete: function () {
              }
            });
          } else if (timer.hasClass('timer-automatic-fire')) {
            button.trigger('click');
          }
        }
      }, 1000);
      button.addClass('disabled');
      // Show timer container
      timer.removeClass('d-none');
    }
  }

  function initializeLocationRedirection() {
    setTimeout(function () {
      location.reload();
    }, 4000);
  }

  window.step2_mark_records_done = function (data) {
    // iterate through all the records that are correct and mark them as done.
    $.each(data.data.records, function (index, record) {
      $('tr.record_type_' + record + ' i.copy-clipboard').addClass('d-none');
      $('tr.record_type_' + record + ' img.record-done').removeClass('d-none');
    });
  }

  /**
   * Implements a generic timer, not tightly coupled to a form.
   */
  function countdownTimer() {
    // Time calculations for days, hours, minutes and seconds
    var minutes = Math.floor((sslCheckTimeLeft % (3600)) / (60));
    var seconds = Math.floor((sslCheckTimeLeft % (60)));

    // Concat the 0 in case if
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    // If the count down is over, enable the button
    if (sslCheckTimeLeft === 0) {
      clearInterval(sslCheckerInterval);
      sslCheckerInterval = false;
      return false;
    }

    // Fill the time into the DOM
    $('.time-wait.timer-automatic .ms').html(minutes + ":" + seconds);

    // Decrease interval
    sslCheckTimeLeft--;
    return true;
  }


  /**
   * Copy functionality
   *
   * @param selector
   */
  function copy(selector) {
    // Get the text field
    var element = document.querySelector(selector);
    if (typeof element !== 'undefined') {
      // Select the text field
      element.select();
      // For mobile devices
      element.setSelectionRange(0, 9999999);
      // Copy the text inside the text field
      return document.execCommand("copy");
    } else {
      return false;
    }
  }
})(jQuery, params);





