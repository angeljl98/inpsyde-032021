<?php
global $wpdb;
if ( current_user_can( 'activate_plugins' ) ) {
	?>
	<script type="text/javascript" language="javascript">
		var loopCount=0;
		var disabledListForSubmit = {};
		var disabledMobileListForSubmit = {};
		var disabledGroupListForSubmit = {};
		var disabledMobileGroupListForSubmit = {};
		function PO_submit_pt_plugins(total, offset){
			if (total == 0 && offset == 0) {
				PO_toggle_loading('#PO-pt-settings');
			}
			
			var selectedPostType = jQuery('select#PO-selected-post-type').val();
			if (offset == 0) {
				disabledListForSubmit = {};
				disabledMobileListForSubmit = {};
				disabledGroupListForSubmit = {};
				disabledMobileGroupListForSubmit = {};

				jQuery('.PO-disabled-std-plugin-list').each(function() {
					var roleName = jQuery(this).prop('name').replace('PO_disabled_std_plugin_list[', '').replace('][]', '');
					if (typeof(disabledListForSubmit[roleName]) == 'undefined' || disabledListForSubmit[roleName].constructor != Array) {
						disabledListForSubmit[roleName] = new Array();
					}
					disabledListForSubmit[roleName].push(jQuery(this).val());
				});
				
				jQuery('.PO-disabled-mobile-plugin-list').each(function() {
					var roleName = jQuery(this).prop('name').replace('PO_disabled_mobile_plugin_list[', '').replace('][]', '');
					if (typeof(disabledMobileListForSubmit[roleName]) == 'undefined' || disabledMobileListForSubmit[roleName].constructor != Array) {
						disabledMobileListForSubmit[roleName] = new Array();
					}
					disabledMobileListForSubmit[roleName].push(jQuery(this).val());
				});
				
				jQuery('.PO-disabled-std-group-list').each(function() {
					var roleName = jQuery(this).prop('name').replace('PO_disabled_std_group_list[', '').replace('][]', '');
					if (typeof(disabledGroupListForSubmit[roleName]) == 'undefined' || disabledGroupListForSubmit[roleName].constructor != Array) {
						disabledGroupListForSubmit[roleName] = new Array();
					}
					disabledGroupListForSubmit[roleName].push(jQuery(this).val());
				});
				
				jQuery('.PO-disabled-mobile-group-list').each(function() {
					var roleName = jQuery(this).prop('name').replace('PO_disabled_mobile_group_list[', '').replace('][]', '');
					if (typeof(disabledMobileGroupListForSubmit[roleName]) == 'undefined' || disabledMobileGroupListForSubmit[roleName].constructor != Array) {
						disabledMobileGroupListForSubmit[roleName] = new Array();
					}
					disabledMobileGroupListForSubmit[roleName].push(jQuery(this).val());
				});
			}
			
			var postVars = { 'PO_disabled_std_plugin_list': disabledListForSubmit, 'PO_disabled_mobile_plugin_list': disabledMobileListForSubmit, 'PO_disabled_std_group_list': disabledGroupListForSubmit, 'PO_disabled_mobile_group_list': disabledMobileGroupListForSubmit, 'selectedPostType': selectedPostType, 'PO_total_post_count': total, 'PO_post_offset': offset, PO_nonce: '<?php print $this->PO->nonce; ?>' };
			jQuery.post(encodeURI(ajaxurl + '?action=PO_save_pt_plugins'), postVars, function (result) {
				var parsedResult = jQuery.parseJSON(result);
				if (parsedResult['success'] == '1') {
					if (parseInt(parsedResult['total'], 10) > parseInt(parsedResult['offset'], 10) + 100) {
						jQuery('#PO-progress-message').html('Still Working: ' + (parseInt(parsedResult['offset'], 10) + 100) + ' ' + selectedPostType + 's have been processed.  There are still ' + (parseInt(parsedResult['total'], 10) - (parseInt(parsedResult['offset'], 10) + 100)) + ' left.');	
						loopCount++;
						PO_submit_pt_plugins(parsedResult['total'], parseInt(parsedResult['offset'], 10) + 100);
					} else {
						PO_toggle_loading('#PO-pt-settings');
						PO_display_ui_dialog('Submission Result', parsedResult['msg']);
						jQuery('#PO-progress-message').html('');
						loopCount=0;
					}
				} else {
					PO_toggle_loading('#PO-pt-settings');
					PO_display_ui_dialog('Submission Result', parsedResult['msg']);
				}
			});
		}
		
		function PO_add_saved_items(sourceType, targetType, targetRole, values) {
			jQuery('#hidden-plugin-lists-container').each(function() {
				if (jQuery(this).prop('name') == 'PO_disabled_'+targetType+'_'+sourceType+'_list['+targetRole+'][]') {
					jQuery(this).remove();
				}
			});
			
			jQuery('#PO-all-'+sourceType+'-wrap .'+sourceType+'-wrap').each(function() {
				var itemID = jQuery(this).find('.PO-'+sourceType+'-id').val();
				if (jQuery.inArray(itemID, values[0]) > -1 || (jQuery.inArray(itemID, values[2]) > -1 && jQuery.inArray(itemID, values[1]) == -1)) {
					jQuery('#hidden-plugin-lists-container').append('<input type="hidden" class="PO-disabled-'+targetType+'-'+sourceType+'-list" name="PO_disabled_'+targetType+'_'+sourceType+'_list['+targetRole+'][]" value="'+itemID+'">');
				}
			});
		}
		
		function PO_get_pt_plugins() {
			var selectedPostType = jQuery('select#PO-selected-post-type').val();
			PO_toggle_loading('#PO-pt-settings');
			jQuery.post(encodeURI(ajaxurl + '?action=PO_get_pt_plugins'), {'selectedPostType': selectedPostType, PO_nonce: '<?php print $this->PO->nonce; ?>' }, function (result) {
				if (result == 'post_type_not_supported') {
					PO_display_ui_dialog('Error', 'There was an error retrieving the list of disabled/enabled plugins');
				} else {
					var pluginLists = jQuery.parseJSON(result);
					//Remove current disabled lists
					jQuery('.PO-disabled-std-plugin-list, .PO-disabled-mobile-plugin-list, .PO-disabled-std-group-list, .PO-disabled-mobile-group-list').remove();
					for (var key in pluginLists) {
						PO_add_saved_items('plugin', 'std', key, new Array(pluginLists[key][0], pluginLists[key][1], globalPlugins['std_plugins']));
						PO_add_saved_items('plugin', 'mobile', key, new Array(pluginLists[key][2], pluginLists[key][3], globalPlugins['mobile_plugins']));
						PO_add_saved_items('group', 'std', key, new Array(pluginLists[key][4], pluginLists[key][5], globalPlugins['std_groups']));
						PO_add_saved_items('group', 'mobile', key,  new Array(pluginLists[key][6], pluginLists[key][7], globalPlugins['mobile_groups']));
					}
					
					PO_toggle_loading('#PO-pt-settings');
				}
				PO_mark_disabled_plugins();
			});
		}

		function PO_reset_pt_settings() {
			var selectedPostType = jQuery('select#PO-selected-post-type').val();
			if (confirm('Are you sure you want to reset the enabled/disabled plugins back to default for this post type?')) {
				if (jQuery('#PO-reset-all-pt').prop('checked')) {
					resetAll = 1;
				} else {
					resetAll = 0;
				}
				var postVars = {'selectedPostType': selectedPostType, PO_nonce: '<?php print $this->PO->nonce; ?>', PO_reset_all_pt: resetAll };
				PO_submit_ajax('PO_reset_pt_settings', postVars, '#PO-pt-settings', PO_get_pt_plugins);
			}
		}
		
		jQuery(function() {
			PO_toggle_loading('#PO-pt-settings');
			PO_get_pt_plugins();
			jQuery('#PO-selected-post-type').change(function() {
				PO_get_pt_plugins()
			});
		});
	</script>
	<?php
}
?>