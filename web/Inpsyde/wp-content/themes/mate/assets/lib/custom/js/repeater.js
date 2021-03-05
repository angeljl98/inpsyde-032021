jQuery(document).ready(function($) {

    // Show Title Sections While Loadiong.
    $('.mate-repeater-field-control').each(function(){

        var title = $(this).find('.home-section-type option:selected').text();
        $(this).find('.mate-repeater-field-title').text(title);
        var title_key = $(this).find('.home-section-type option:selected').val();

        $(this).find('.home-repeater-fields-hs').hide();
        $(this).find('.'+title_key+'-fields').show();

        $(this).find('.home-section-type select option').each(function(){

            var title_key_2 = $(this).val();
            if( title_key != title_key_2 ){

                $(this).remove();

            }
        });
       
    });

    // Show Title After Secect Section Type.
    $('.home-section-type select').change(function(){

        var optionSelected = $("option:selected", this);
        var textSelected   = optionSelected.text();
        var title_key = optionSelected.val();

        $(this).closest('.mate-repeater-field-control').find('.home-repeater-fields-hs').hide();
        $(this).closest('.mate-repeater-field-control').find('.'+title_key+'-fields').show();
        $(this).closest('.mate-repeater-field-control').find('.mate-repeater-field-title').text( textSelected );

    });

    // Save Value.
    function mate_refresh_repeater_values(){

        $(".mate-repeater-field-control-wrap").each(function(){
            
            var values = []; 
            var $this = $(this);
            
            $this.find(".mate-repeater-field-control").each(function(){
            var valueToPush = {};

            $(this).find('[data-name]').each(function(){
                var dataName = $(this).attr('data-name');
                var dataValue = $(this).val();
                valueToPush[dataName] = dataValue;
            });

            values.push(valueToPush);
            });

            $this.next('.mate-repeater-collector').val( JSON.stringify( values ) ).trigger('change');
        });

    }
    var appenditem = $(".mate-repeater-field-control:first").html();

    /*jshint -W065 */
    $('.twp-select-customizer select').each(function(){
        $(this).selectize();
    });

    $("body").on("click",'.mate-add-control-field', function(){

        var $this = $(this).parent();
        if(typeof $this != 'undefined') {

            var field = $('.mate-repeater-field-control-wrap').append('<li class="mate-repeater-field-control ui-sortable-handle twp-sortable-active extended">'+appenditem+'</li>');

            if(typeof field != 'undefined'){

                mate_refresh_repeater_values();
            }

            // Show Title After Secect Section Type.
            $('.home-section-type select').change(function(){
                var optionSelected = $("option:selected", this);
                var textSelected   = optionSelected.text();
                var title_key = optionSelected.val();

                $(this).closest('.mate-repeater-field-control').find('.home-repeater-fields-hs').hide();
                $(this).closest('.mate-repeater-field-control').find('.'+title_key+'-fields').show();

                $(this).closest('.mate-repeater-field-control').find('.mate-repeater-field-title').text(textSelected);

            });

            $('.mate-repeater-field-control-wrap li:last-child').find('.home-repeater-fields-hs').hide();
            $('.mate-repeater-field-control-wrap li:last-child').find('.slide-banner-fields').show();

            $('.mate-repeater-field-control-wrap li').removeClass('twp-sortable-active');
            $('.mate-repeater-field-control-wrap li:last-child').addClass('twp-sortable-active');
            $('.mate-repeater-field-control-wrap li:last-child .mate-repeater-fields').addClass('twp-sortable-active extended');
            $('.mate-repeater-field-control-wrap li:last-child .mate-repeater-fields').show();

            $('.mate-repeater-field-control.twp-sortable-active .title-rep-wrap').click(function(){
                $(this).next('.mate-repeater-fields').slideToggle();
            }); 

            field.find('.customizer-color-picker').each(function(){

                if( $(this).closest('.mate-repeater-field-control').hasClass('twp-sortable-active') ){
                    
                    $(this).closest('.mate-repeater-field-control').find('.wp-picker-container').addClass('old-one');
                    $(this).closest('.mate-repeater-field-control').find('.mate-type-colorpicker .description.customize-control-description').after('<input data-default="" class="customizer-color-picker" data-alpha="true" data-name="category_color" type="text" value="#d0021b">');
                    
                    $(this).closest('.mate-repeater-field-control').find('.customizer-color-picker').wpColorPicker({
                        defaultColor: '#d0021b',
                        change: function(event, ui){
                            setTimeout(function(){
                            mate_refresh_repeater_values();
                            }, 100);
                        }
                    }).parents('.customizer-type-colorpicker').find('.wp-color-result').first().remove();

                    $(this).closest('.mate-repeater-field-control').find('.old-one').remove();

                }
            });

            $('.mate-repeater-field-control-wrap li:last-child .mate-repeater-field-title').text(mate_repeater.new_section);
            $this.find(".mate-repeater-field-control:last .home-section-type select").empty().append( mate_repeater.optionns);

            /*jshint -W065 */
            $('.twp-sortable-active .twp-select-customizer select').each(function(){
                $(this).selectize();
            });

        }
        return false;
    });
    
    $('.mate-repeater-field-control .title-rep-wrap').click(function(){
        $(this).next('.mate-repeater-fields').slideToggle().toggleClass('extended');
    });

    //MultiCheck box Control JS
    $( 'body' ).on( 'change', '.mate-type-multicategory input[type="checkbox"]' , function() {
        var checkbox_values = $( this ).parents( '.mate-type-multicategory' ).find( 'input[type="checkbox"]:checked' ).map(function(){
            return $( this ).val();
        }).get().join( ',' );
        $( this ).parents( '.mate-type-multicategory' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        mate_refresh_repeater_values();
    });

    //Checkbox Multiple Control
    $( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on( 'change', function() {
        checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
            function() {
                return this.value;
            }
        ).get().join( ',' );

        $( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
    });

    // ADD IMAGE LINK
    $('.customize-control-repeater').on( 'click', '.twp-img-upload-button', function( event ){
        event.preventDefault();

        var imgContainer = $(this).closest('.twp-img-fields-wrap').find( '.thumbnail-image'),
        placeholder = $(this).closest('.twp-img-fields-wrap').find( '.placeholder'),
        imgIdInput = $(this).siblings('.upload-id');

        // Create a new media frame
        frame = wp.media({
            title: mate_repeater.upload_image,
            button: {
            text: mate_repeater.use_imahe
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        frame.on( 'select', function() {

        // Get media attachment details from the frame state
        var attachment = frame.state().get('selection').first().toJSON();

        // Send the attachment URL to our custom image input field.
        imgContainer.html( '<img src="'+attachment.url+'" style="max-width:100%;"/>' );
        placeholder.addClass('hidden');

        // Send the attachment id to our hidden input
        imgIdInput.val( attachment.url ).trigger('change');

        });

        // Finally, open the modal on click
        frame.open();
    });
    // DELETE IMAGE LINK
    $('.customize-control-repeater').on( 'click', '.twp-img-delete-button', function( event ){

        event.preventDefault();
        var imgContainer = $(this).closest('.twp-img-fields-wrap').find( '.thumbnail-image'),
        placeholder = $(this).closest('.twp-img-fields-wrap').find( '.placeholder'),
        imgIdInput = $(this).siblings('.upload-id');

        // Clear out the preview image
        imgContainer.find('img').remove();
        placeholder.removeClass('hidden');

        // Delete the image id from the hidden input
        imgIdInput.val( '' ).trigger('change');

    });

    // Color Picker
    $('.customizer-color-picker').each(function(){
        $(this).wpColorPicker({
            defaultColor: '#d0021b',
            change: function(event, ui){
                setTimeout(function(){
                mate_refresh_repeater_values();
                }, 100);
            }
        }).parents('.customizer-type-colorpicker').find('.wp-color-result').first().remove();
    });

    $("#customize-theme-controls").on("click", ".mate-repeater-field-remove",function(){
        if( typeof  $(this).parent() != 'undefined'){
            $(this).closest('.mate-repeater-field-control').slideUp('normal', function(){
                $(this).remove();
                mate_refresh_repeater_values();
            });
            
        }
        return false;
    });

    $('#customize-theme-controls').on('click', '.mate-repeater-field-close', function(){
        $(this).closest('.mate-repeater-fields').slideUp();
        $(this).closest('.mate-repeater-field-control').toggleClass('expanded');
    });

    /*Drag and drop to change order*/
    $(".mate-repeater-field-control-wrap").sortable({
        axis: 'y',
        orientation: "vertical",
        update: function( event, ui ) {
            mate_refresh_repeater_values();
        }
    });

    $("#customize-theme-controls").on('keyup change', '[data-name]',function(){
         mate_refresh_repeater_values();
         return false;
    });

    $("#customize-theme-controls").on('change', 'input[type="checkbox"][data-name]',function(){
        if($(this).is(":checked")){
            $(this).val('yes');
        }else{
            $(this).val('no');
        }
        mate_refresh_repeater_values();
        return false;
    });

});

