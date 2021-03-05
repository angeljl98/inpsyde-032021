(function ($) {

    var custom_theme_file_frame;
    var ajaxurl = mate_admin.ajax_url;
    var mateNonce = mate_admin.ajax_nonce;


    // Uploads.
      jQuery(document).on('click', 'input.select-img', function( event ){

        var $this = $(this);

        event.preventDefault();

        var CustomThemeImage = wp.media.controller.Library.extend({

            defaults :  _.defaults({
                    id:        'custom-theme-insert-image',
                    title:      $this.data( 'uploader_title' ),
                    allowLocalEdits: false,
                    displaySettings: true,
                    displayUserSettings: false,
                    multiple : false,
                    library: wp.media.query( { type: 'image' } )
              },wp.media.controller.Library.prototype.defaults )

        });

        // Create the media frame.
        custom_theme_file_frame = wp.media.frames.custom_theme_file_frame = wp.media({

            button: {
                text: jQuery( this ).data( 'uploader_button_text' )
            },
            state : 'custom-theme-insert-image',
                states : [
                    new CustomThemeImage()
                ],
            multiple: false

        });

        // When an image is selected, run a callback.
        custom_theme_file_frame.on( 'select', function() {

          var state = custom_theme_file_frame.state('custom-theme-insert-image');
          var selection = state.get('selection');
          var display = state.display( selection.first() ).toJSON();
          var obj_attachment = selection.first().toJSON();
          display = wp.media.string.props( display, obj_attachment );

          var image_field = $this.siblings('.img');
          var imgurl = display.src;

          // Copy image URL.
          image_field.val(imgurl);
          image_field.trigger('change');

          // Show in preview.
          var image_preview_wrap = $this.siblings('.image-preview-wrap');
          var image_html = '<img src="' + imgurl+ '" alt="" style="width:200px;height:auto;" />';
          image_preview_wrap.html( image_html );

          // Show Remove button.
          var image_remove_button = $this.siblings('.btn-image-remove');
          image_remove_button.css('display','inline-block');

        });

        // Finally, open the modal.
        custom_theme_file_frame.open();

      });

      // Remove image.
      jQuery(document).on('click', 'input.btn-image-remove', function( e ) {

        e.preventDefault();
        var $this = $(this);
        var image_field = $this.siblings('.img');
        image_field.val('');
        var image_preview_wrap = $this.siblings('.image-preview-wrap');
        image_preview_wrap.html('');
        $this.css('display','none');
        image_field.trigger('change');

      });

    $('.twp-img-upload-button').click( function(){

        event.preventDefault();
        var imgContainer = $(this).closest('.twp-img-fields-wrap').find( '.twp-thumbnail-image .twp-img-container'),
        removeimg = $(this).closest('.twp-img-fields-wrap').find( '.twp-img-delete-button'),
        imgIdInput = $(this).siblings('.upload-id');
        var frame;

        // Create a new media frame
        frame = wp.media({
            title: mate_admin.upload_image,
            button: {
            text: mate_admin.use_imahe
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        frame.on( 'select', function() {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();
            // Send the attachment URL to our custom image input field.
            imgContainer.html( '<img src="'+attachment.url+'" style="width:200px;height:auto;" />' );
            removeimg.addClass('twp-img-show');
            // Send the attachment id to our hidden input
            imgIdInput.val( attachment.url ).trigger('change');

        });

        // Finally, open the modal on click
        frame.open();

    });

    // DELETE IMAGE LINK
    $('.twp-img-delete-button').click( function(){

        event.preventDefault();
        var imgContainer = $(this).closest('.twp-img-fields-wrap').find( '.twp-thumbnail-image .twp-img-container');
        var removeimg = $(this).closest('.twp-img-fields-wrap').find( '.twp-img-delete-button');
        var imgIdInput = $(this).closest('.twp-img-fields-wrap').find( '.upload-id');
        // Clear out the preview image
        imgContainer.find('img').remove();
        removeimg.removeClass('twp-img-show');
        // Delete the image id from the hidden input
        imgIdInput.val( '' ).trigger('change');

    });

    // Remove IMAGE AFTER CATEGORY CREATED LINK
    $(document).ajaxSuccess(function(e, request, settings){

        var object = settings.data;
        if( typeof object == 'string' ){

            var object = object.split("&");

            if( object.includes( 'action=add-tag' ) && object.includes( 'screen=edit-category' ) && object.includes( 'taxonomy=category' ) ){
                
                $('.twp-img-delete-button').removeClass('twp-img-show');
                $('.upload-id').attr('value','');
                $('.twp-img-container').empty();

            }

        }

    });

    // Metabox Tab
    $('.metabox-navbar a').click(function (){
        var tabid = $(this).attr('id');
        $('.metabox-navbar a').removeClass('metabox-navbar-active');
        $(this).addClass('metabox-navbar-active');
        $('.twp-tab-content .metabox-content-wrap').hide();
        $('.twp-tab-content #'+tabid+'-content').show();
        $('.twp-tab-content .metabox-content-wrap').removeClass('metabox-content-wrap-active');
        $('.twp-tab-content #'+tabid+'-content').addClass('metabox-content-wrap-active');
    });

    // Dismiss notice
    $('.twp-custom-setup').click(function(){
        
        var data = {
            'action': 'mate_notice_dismiss',
            '_wpnonce': mateNonce,
        };
 
        $.post(ajaxurl, data, function( response ) {

            $('.twp-mate-notice').hide();
            
        });

    });

    // Getting Start action
    $('.twp-install-active').click(function(){

        $(this).closest('.twp-mate-notice').addClass('twp-installing');

        var data = {
            'action': 'mate_install_plugins',
            '_wpnonce': mateNonce,
        };
 
        $.post(ajaxurl, data, function( response ) {

            window.location.href = response;
            
        });

    });

    $('.theme-recommended-plugin .recommended-plugin-status').click(function(){
        
        var id = $(this).closest('.about-items-wrap').attr('id');

        $(this).addClass('twp-activating-plugin')
        var PluginName = $(this).closest('.theme-recommended-plugin').find('h2').text();
        var PluginStatus = $(this).attr('plugin-status');
        var PluginFile = $(this).attr('plugin-file');
        var PluginFolder = $(this).attr('plugin-folder');
        var PluginSlug = $(this).attr('plugin-slug');
        var pluginClass = $(this).attr('plugin-class');

        var data = {
            'single': true,
            'PluginStatus': PluginStatus,
            'PluginFile': PluginFile,
            'PluginFolder': PluginFolder,
            'PluginSlug': PluginSlug,
            'PluginName': PluginName,
            'pluginClass': pluginClass,
            'action': 'mate_install_plugins',
            '_wpnonce': mateNonce,
        };
 
        $.post(ajaxurl, data, function( response ) {
            
            var active = mate_admin.active
            var deactivate = mate_admin.deactivate
            $('#'+id+' .recommended-plugin-status').empty();

            if( response == 'Deactivated' ){
                
                $('#'+id+' .theme-recommended-plugin').removeClass('recommended-plugin-active');
                $('#'+id+' .recommended-plugin-status').removeClass('twp-plugin-active');
                $('#'+id+' .recommended-plugin-status').addClass('twp-plugin-deactivate');
                $('#'+id+' .recommended-plugin-status').html(active);
                $('#'+id+' .recommended-plugin-status').attr('plugin-status','deactivate');

            }else if( response == 'Activated' ){
                
                $('#'+id+' .theme-recommended-plugin').addClass('recommended-plugin-active');
                $('#'+id+' .recommended-plugin-status').removeClass('twp-plugin-deactivate');
                $('#'+id+' .recommended-plugin-status').addClass('twp-plugin-active');
                $('#'+id+' .recommended-plugin-status').html(deactivate);
                $('#'+id+' .recommended-plugin-status').attr('plugin-status','active');

            }else{
                
                $('#'+id+' .theme-recommended-plugin').removeClass('recommended-plugin-active');
                $('#'+id+' .recommended-plugin-status').removeClass('twp-plugin-not-install');
                $('#'+id+' .recommended-plugin-status').addClass('twp-plugin-active');
                $('#'+id+' .recommended-plugin-status').html(active);
                $('#'+id+' .recommended-plugin-status').attr('plugin-status','deactivate');

            }

            $('.recommended-plugin-status').removeClass('twp-activating-plugin');
            
        });
    });

}(jQuery));