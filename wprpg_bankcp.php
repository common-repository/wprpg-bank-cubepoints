<?php
/*
  Plugin Name: WP RPG Bank w/ Cubepoints
  Plugin URI: http://wordpress.org/extend/plugins/wprpg_bank_cubepoints/
  Version: 1.0.5
  wpRPG: 1.0.13
  Author: <a href="http://tagsolutions.tk">Tim G.</a>
  Description: RPG Bank Element with Cubepoints Integration
  Text Domain: wprpg_bank_cp
  License: GPL2
 */
 /*
	Globals
 */
global $wpdb;
 
/*
	Definitions
	@since 1.0.0
*/
define('WPRPG_BankCP_Plugin_File', plugin_basename( __FILE__ ));
define('WPRPG_BankCP_Version', '1.0.5');

 /*
	WPRPG Class Loader
	@since 1.0.0
*/

function BankCP_wpRPGCheck() {
	if ( class_exists( 'wpRPG' ) ) {
		if(!class_exists('wpRPG_BankCP')){
			include(__DIR__. '/wprpg-bankcp-class.php');
		}
        	$rpgBankCP = new wpRPG_BankCP;
		include ( __DIR__.'/wprpg-bankcp-library.php');
	}
}
add_action( 'plugins_loaded', 'BankCP_wpRPGcheck' );
/*
	Plugin Activations / Uninstall
	@since 1.0.0
*/
function BankCP_Activate()
{
	add_option('Activated_Plugin','wpRPG-BankCP');
}

register_activation_hook( __FILE__, 'BankCP_Activate');
register_deactivation_hook(__FILE__, 'wpRPG_BankCP_on_deactivation');
register_uninstall_hook(__FILE__, 'wpRPG_BankCP_on_uninstall');

function wpRPG_BankCP_on_deactivation() {
    if (!current_user_can('activate_plugins'))
        return;
    $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
	update_option('WPRPG_BankCP_installed', 0);
}

function wpRPG_BankCP_on_uninstall() {
    global $wpdb;
    if (!current_user_can('activate_plugins'))
        return;
		
    check_admin_referer('bulk-plugins');
   
   if (__FILE__ != WP_UNINSTALL_PLUGIN)
        return;
}

?>
