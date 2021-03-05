<?php
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-tooltip');
wp_enqueue_script('jquery-ui-dialog');
?>
<script language="javascript" src="<?php print $this->PO->urlPath; ?>/js/validation.js"></script>
<script language="javascript" type="text/javascript">
	var tmpObjectCount = 0;
	<?php
	global $wpdb;
	if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'PO_global_plugins') {
		$storedPluginLists = array();
	} else {
		$sql = "SELECT disabled_plugins, disabled_mobile_plugins, disabled_groups, disabled_mobile_groups FROM ".$wpdb->prefix."po_plugins WHERE post_type='global_plugin_lists' AND post_id=0";
		$storedPluginLists = $wpdb->get_row($sql, ARRAY_A);
	}
	$globalPluginLists = array(
		'std_plugins'=>(is_array(@unserialize($storedPluginLists['disabled_plugins'])))? @unserialize($storedPluginLists['disabled_plugins']):array(), 
		'mobile_plugins'=>(is_array(@unserialize($storedPluginLists['disabled_mobile_plugins'])))? @unserialize($storedPluginLists['disabled_mobile_plugins']):array(),
		'std_groups'=>(is_array(@unserialize($storedPluginLists['disabled_groups'])))? @unserialize($storedPluginLists['disabled_groups']):array(),
		'mobile_groups'=>(is_array(@unserialize($storedPluginLists['disabled_mobile_groups'])))? @unserialize($storedPluginLists['disabled_mobile_groups']):array()
	);
	?>
	var globalPlugins = <?php print json_encode($globalPluginLists); ?>;

	var toggleButtonOptions = [['Off','On'], ['No','Yes']];
	
	jQuery(function() {
		PO_attach_help_dialog();
		jQuery('#post').submit(function(e) {
			jQuery('.PO-permalink-input').removeClass('badInput');
			jQuery('.PO-permalink-input').each(function() {
				var thisPermalinkInput = jQuery(this);
				var thisPermalinkVal = jQuery(this).val();
				var thisPermalinkName = jQuery(this).prop('name');
				jQuery('.PO-permalink-input').each(function() {
					if (jQuery(this).prop('name') != thisPermalinkName && jQuery(this).val() == thisPermalinkVal) {
						jQuery(this).addClass('badInput');
						thisPermalinkInput.addClass('badInput');
						e.preventDefault();
						PO_display_ui_dialog("Duplicate Permalinks", "You have 2 or more permalinks that are the same.  Each permalink must be unique.")
					}
				});

			});
		});
		
		jQuery('#PO-activate-pt-override').change(function() {
			PO_activate_pt_override();
		});

		jQuery('#PO-pt-override').change(function() {
			PO_deactivate_pt_override();
		});
		
		PO_set_expand_info_action();
		
		PO_attach_ui_handlers();

		jQuery('#PO-ui-notices').dialog({
			dialogClass: 'PO-ui-dialog',
			closeText: 'X',
			autoOpen: false,
			resizable: false,
			height: "auto",
			width: (jQuery(window).width() > 400)?'400':jQuery(window).width()-20,
			modal: true,
			position: {within: '.PO-content-wrap'},
			buttons: {
				"Ok": function() {
					jQuery(this).dialog("close");
				}
			},
			open: function(event, ui) {
				jQuery('.ui-widget-overlay.ui-front').css('position', 'fixed');
				jQuery('.ui-widget-overlay.ui-front').css('left', '0px');
				jQuery('.ui-widget-overlay.ui-front').css('right', '0px');
				jQuery('.ui-widget-overlay.ui-front').css('top', '0px');
				jQuery('.ui-widget-overlay.ui-front').css('bottom', '0px');
				jQuery('.ui-widget-overlay.ui-front').css('background', '#000');
				jQuery('.ui-widget-overlay.ui-front').css('opacity', '.5');
				jQuery('.ui-widget-overlay.ui-front').css('zIndex', '9998');
			}
		});

		jQuery('#PO-add-permalink').click(function() {
			PO_add_permalink();
		});

		jQuery('#PO-disable-all-plugins').click(function() {
			PO_add_all('plugin', jQuery('#PO-available-platforms').val(), jQuery('#PO-available-roles').val());
			PO_mark_disabled_plugins();
		});

		jQuery('#PO-enable-all-plugins').click(function() {
			PO_remove_all('plugin', jQuery('#PO-available-platforms').val(), jQuery('#PO-available-roles').val());
			PO_mark_disabled_plugins();
		});

		jQuery('#PO-disable-all-groups').click(function() {
			PO_add_all('group', jQuery('#PO-available-platforms').val(), jQuery('#PO-available-roles').val());
			PO_mark_disabled_plugins();
		});

		jQuery('#PO-enable-all-groups').click(function() {
			PO_remove_all('group', jQuery('#PO-available-platforms').val(), jQuery('#PO-available-roles').val());
			PO_mark_disabled_plugins();
		});

		jQuery('#PO-display-plugin-overview').click(function() {
			var overviewContent = '<h3 class="PO-plugin-overview-header">Disabled Plugins &amp; Groups</h3>';
			overviewContent += '<ul class="plugin-overview-list">'
			var availablePlatforms = new Array();
			if (jQuery('#PO-available-platforms option').length > 0) {
				jQuery('#PO-available-platforms option').each(function() {
					availablePlatforms[jQuery(this).text()] = jQuery(this).val();
				});
			} else {
				availablePlatforms['Standard']='std';
			}

			var availableRoles = new Array();
			if (jQuery('#PO-available-roles option').length > 0) {
				jQuery('#PO-available-roles option').each(function() {
					availableRoles[jQuery(this).text()] = jQuery(this).val();
				});
			} else {
				availableRoles['All Users']='_';
			}
			
			for (var pKey in availablePlatforms) {
				var platform = availablePlatforms[pKey];
				overviewContent += '<li>'+pKey+'<ul class="plugin-overview-platform-list">';
				for (var rKey in availableRoles) {
					overviewContent += '<li>'+rKey+'<ul class="plugin-overview-role-list">';
					var role = availableRoles[rKey];
					var disabledItems = new Array();
					jQuery('.PO-disabled-'+platform+'-plugin-list').each(function() {
						if (jQuery(this).prop('name') == 'PO_disabled_'+platform+'_plugin_list['+role+'][]') {
							disabledItems.push(jQuery(this).val());
						}
					});
					overviewContent += '<li>Plugins<ul class="plugin-overview-plugin-list">';
					jQuery('.plugin-wrap').each(function() {
						if (jQuery.inArray(jQuery(this).find('.PO-plugin-id').val(), disabledItems) != -1) {
							overviewContent += '<li>'+jQuery(this).find('.PO-plugin-name').html()+'</li>';
						}
					});
					overviewContent += '</ul></li>';
					
					disabledItems = new Array();
					jQuery('.PO-disabled-'+platform+'-group-list').each(function() {
						if (jQuery(this).prop('name') == 'PO_disabled_'+platform+'_group_list['+role+'][]') {
							disabledItems.push(jQuery(this).val());
						}
					});
					overviewContent += '<li>Groups<ul class="plugin-overview-group-list">';
					jQuery('.group-wrap').each(function() {
						if (jQuery.inArray(jQuery(this).find('.PO-group-id').val(), disabledItems) != -1) {
							overviewContent += '<li>'+jQuery(this).find('.PO-group-members').html()+'</li>';
						}
					});
					overviewContent += '</ul></li>';
					
					overviewContent += '</ul>';
				}
				overviewContent += '</ul></li>';
				
			}
			overviewContent += '</ul>';
			
			PO_display_ui_dialog('Plugin Overview', overviewContent);
		});

		jQuery('.plugin-name-container, .group-name-container').on('click.pluginOrganizer', function() {
			var platform = jQuery('#PO-available-platforms').val();
			var role = jQuery('#PO-available-roles').val();
			var itemType = 'plugin';
			if (jQuery(this).hasClass('group-name-container')) {
				itemType = 'group';
			}
			var itemID = jQuery(this).find('.PO-'+itemType+'-id').val();
			if (jQuery(this).closest('.'+itemType+'-wrap').hasClass('disabled') || jQuery(this).closest('.'+itemType+'-wrap').hasClass('global-disabled')) {
				jQuery('.PO-disabled-'+platform+'-'+itemType+'-list').each(function() {
					if (jQuery(this).prop('name') == 'PO_disabled_'+platform+'_plugin_list['+role+'][]' && jQuery(this).val() == itemID) {
						jQuery(this).remove();
					}
				});
				jQuery(this).closest('.'+itemType+'-wrap').removeClass('global-disabled');
				jQuery(this).closest('.'+itemType+'-wrap').removeClass('disabled');
			} else {
				jQuery('#hidden-plugin-lists-container').append('<input type="hidden" class="PO-disabled-'+platform+'-'+itemType+'-list" name="PO_disabled_'+platform+'_'+itemType+'_list['+role+'][]" value="'+itemID+'">');
				if (jQuery.inArray(jQuery(this).find('.PO-'+itemType+'-id').val(), globalPlugins[platform+'_plugins']) != -1) {
					jQuery(this).closest('.'+itemType+'-wrap').addClass('global-disabled');
					jQuery(this).closest('.'+itemType+'-wrap').removeClass('disabled');
				} else {
					jQuery(this).closest('.'+itemType+'-wrap').removeClass('global-disabled');
					jQuery(this).closest('.'+itemType+'-wrap').addClass('disabled');
				}
			}
		});

		jQuery('#PO-available-platforms').on('change.pluginOrganizer', function() {
			PO_mark_disabled_plugins();
		});

		jQuery('#PO-available-roles').on('change.pluginOrganizer', function() {
			PO_mark_disabled_plugins();
		});

		PO_mark_disabled_plugins();
	});
	
	function PO_attach_help_dialog() {
		jQuery('.PO-help-dialog').tooltip({
			content: function() {
				return PO_format_tooltip(jQuery(this).attr('title'));
			},
			show: {
				effect: "slideDown",
				delay: 50
			},
			hide: {
				effect: "slideUp",
				delay: 50
			},
			tooltipClass: "PO-ui-tooltip"
		});
	}
	
	function PO_format_tooltip(tooltipTxt) {
		var formattedTxt = tooltipTxt.replace(/__ts__/g, '<div class="tooltip-title">');
		formattedTxt = formattedTxt.replace(/__rs__/g, '<div class="tooltip-row">');
		return formattedTxt.replace(/(__te__|__re__)/g, '</div>');
	}
	
	function PO_add_permalink() {
		jQuery('#PO-permalink-container').append('<div class="PO-permalink-wrapper"><input type="hidden" name="PO_pl_id[]" value="tmp_'+tmpObjectCount+'"><input type="text" class="PO-permalink-input" size="25" name="PO_permalink_filter_tmp_'+tmpObjectCount+'" value=""><input type="button" class="PO-delete-permalink" value="X"></div>');
		tmpObjectCount++;
		PO_attach_ui_handlers();
	}

	function PO_display_ui_dialog(dialogTitle, dialogText) {
		jQuery('.PO-ui-dialog .ui-dialog-title').html(dialogTitle);
		jQuery('#PO-ajax-notices-container').html(dialogText);
		jQuery('#PO-ui-notices').dialog('open');
	}
	
	function PO_attach_ui_handlers() {
		jQuery('.PO-permalink-input').off('keyup.pluginOrganizer');
		jQuery('.PO-permalink-input').on('keyup.pluginOrganizer', function() {
			if ((jQuery(this).val().match(/\*/g) || []).length > 1) {
				PO_display_ui_dialog('Warning', 'Using more than one wildcard in a permalink is not supported. You must remove all of the wildcards except one or it will never match anything.');
			}
		});
		
		jQuery('.group-wrap').tooltip({
			content: function() {
				return PO_format_tooltip(jQuery(this).attr('title'));
			},
			show: {
				effect: "slideDown",
				delay: 50
			},
			hide: {
				effect: "slideUp",
				delay: 50
			},
			tooltipClass: "PO-ui-tooltip"
		});

		jQuery('.PO-delete-permalink').off('click.pluginOrganizer');
		jQuery('.PO-delete-permalink').on('click.pluginOrganizer', function() {
			jQuery(this).closest('.PO-permalink-wrapper').remove();
		});
	}
	
	function PO_add_all(itemType, platform, role) {
		PO_remove_all(itemType, platform, role);
		jQuery('.PO-'+itemType+'-id').each(function() {
			jQuery('#hidden-plugin-lists-container').append('<input type="hidden" class="PO-disabled-'+platform+'-'+itemType+'-list" name="PO_disabled_'+platform+'_'+itemType+'_list['+role+'][]" value="'+jQuery(this).val()+'">');
		});
	}

	function PO_remove_all(itemType, platform, role) {
		jQuery('.PO-disabled-'+platform+'-'+itemType+'-list').each(function() {
			if (jQuery(this).prop('name') == 'PO_disabled_'+platform+'_'+itemType+'_list['+role+'][]') {
				jQuery(this).remove();
			}
		});
	}
	
	function PO_mark_disabled_plugins() {
		var platform = jQuery('#PO-available-platforms').val();
		var role = jQuery('#PO-available-roles').val();
		var itemType = new Array('plugin', 'group');
		for (var i=0; i<itemType.length; i++) {
			var disabledItems = new Array();
			jQuery('.PO-disabled-'+platform+'-'+itemType[i]+'-list').each(function() {
				if (jQuery(this).prop('name') == 'PO_disabled_'+platform+'_'+itemType[i]+'_list['+role+'][]') {
					disabledItems.push(jQuery(this).val());
				}
			});
			
			jQuery('.'+itemType[i]+'-wrap').each(function() {
				if (jQuery.inArray(jQuery(this).find('.PO-'+itemType[i]+'-id').val(), disabledItems) != -1) {
					if (jQuery.inArray(jQuery(this).find('.PO-'+itemType[i]+'-id').val(), globalPlugins[platform+'_'+itemType[i]+'s']) != -1) {
						jQuery(this).addClass('global-disabled');
						jQuery(this).removeClass('disabled');
					} else {
						jQuery(this).removeClass('global-disabled');
						jQuery(this).addClass('disabled');
					}
				} else {
					jQuery(this).removeClass('global-disabled');
					jQuery(this).removeClass('disabled');
				}
			});
		}
	}
	
	
	function PO_activate_pt_override() {
		jQuery('#PO-pt-override-msg-container').hide();
		jQuery('#PO-post-meta-box-wrapper').show();
		jQuery('#PO-pt-override').prop('checked', true);
	}

	function PO_deactivate_pt_override() {
		if (jQuery('#PO-activate-pt-override').prop('checked')) {
			jQuery('#PO-pt-override-msg-container').show();
			jQuery('#PO-post-meta-box-wrapper').hide();
			jQuery('#PO-pt-override').prop('checked', false);
			jQuery('#PO-activate-pt-override').prop('checked', false);
		}
	}
	
	function PO_set_expand_info_action() {
		jQuery('.expand-info-icon').each(function() {
			jQuery(this).unbind();
			var targetID = jQuery(this).prop('id').replace('PO-expand-info-', '');
			var infoContainer = jQuery('#PO-info-container-' + targetID);
			if (!jQuery(infoContainer).find('.PO-info-inner').html().match(/^\s*$/)) {
				jQuery(this).click(function() {
					if (jQuery(this).hasClass('fa-plus-square-o')) {
						jQuery(this).removeClass('fa-plus-square-o');
						jQuery(this).addClass('fa-minus-square-o');
						infoContainer.slideDown(300);
					} else {
						jQuery(this).removeClass('fa-minus-square-o');
						jQuery(this).addClass('fa-plus-square-o');
						infoContainer.slideUp(300);
					}
				});
			}
		});
	}
	
	function PO_toggle_loading(container) {
		jQuery(container+' .PO-loading-container').toggle();
		jQuery(container+' .inside').toggle();
	}

	function PO_toggle_button(checkboxID, buttonPrefix, optionIndex) {
		if (jQuery('#'+checkboxID).prop('checked') == false) {
			PO_set_button(jQuery('#'+checkboxID), 1, buttonPrefix, optionIndex);
		} else {
			PO_set_button(jQuery('#'+checkboxID), 0, buttonPrefix, optionIndex);
		}
	}
	
	function PO_set_button(checkbox, onOff, buttonPrefix, optionIndex) {
		if (onOff == 1) {
			jQuery(checkbox).prop('checked', true);
		} else {
			jQuery(checkbox).prop('checked', false);
		}
		jQuery(checkbox).parent().find("input[type='button']").removeClass();
		jQuery(checkbox).parent().find("input[type='button']").addClass(buttonPrefix+'toggle-button-'+toggleButtonOptions[optionIndex][onOff].toLowerCase());
		jQuery(checkbox).parent().find("input[type='button']").attr('value',toggleButtonOptions[optionIndex][onOff]);
	}
	
	function PO_reset_post_settings(postID) {
		PO_toggle_loading('#PO-meta-wrap');
		jQuery.post(encodeURI(ajaxurl + '?action=PO_reset_post_settings'), { 'postID': postID, PO_nonce: '<?php print $this->PO->nonce; ?>' }, function (result) {
			try {
				var responseObj = jQuery.parseJSON(result);
			} catch(err) {
				var responseObj = {'success': 0, 'alerts': ['There was an issue removing the settings.']};
			}
			
			if (responseObj['success'] == '1') {
				PO_display_ui_dialog('Submission Result', 'The settings were successfully reset.');
				location.reload(true);
			} else {
				var submissionAlerts = '';
				for (var i=0; i<responseObj['alerts'].length; i++) {
					if (submissionAlerts != '') {
						submissionAlerts += '<br />';
					}
					submissionAlerts += responseObj['alerts'][i];
				}
				PO_toggle_loading('#PO-meta-wrap');
				PO_display_ui_dialog('Submission Result', submissionAlerts);
			}
		});
	}

	function PO_submit_ajax(action, postVars, container, callback) {
		PO_toggle_loading(container);
		jQuery.post(encodeURI(ajaxurl + '?action='+action), postVars, function (result) {
			try {
				var responseObj = jQuery.parseJSON(result);
			} catch(err) {
				var responseObj = {'success': 0, 'alerts': ['There was an issue saving the changes you made']};
			}


			PO_toggle_loading(container);
			if (responseObj['alerts'].length > 0) {
				var submissionAlerts = '';
				for (var i=0; i<responseObj['alerts'].length; i++) {
					if (submissionAlerts != '') {
						submissionAlerts += '<br />';
					}
					submissionAlerts += responseObj['alerts'][i];
				}
				PO_display_ui_dialog('Submission Result', submissionAlerts);
			}
			
			if (typeof(callback) == 'function') {
				callback(responseObj);
			}
		});
	}
	
	<?php
	print "var regex = new Array();\n";
	foreach ($this->PO->regex as $key=>$val) {
		print "regex['$key'] = $val;\n";
	}
	?>
</script>