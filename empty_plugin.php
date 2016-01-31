<?php
/**
* Plugin Name: Empty Plugin
* Plugin URI: 
* Description: Install to quick start a custom WordPress plugin.
* Version: 0.1
* Author: John Vandivier
* Author URI: afterecon.com/portfolio
* License: MIT open source license. Unrestricted use. Credit preferred.
**/

add_action('wp_head', 'ep_mod_head');

function ep_mod_head() {
?>
<style>

</style>
<script>
	jQuery(function(){
		alert('jQuery/plugin check.');
	});
</script>
<?php
}
?>