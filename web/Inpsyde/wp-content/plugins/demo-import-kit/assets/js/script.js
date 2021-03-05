jQuery( function ( $ ) {








	var DIK_AdvancedUpload = function() {
	  var div = document.createElement('div');
	  return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
	}();

	var $form = $('.dik-drag-drop');



	if (DIK_AdvancedUpload) {

	  var droppedFiles = false;

		$form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {

			e.preventDefault();
			e.stopPropagation();

		})

		.on('dragover dragenter', function() {

			$form.addClass('is-dragover');

		})

		.on('dragleave dragend drop', function() {

			$form.removeClass('is-dragover');

		})
		.on('drop', function(e) {

			droppedFiles = e.originalEvent.dataTransfer.files;
			$form.find('#twp-content-file-upload').prop('files', droppedFiles);
			

			if ( $('#twp-content-file-upload').length && document.getElementById("twp-content-file-upload").files.length != 0) {
				$('.two-upload-status').empty();
				$('.two-upload-status').text( droppedFiles[0].name );
			}

		});

	}


	$("#twp-content-file-upload").on("change", function(e){

	   if( $("#twp-content-file-upload").val() !== "" ){
	        
	        if ( $('#twp-content-file-upload').length && document.getElementById("twp-content-file-upload").files.length != 0) {

	        	var droppedFiles = $("#twp-content-file-upload").val();
	        	var filename = droppedFiles.replace(/C:\\fakepath\\/i, '');
				$('.two-upload-status').empty();
				$('.two-upload-status').text( filename );
				
			}

	    }

	});







    $('.dik-primary-tab').click(function (){

    	if( !$(this).hasClass('dik-primary-tab-active') ){

    		$('.dik-primary-tab').removeClass('dik-primary-tab-active');
    		$(this).addClass('dik-primary-tab-active');

    		demo_import_kit_tab();

			var PrimaryCat = $(this).attr('ptab-data');
		    var ajaxurl = dik.ajax_url;
		    var data = {
		        'action': 'demo_import_kit_grid_primary_tab',
		        'PrimaryCat': PrimaryCat,
		        '_wpnonce': dik.ajax_nonce ,
		    };

		    $.post(ajaxurl, data, function (response) {

		    	$('.dik-content-wrapper').empty();
		    	$('.dik-content-wrapper').html( response );

		    	$('.dik-rows').imagesLoaded(function () {

			    	var $grid = $('.dik-rows').isotope({
					  // options
					  itemSelector: '.dik-columns',
					  layoutMode: 'fitRows'
					});

			    });

				demo_import_kit_tab();

				$( '.action-import-grid' ).on( 'click', function () {

					$('.dik-content-wrapper').addClass('dik-grid-importing');
					$('.dik-content-wrapper').empty();
					$('.dik-content-wrapper').append('<div><h2>'+ dik.importing_title+'</h2><p>'+ dik.importing_message+'</p><div class="dik-import-loading"></div></div>');
					// Grid import AJAX call
					var data = new FormData();
					data.append( 'action', 'dik_import_demo_data' );
					data.append( 'security', dik.ajax_nonce );
					data.append( 'selected', $( this ).attr('thumbid') );
					data.append( 'demoSlug', $( this ).attr('demo-slug') );
					ajaxCall( data );

				});
							
		    });

		}

    });

	$( '#twp-zip-file-upload' ).on( 'click', function () {
		
		// Prepare data for the AJAX call
		var data = new FormData();
		data.append( 'action', 'dik_import_demo_data' );
		data.append( 'security', dik.ajax_nonce );

		if ( $('#twp-content-file-upload').length && document.getElementById("twp-content-file-upload").files.length != 0) {
			data.append( 'content_file', $('#twp-content-file-upload')[0].files[0] );
		}else{
			alert(dik.required_file);
			return;
		}

		$('.dik-drag-drop').addClass('dik-demo-importing');
		$('.dik-header-wrapper').addClass('dik-demo-importing');
		$( '.dik-drag-drop' ).empty();
		$( '.js-dik-ajax-response' ).empty();
		$('.dik-content-wrapper').empty();
		$('.dik-header-upload').empty();
		$('.dik-drag-drop').append('<div><h2>'+ dik.importing_title+'</h2><p>'+ dik.importing_message+'</p><div class="dik-import-loading"></div></div>');
		$('.dik-header-upload').append('<div><h2>'+ dik.importing_title+'</h2><p>'+ dik.importing_message+'</p><div class="dik-import-loading"></div></div>');
		ajaxCall( data );

	});


	$( '.action-import-grid' ).on( 'click', function () {

		$('.dik-content-wrapper').addClass('dik-grid-importing');
		$('.dik-content-wrapper').empty();
		$('.dik-content-wrapper').append('<div><h2>'+ dik.importing_title+'</h2><p>'+ dik.importing_message+'</p><div class="dik-import-loading"></div></div>');
		
		// Grid import AJAX call
		var data = new FormData();
		data.append( 'action', 'dik_import_demo_data' );
		data.append( 'security', dik.ajax_nonce );
		data.append( 'selected', $( this ).attr('thumbid') );
		data.append( 'demoSlug', $( this ).attr('demo-slug') );
		ajaxCall( data );

	});

	function ajaxCall( data ) {
		
		$.ajax({
			method:     'POST',
			url:        dik.ajax_url,
			data:       data,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$( '.js-dik-ajax-loader' ).show();
			}
		}).done( function( response ) {
			
			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				ajaxCall( data );;
			}else {
				
				$('.dik-content-wrapper').removeClass('dik-grid-importing');
				$('.dik-content-wrapper').removeClass('dik-grid-imported');
				$('.dik-content-wrapper').empty();
				$('.dik-demo-importing .dik-header-upload, .dik-demo-importing.dik-drag-drop').empty();
				$('.dik-demo-importing .dik-header-upload, .dik-demo-importing.dik-drag-drop').append('<div><h2>'+ dik.import_status+'</h2><p>'+ response+'</p><div class="dik-import-loading"></div></div>');

				if( ! $('.dik-header-wrapper, .dik-drag-drop').hasClass('dik-demo-importing') ){

					$('.dik-content-wrapper').append('<div><h2>'+ dik.import_status+'</h2><p>'+ response+'</p><div class="dik-import-loading"></div></div>');
				}

				$('.dik-header-wrapper').removeClass('dik-demo-importing');

			}
		}).fail( function( error ) {

			//$('.dik-header-wrapper').removeClass('dik-demo-importing');
			$('.dik-content-wrapper, .dik-demo-importing.dik-drag-drop').removeClass('dik-grid-importing');
			$('.dik-content-wrapper, .dik-demo-importing.dik-drag-drop').empty();

			$( '.dik-content-wrapper, .dik-demo-importing.dik-drag-drop' ).append( '<div class="notice  notice-error  is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>' );
			
		});
	}

	demo_import_kit_tab();
	demo_import_kit_search();

});

