(function ($) {

    // Social Share Sortable
    $('.twp-be-social-re-oprder').sortable({
        axis: 'y',
        containment: "parent",
        update:function(event,ui){
                    var profile_array = [];
                    $('.twp-be-social-share-wrap input[type="checkbox"]').each(function(){
                    profile_array.push($(this).attr('data-key')) ;
                    });
                    var social_networks_orders = profile_array.join(',');
                    $('#twp_social_share_options').val(social_networks_orders);
                }
    });

    $('.twp-be-tab').click(function(){
        var id = $(this).attr('id');
        $('.twp-be-tab').removeClass('twp-tab-active');
        $(this).addClass('twp-tab-active');
        $('.twp-be-content').removeClass('twp-content-active');
        $('#'+id+'-content').addClass('twp-content-active');
        
    });


    $('.twp-toggle-control').click(function(){
        $(this).closest('.twp-be-social-share-options').find('.twp-social-control').slideToggle();
    });


    // Image Upload
    $('.be-img-upload-button').click( function(){

        event.preventDefault();
        var imgContainer = $(this).closest('.twp-img-fields-wrap').find( '.twp-thumbnail-image .twp-img-container'),
        removeimg = $(this).closest('.twp-img-fields-wrap').find( '.twp-img-delete-button'),
        imgIdInput = $(this).siblings('.upload-id');
        var frame;

        // Create a new media frame
        frame = wp.media({
            title: booster_extension_admin.upload_image,
            button: {
            text: booster_extension_admin.use_imahe
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

}(jQuery));