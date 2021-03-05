<?php 
$adminPage = '';
if (isset($_GET['page'])) {
	$adminPage = $_GET['page'];
}

if ($adminPage == 'PO_global_plugins') {
	$availableRoles = array('_'=>'All Users');
} else {
	$availableRoles = $this->PO->get_available_roles();
}
?>

<script type="text/javascript" language="javascript">
	var PO_role_array = <?php print json_encode($availableRoles); ?>;
</script>

<?php
if (isset($errMsg) && $errMsg != "") {
	?>
	<h3 style="color: #CC0066;"><?php print $errMsg; ?></h3>
	<?php
}
	
$ptStored = get_option('PO_pt_stored');
if (isset($post) && in_array(get_post_type($post->ID), $ptStored)) {
	?>
	<div id="PO-pt-override-msg-container">
		Settings for this post type have been overridden by the post type settings.  You can edit them by going <a href="<?php print get_admin_url(); ?>admin.php?page=PO_pt_plugins&PO_target_post_type=<?php print get_post_type($post->ID); ?>">here</a>.  You can also override them by checking the box below and saving the post.
		<br /><input type="checkbox" id="PO-activate-pt-override" name="PO_activate_pt_override" value="1" <?php print ($ptOverride == "1")? 'checked="checked"':''; ?>>Override Post Type settings
		<a href="#" onclick="PO_display_ui_dialog('Override Post Type Settings', 'By checking this box the changes you make here will not be overwritten by the settings that have been set for the <?php print get_post_type($post->ID); ?> post type.  You will be able to see the plugins disabled/enabled on this page and make changes to them.');return false;">
		  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
		</a>
		<?php if ($ptOverride == 0) { ?>
			<style type="text/css">
				#PO-post-meta-box-wrapper {display:none;}
			</style>
		<?php } else { ?>
			<style type="text/css">
				#PO-pt-override-msg-container {display:none;}
			</style>
		<?php } ?>
		
	</div>
	<?php
}
?>
<div id="PO-post-meta-box-wrapper" class="PO-content-wrap">
	<?php
	do_action('PO_display_meta_compatibility', 1);
	do_action('PO_display_meta_debug', 1);
	if ($adminPage != 'PO_search_plugins' && $adminPage != 'PO_global_plugins') { ?>
		<?php if(isset($post) && get_post_type($post->ID) == 'plugin_filter') { ?>
			<div class="metaBoxLabel">
				Name
			</div>
			<div class="metaBoxContent">
				<input type="text" class="PO-filter-name-input" size="25" name="PO_filter_name" value="<?php print $filterName; ?>">
			</div>
			<div class="metaBoxLabel">
				Permalinks
				<a href="#" onclick="PO_display_ui_dialog('Permalinks', 'Click the Add Permalink button to add new permalinks to this plugin filter. All of them will have the same settings that you select on this page.<br /><br />You can use limited wildcards in the permalink structure. For instance you can match the url http://www.foo.foo/some/pretty/permalink/ by entering http://www.foo.foo/some/*/permalink/.  You can also match the url by entering http://www.foo.foo/*/pretty/permalink/ as the permalink. The only character that is recognized is the * character. You can only use one and it can only replace one piece of the url in between the / characters.');return false;">
					<span class="dashicons PO-dashicon dashicons-editor-help"></span>
				 </a>
			</div>
			<div class="metaBoxContent">
				<div style="text-align: center;">
					<input type="button" id="PO-add-permalink" value="Add Permalink">
				</div>
				<div id="PO-permalink-container">
					<?php if (sizeof($permalinkFilters) > 0) { ?>
						<?php foreach($permalinkFilters as $permalinkFilter) { ?>
							<div class="PO-permalink-wrapper">
								<input type="hidden" name="PO_pl_id[]" value="<?php print $permalinkFilter['pl_id']; ?>">
								<input type="text" class="PO-permalink-input" size="25" name="PO_permalink_filter_<?php print $permalinkFilter['pl_id']; ?>" value="<?php print ($permalinkFilter['permalink'] != "") ? (($permalinkFilter['secure'] == 1)? "https://":"http://") . $permalinkFilter['permalink'] : ""; ?>"><input type="button" class="PO-delete-permalink" value="X">
							</div>
						<?php } ?>
					<?php } else { ?>
						<script type="text/javascript" language="javascript">
							jQuery(function() {
								PO_add_permalink();
							});
						</script>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<div id="settingsMetaBox" class="metaBoxContent">
			<div class="PO-meta-head">Settings<?php if(isset($ajaxSaveFunction)) { ?><input type=button name=submit value="Save" onmousedown="<?php print $ajaxSaveFunction; ?>" class="PO-ajax-save-btn button button-primary"><?php } ?></div>
			<?php if ($adminPage == 'PO_pt_plugins') { ?>
				<div style="padding-left: 10px;">
				Post Type: <select id="PO-selected-post-type" name="PO_selected_post_type">
				<?php
					$supportedPostTypes = get_option("PO_custom_post_type_support");
					if (!is_array($supportedPostTypes)) {
						$supportedPostTypes = array();
					}
					if (isset($_REQUEST['PO_target_post_type'])) {
						$targetPostType = $_REQUEST['PO_target_post_type'];
					} else {
						$targetPostType = '';
					}
					
					foreach($supportedPostTypes as $postType) {
						print '<option value="' . $postType . '" ' . (($targetPostType == $postType)? 'selected="selected" ':'') . '>' . $postType . '</option>';
					}
				?>
				</select>
				</div>
				<hr>
				<input type="button" class="button" style="float: left;margin: 5px;" id="resetPostTypeSettings" value="Reset settings for this post type" onclick="PO_reset_pt_settings();">
				<div style="float: left;margin: 10px 5px 0px 0px;">
					<input type="checkbox" id="PO-reset-all-pt" name="PO-reset-all-pt" value="1"><label for="PO-reset-all-pt">Reset All</label>
				</div>
				<a href="#" onclick="PO_display_ui_dialog('Reset all matching posts', 'By checking this box all posts that match the selected post type will be reset.  If the box isn\'t checked the post type setting will be reset but the individual posts will still keep the settings until they are changed individually.  You can go directly to each post matching this post type and override this setting.  Then the changes you make here will not affect that post.');return false;">
					<span class="dashicons PO-dashicon dashicons-editor-help"></span>
				 </a>
				<div style="clear: both;"></div>
			<?php } else { ?>
				<?php if (isset($post)) { ?>
					<input type="checkbox" id="affectChildren" name="affectChildren" value="1" <?php print ($affectChildren == "1")? 'checked="checked"':''; ?>>Also Affect Children
					<a href="#" onclick="PO_display_ui_dialog('Also Affect Children', 'By checking this box the plugins disabled or enabled for this page will be used for its children if they have nothing set.');return false;">
					  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
					</a>
					<hr>
					<?php if(isset($post) && in_array(get_post_type($post->ID), get_option('PO_custom_post_type_support'))) { ?>
						<input type="checkbox" id="PO-pt-override" name="PO_pt_override" value="1" <?php print ($ptOverride == "1")? 'checked="checked"':''; ?>>Override Post Type settings
						<a href="#" onclick="PO_display_ui_dialog('Override Post Type Settings', 'By checking this box the changes you make here will not be overwritten by the settings that have been set for the <?php print get_post_type($post->ID); ?> post type.');return false;">
						  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
						</a>
						<hr>
					<?php } ?>
					<?php if (get_post_type($post->ID) == 'plugin_filter') { ?>
						<input type="text" id="postPriority" name="PO_post_priority" value="<?php print $postPriority; ?>" maxlength="3" size="4">Priority
						<a href="#" onclick="PO_display_ui_dialog('Priority', 'This will set the priority of the post when fuzzy url matching is used.  If multiple plugin filters are found this will decide which is used.  Higher priority takes precedence.');return false;">
						  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
						</a>
						<hr>
					<?php } ?>
					<input type="button" class="button" style="margin: 5px;" id="resetPostSettings" value="Reset settings for this post" onclick="PO_reset_post_settings(<?php print $post->ID; ?>);">
					<?php 
				}
			}
			?>
		</div>
	<?php } ?>
	
	<?php
	if (get_option('PO_disable_plugins_mobile') == '1' || get_option("PO_disable_plugins_by_role") == '1') {
		?>
		<div id="viewControlContainer" class="metaBoxContent">
			<div class="PO-meta-head">View Controls<?php if(isset($ajaxSaveFunction)) { ?><input type=button name=submit value="Save" onmousedown="<?php print $ajaxSaveFunction; ?>" class="PO-ajax-save-btn button button-primary"><?php } ?></div>
			<div class="PO-container-controls"><input type="button" id="PO-display-plugin-overview" class="button button-primary" value="Overview"></div>
			<?php
			if (get_option('PO_disable_plugins_mobile') == '1') {
				?>
				<div class="view-selector">
					<label for="PO_available_platforms">Platform</label>
					<select name="PO_available_platforms" id="PO-available-platforms">
						<option value="std">Standard</option>
						<option value="mobile">Mobile</option>
					</select>
					<a href="#" onclick="PO_display_ui_dialog('Platform', 'The platform that will be affected by your plugin selections.  Either Standard or Mobile.  Standard is anything that doesn\'t match the patterns entered on the settings page under the Mobile User Agents tab.');return false;">
					  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
					</a>
				</div>
				<?php
			} else {
				?>
				<input type="hidden" name="PO_available_platforms" id="PO-available-platforms" value="std">
				<?php
			}
						
			if ($adminPage != 'PO_global_plugins' && get_option("PO_disable_plugins_by_role") == '1' && is_array($availableRoles)) {
				?>
				<div class="view-selector">
					<label for="PO_available_roles">Role</label>
					<select name="PO_available_roles" id="PO-available-roles">
						<?php
						foreach($availableRoles as $roleID=>$roleName) {
							print '<option value="'.$roleID.'">'.$roleName.'</option>';
						}
						?>
					</select>
					<a href="#" onclick="PO_display_ui_dialog('Role', 'The user role that will be affected by your plugin selections.  These can be set on the settings page under the General Settings tab.  The first role matched in the priority order you have set will take affect.  Some users will have multiple roles.');return false;">
					  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
					</a>
				</div>
				<?php
			} else {
				?>
				<input type="hidden" name="PO_available_roles" id="PO-available-roles" value="_">
				<?php
			}
			?>
		</div>
		<?php
	} else {
		?>
		<input type="hidden" name="PO_available_platforms" id="PO-available-platforms" value="std">
		<input type="hidden" name="PO_available_roles" id="PO-available-roles" value="_">
		<?php
	}

	?>
	<div id="hidden-plugin-lists-container">
		<?php
		foreach ($plugins as $key=>$plugin) {
			foreach($availableRoles as $roleID=>$roleName) {
				if ((in_array($key, $globalPlugins) && !in_array($key, $pluginLists[$roleID]['enabled_plugin_list'])) || in_array($key, $pluginLists[$roleID]['disabled_plugin_list'])) {
					?>
					<input type="hidden" class="PO-disabled-std-plugin-list" name="PO_disabled_std_plugin_list[<?php print $roleID; ?>][]" value="<?php print $key; ?>">
					<?php
				}
				if (get_option('PO_disable_plugins_mobile') == '1') {
					if ((in_array($key, $globalMobilePlugins) && !in_array($key, $pluginLists[$roleID]['enabled_mobile_plugin_list'])) || in_array($key, $pluginLists[$roleID]['disabled_mobile_plugin_list'])) {
						?>
						<input type="hidden" class="PO-disabled-mobile-plugin-list" name="PO_disabled_mobile_plugin_list[<?php print $roleID; ?>][]" value="<?php print $key; ?>">
						<?php
					}
				}
			}
		}
		foreach ($groupList as $key=>$group) {
			foreach($availableRoles as $roleID=>$roleName) {
				if ((in_array($group->ID, $globalGroups) && !in_array($group->ID, $pluginLists[$roleID]['enabled_group_list'])) || in_array($group->ID, $pluginLists[$roleID]['disabled_group_list'])) {
					?>
					<input type="hidden" class="PO-disabled-std-group-list" name="PO_disabled_std_group_list[<?php print $roleID; ?>][]" value="<?php print $group->ID; ?>">
					<?php
				}
				if (get_option('PO_disable_plugins_mobile') == '1') {
					if ((in_array($group->ID, $globalMobileGroups) && !in_array($group->ID, $pluginLists[$roleID]['enabled_mobile_group_list'])) || in_array($group->ID, $pluginLists[$roleID]['disabled_mobile_group_list'])) {
						?>
						<input type="hidden" class="PO-disabled-mobile-group-list" name="PO_disabled_mobile_group_list[<?php print $roleID; ?>][]" value="<?php print $group->ID; ?>">
						<?php
					}
				}
			}
		}
		?>
	</div>

	<div id="pluginContainer" class="metaBoxContent">
		<div class="PO-meta-head">Plugins
			<a href="#" onclick="PO_display_ui_dialog('Legend', '<div class=\'legend-continaer active-plugin-legend\'>Enabled and Active</div><div class=\'legend-continaer inactive-plugin-legend\'>Enabled and Inactive</div><div class=\'legend-continaer disabled-plugin-legend\'>Disabled</div><?php print ($adminPage != 'PO_global_plugins')? "<div class=\'legend-continaer global-disabled-plugin-legend\'>Globally Disabled</div>":""; ?>');return false;">
			  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
			</a>
			<?php if(isset($ajaxSaveFunction)) { ?><input type=button name=submit value="Save" onmousedown="<?php print $ajaxSaveFunction; ?>" class="PO-ajax-save-btn button button-primary"><?php } ?>
		</div>
		<div class="PO-container-controls">
			<input type="button" id="PO-disable-all-plugins" class="button button-primary" value="Disable All">&nbsp;
			<input type="button" id="PO-enable-all-plugins" class="button button-primary" value="Enable All">
		</div>
		<div id="PO-all-plugin-wrap" class="outer-plugin-wrap">
			<?php
			foreach ($plugins as $key=>$plugin) {
				$pluginWrapClass = (in_array($key, $activeSitewidePlugins) || in_array($key, $activePlugins))? "active-plugin-wrap" : "inactive-plugin-wrap";
				?>
				<div class="plugin-wrap <?php print $pluginWrapClass; ?>">
					<div class="plugin-name-container">
						<input type="hidden" class="PO-plugin-id" value="<?php print $key; ?>" />
						<span class="PO-plugin-name"><?php print $plugin['Name']; ?></span>
					</div>
					<div style="clear: both;"></div>
				</div>
				<?php
			} ?>
		</div>
	</div>

	<div id="groupContainer" class="metaBoxContent">
		<div class="PO-meta-head">Plugin Groups
			<a href="#" onclick="PO_display_ui_dialog('Legend', '<div class=\'legend-continaer active-plugin-legend\'>Enabled and Active</div><div class=\'legend-continaer inactive-plugin-legend\'>Enabled and Inactive</div><div class=\'legend-continaer disabled-plugin-legend\'>Disabled</div><?php print ($adminPage != 'PO_global_plugins')? "<div class=\'legend-continaer global-disabled-plugin-legend\'>Globally Disabled</div>":""; ?>');return false;">
			  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
			</a>
			<?php if(isset($ajaxSaveFunction)) { ?><input type=button name=submit value="Save" onmousedown="<?php print $ajaxSaveFunction; ?>" class="PO-ajax-save-btn button button-primary"><?php } ?>
		</div>
		<div class="PO-container-controls">
			<input type="button" id="PO-disable-all-groups" class="button button-primary" value="Disable All">&nbsp;
			<input type="button" id="PO-enable-all-groups" class="button button-primary" value="Enable All">
		</div>
		<div id="PO-all-group-wrap" class="outer-group-wrap">
			<?php if (sizeOf($groupList) > 0) {
				foreach ($groupList as $key=>$group) {
					?>
					<div class="group-wrap <?php print $groupWrapClass; ?>">
						<div class="group-name-container">
							<input type="hidden" class="PO-group-id" value="<?php print $group->ID; ?>" />
							<?php 
							$membersTip = '__ts__Plugins__te__';
							$groupMembers = get_post_meta($group->ID, "_PO_group_members", $single=true);
							if (is_array($groupMembers)) {
								foreach($groupMembers as $plugin) {
									$membersTip .= '__rs__'.$plugins[$plugin]['Name'].'__re__';
								}
							}
							?>
							<span class="PO-group-members" title="<?php print $membersTip; ?>"><?php print $group->post_title; ?></span>
						</div>
						<div style="clear: both;"></div>
					</div>
					<?php
				}
			} ?>
		</div>
		<div style="clear: both;"></div>
	</div>
	<div style="clear: both;"></div>
</div>
<div style="clear: both;"></div>
<input type="hidden" name="poSubmitPostMetaBox" value="1" />