function demo_import_kit_tab(){

	jQuery( function ( $ ) {

		$('.dik-rows').imagesLoaded(function () {

			var $grid = $('.dik-rows').isotope({
			  // options
			  itemSelector: '.dik-columns',
			  layoutMode: 'fitRows'
			});

		});

		$('.dik-tab').click( function() {

			var tabid = $(this).attr('tabid');
	        $('.dik-sidebar-nav a').removeClass('dik-tab-active');
	        $(this).addClass('dik-tab-active');

			$('#dik-search-input').val('');
			var filterValue = $(this).attr('data-filter');
			var filterCurrent = $(this).attr('data-current');
			

			$('.dik-grid-items').each(function(){

				$(this).removeClass('dik-search-filter');

				if( $(this).hasClass(filterCurrent) ){
					$(this).addClass('dik-search-filter');
				}else if( filterCurrent == '*' ){
					$(this).addClass('dik-search-filter');
				}else{

				}

			});

			$('.dik-rows').imagesLoaded(function () {

				var $grid = $('.dik-rows').isotope({
				  // options
				  itemSelector: '.dik-columns',
				  layoutMode: 'fitRows'
				});
				
				$grid.isotope({ filter: filterValue });

				$('.dik-search-filter').each(function(){
					var cStyle = $(this).attr('style');
					cStyle = cStyle.replace('display: none;','');

					$(this).attr('style',cStyle);
				});

			});

			
			$('.dik-rows').imagesLoaded(function () {
				var $grid = $('.dik-rows').isotope({
				  // options
				  itemSelector: '.dik-columns',
				  layoutMode: 'fitRows'
				});
				$grid.isotope({ filter: filterValue });
			});

		});

	});

}

function demo_import_kit_search(){

	jQuery( function ( $ ) {

		$("#dik-search-input").on("keyup", function() {

		    var value = $(this).val().toLowerCase();
		    $(".dik-search-filter").filter(function() {

		      	$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);

		      	$('.dik-rows').imagesLoaded(function () {

			      	var $grid = $('.dik-rows').isotope({
					  	// options
					  	itemSelector: '.dik-columns',
					  	layoutMode: 'fitRows'
					});

			      });

		    });

		});

	});

}


