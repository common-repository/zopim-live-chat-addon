<?php
/*
Plugin Name: Zopim Widget Addon - Page Exceptions
Plugin URI: http://www.zopim.org
Description: Addition to Zopim Plugin, makes an option to select on which pages NOT to show the plugin.
Author: Alex Frenkel
Version: 0.3
Author URI: http://alex.frenkel-online.com/
*/

class sirshurf_zopim {
	
	function check_page($content){
		$strList = get_option('zopimGetPageExcludeList');

		if(!empty($strList)){
			$arrPages = explode(',',$strList);

			if (!empty($arrPages)){
				if (is_page($arrPages)){
					remove_action('get_footer', 'zopimme');
				}
			}
		}
		return $content;
	}

	function zopim_create_custom_menu(){
	   add_submenu_page('zopim_account_config', 'Zopim Exclusion', 'Zopim Exclusion', 'administrator', 'admin.php?page=zopim_create_custom_menu_page', array('sirshurf_zopim', 'zopim_create_custom_menu_page'));
	}

	function zopim_create_custom_menu_page() {
	   global $current_user;

	   $message = "";
	   if (count($_POST) > 0) {
	      update_option('zopimGetPageExcludeList', $_POST["zopimGetPageExcludeList"]);
	}

	?>

	<div class="wrap">
	<div id="icon-themes" class="icon32"><br/></div><h2>Customize your widget</h2>

	<?php echo $message; ?>
	<form method="post" action="">
	<div class="metabox-holder">
		<div class="postbox">
			<h3 class="hndle"><span>General Settings</span></h3>
			<div style="padding:10px;">
	    <table class="form-table">

		<tr valign="top" >
		<th scope="row">List of pages to exclude</th>
		<td><input type="text" id="zopimGetPageExcludeList" name="zopimGetPageExcludeList" value=" <?php echo (get_option('zopimGetPageExcludeList'));?>" /></td>
		</tr>
	</table>
	</div>
	</div>
	    <p class="submit">
	    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	    </p>
	</div>
	</form>
	</div>
	<?php
	}

	function activate() {
		// ensure path to this file is via main wp plugin path
		$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
		$this_plugin = plugin_basename(trim($wp_path_to_this_file));
		$active_plugins = get_option('active_plugins');
		$this_plugin_key = array_search($this_plugin, $active_plugins);
		if ($this_plugin_key !== false) { 
			array_splice($active_plugins, $this_plugin_key, 1);
			array_push($active_plugins, $this_plugin);
			update_option('active_plugins', $active_plugins);
		}
	}
}

add_action('admin_menu', array('sirshurf_zopim', 'zopim_create_custom_menu'));
add_filter('the_content', array('sirshurf_zopim','check_page') ,10,3);
add_action("activated_plugin", array('sirshurf_zopim', "activate"));


