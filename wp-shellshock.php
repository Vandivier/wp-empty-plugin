<?php
/**
* Plugin Name: WP Shellshock
* Plugin URI: 
* Description: Quickly inject custom code.
* Version: 0.3
* Author: John Vandivier
* Author URI: http://www.afterecon.com/portfolio/
* License: MIT open source license. Unrestricted use. Credit preferred.
**/


/* logic:
1 On install, set up module table which contains module name, module content, module inject method
2 On page load, display modules
3 Allow add, edit, delete, deactivate modules
4 On page load, load modules as needed

todo:
1 module groups
2 group-to-plugin button
3 backup group
4 import and export
*/


define('__ROOT__', dirname(__FILE__));
add_action('admin_menu', 'shellshock_menu');

function shellshock_menu() {
	add_menu_page('WP Shellshock', 'WP Shellshock', 'manage_options', 'wp-shellshock', 'wp_shellshock_fxn');
}

function wp_shellshock_fxn() {
	require_once(__ROOT__.'/update_ssdb.php');
	echo '<script>__JROOT__ = "'.__ROOT__.'";</script>';
?>


<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
<style>
.testdiv {
	border: 1px solid darkRed;
}
#module-list {
	border: 2px solid darkGrey;
	padding: 5px;
}
#wpcontent {
	padding-left: 0px;
}
.module-list-item {
	cursor: pointer;
}
.remove-module {
	cursor: pointer;
	color: darkRed;
	float: right;
	margin-right: 30px;
	font-weight: bold;
}
.remove-module:hover {
	color: red;
	text-decoration: none;
}
.align-mid {
	text-align: center;
}
</style>


<div class='container-fluid'>
<br>
<br>
<div class='align-mid'>Thank you for downloading WP Shellshock. We added jQuery and Bootstrap to ur project btw.</div>
<div class="testdiv" id="testdiv"></div>
<br>
<div class='align-mid'>Quickly customize, prototype, or develop in a modular, non-destructive way. Lighter and faster than the competition ;)
<br>Better than a site-specific plugin. Backup, import, and export. Eliminate boilerplate. Be awesome.</div>
<br>
<div>
  	<input id='new-module-name' placeholder='New Module Name'>
  	<select id='injection-method'>
	  <option selected disabled>Injection Method</option>
	  <option value='na'>None</option>
	  <option value='ph'>Preppend to Head</option>
	  <option value='ah'>Append to Head</option>
	  <option value='pb'>Preppend to Body</option>
	  <option value='ab'>Append to Body</option>
	  <option value='pf'>Preppend to Footer</option>
	  <option value='af'>Append to Footer</option>
  	</select>
	<button onclick='addModule()'>New Module</button>
	<br>
</div>
<br>
<div class='row'>
	<div class='col-md-6'>
	  <div id='module-list'>
		<h4>Module List</h4>
		<div class='module-list-item'><a id="Module1" class='module-name'>Module1</a><a class='remove-module'>-</a></div>
	  </div>
	</div>
  	<div class='col-md-6' id='edit-module-area'>
	  <textarea rows="4" cols="50" id="module-content" placeholder='Enter arbitrary PHP.'></textarea>
	  	<br>
	  	Selected Module: <div id='selected-module'>[None]</div>
		<button onclick='updateModuleContents()'>Update Module Content</button>
  	</div>
</div>
<br>
<button>Export Selected Modules [Coming Soon]</button>
<button>Import Module(s) [Coming Soon]</button>
</div>


<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script>
function updateModuleContents() {
  alert("Changes saved to module named : " + jQuery('#selected-module')[0].textContent);
}

function addModule() {
  //document.getElementById("module-content").value += script_src;
  var n = jQuery('#new-module-name').val();
  //todo: ensureUnique(n);
  //todo: removeModule();
  jQuery("#module-list").append("<div class='module-list-item'><a id="+ n +" class='module-name'>"+ n +"</a><a class='remove-module'>-</a></div>");
}
  //Handling dynamic html? http://stackoverflow.com/questions/203198/event-binding-on-dynamically-created-elements
jQuery('#module-list').on('click','.module-list-item .module-name',function(){
  jQuery('#selected-module')[0].textContent = jQuery(this)[0].textContent;
});
jQuery('#module-list').on('click','.module-list-item .remove-module',function(){
  jQuery(this).parent('.module-list-item').remove();
});
</script>


<?}

global $wpss_db_version;
$wpss_db_version = '1.0';

function wpss_db_install() {
	global $wpdb;
	global $wpss_db_version;

	$table_name = $wpdb->prefix . 'shellshock_db';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		head text NOT NULL,
		footer text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'wpss_db_version', $wpss_db_version );
}

function wpss_install_data() {
	global $wpdb;

	$default_head = 'i am the default head';
	$deafault_footer = 'me is dat default footer';

	$table_name = $wpdb->prefix . 'shellshock_db';

	$wpdb->insert(
		$table_name,
		array(
			'head' => $default_head,
			'footer' => $deafault_footer
		)
	);
}

function wpss_remove_data() {
	global $wpdb;
	global $wpss_db_version;
	
	delete_option( 'wpss_db_version', $wpss_db_version );
	$table_name = $wpdb->prefix . 'shellshock_db';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
}

register_activation_hook( __FILE__, 'wpss_db_install' );
register_activation_hook( __FILE__, 'wpss_install_data' );
register_uninstall_hook( __FILE__, 'wpss_remove_data' );
?>