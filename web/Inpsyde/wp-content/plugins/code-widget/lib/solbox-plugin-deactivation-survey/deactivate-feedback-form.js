(function($) {
	let solbox = {};
	
	// if(solbox.DeactivateFeedbackForm)
	// 	return;
	
	solbox.DeactivateFeedbackForm = function(plugin)
	{
		var self = this;
		var strings = solbox_deactivate_feedback_form_strings;
		
		this.plugin = plugin;
		
		// Dialog HTML
		var element = $('\
			<div class="solbox-deactivate-dialog" data-remodal-id="' + plugin.slug + '">\
				<form>\
					<input type="hidden" name="plugin"/>\
					<input type="hidden" name="action" value="cw_deactivation_feedback" />\
					<input type="hidden" name="security" value="' + plugin.security + '" />\
					<h2>' + strings.quick_feedback + '</h2>\
					<p class="deactivate-message">\
						' + strings.foreword + '\
					</p>\
					<ul class="solbox-deactivate-reasons"></ul>\
					<input type="text" name="comments" class="others-comments" placeholder="' + strings.brief_description + '" placeholder="kindly tell us the reason so we can improve?">\
					<p class="solbox-deactivate-dialog-buttons">\
						<a href="#/" class="skip confirm" >'+ strings.skip_and_deactivate +'</a>\
						<input type="submit" class="button confirm"  disabled value="' + strings.submit_and_deactivate + '"/>\
						<button data-remodal-action="cancel" class="button button-primary">' + strings.cancel + '</button>\
					</p>\
				</form>\
			</div>\
		')[0];
		this.element = element;
		
		$(element).find("input[name='plugin']").val(JSON.stringify(plugin));
		
		$(element).on("click", "input[name='reason']", function(event) {

			if( 'found-better-plugin' == $(this).val() ){
				$(element).find('.plugin-name').show();
			}else{ 
				$(element).find('.plugin-name').hide();
			}

			if( 'other' == $(this).val() ){
				$(element).find('.others-comments').show();
			}else{ 
				$(element).find('.others-comments').hide();
			}

			$(element).find("input[type='submit']").prop( 'disabled', false );
			
		});
		$(element).on( "click", ".skip", function(e){
			e.preventDefault();
			$(element).find("form").submit();
		});
		
		$(element).find("form").on("submit", function(event) {
			self.onSubmit(event);
		});
		
		// Reasons list
		var ul = $(element).find("ul.solbox-deactivate-reasons");
		for(var key in plugin.reasons)
		{
			var li = $("<li><input type='radio' name='reason'/> <span></span></li>");
			
			
			$(li).find("span").html(plugin.reasons[key]);
			$(li).find("input").val(key);
			if( 'found-better-plugin' == key ) {
				$(li).append( "<input type='text' class='plugin-name' name='plugin-name' placeholder='" + plugin.reasons['share-plugin-name'] +"' >" );
			}
			$(ul).append(li);
		}
		
		// Listen for deactivate
		$("#the-list [data-slug='" + plugin.slug + "'] .deactivate>a").on("click", function(event) {
			self.onDeactivateClicked(event);
		});
	}
	
	solbox.DeactivateFeedbackForm.prototype.onDeactivateClicked = function(event)
	{
		this.deactivateURL = event.target.href;
		
		event.preventDefault();
		
		if(!this.dialog)
			this.dialog = $(this.element).remodal();
		this.dialog.open();
	}
	
	solbox.DeactivateFeedbackForm.prototype.onSubmit = function(event)
	{
		var element = this.element;
		var strings = solbox_deactivate_feedback_form_strings;
		var self = this;
		var data = $(element).find("form").serialize();

		
		$(element).find("button, input[type='submit']").prop("disabled", true);
		
		if($(element).find("input[name='reason']:checked").length)
		{
			$(element).find("input[type='submit']").val(strings.thank_you);
			
			$.ajax({
				type: "POST",
				url:  ajaxurl,
				data: data,
				complete:	function() {
					
					window.location.href = self.deactivateURL;
				}
			});
		}
		else
		{
			$(element).find("input[type='submit']").val(strings.please_wait);
			window.location.href = self.deactivateURL;
		}
		
		event.preventDefault();
		return false;
	}
	
	$(document).ready(function() {

		for(var i = 0; i < solbox_deactivate_feedback_form_cw.length; i++)
		{
			var plugin = solbox_deactivate_feedback_form_cw[i];
			new solbox.DeactivateFeedbackForm(plugin);
		}
		
	});
	
})(jQuery);
