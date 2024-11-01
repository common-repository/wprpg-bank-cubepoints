<?php
$rpgBankCP = new wpRPG_BankCP;
add_action('admin_init', array($rpgBankCP,'wpRPG_bankcp_load_plugin'));
add_action('admin_init', array($rpgBankCP,'RegisterSettings'));
add_action('wp_ajax_deposit', array($rpgBankCP,'deposit_callback'));
add_action('wp_ajax_nopriv_deposit', array($rpgBankCP,'deposit_callback'));
add_action('wp_ajax_withdraw', array($rpgBankCP,'withdraw_callback'));
add_action('wp_ajax_nopriv_withdraw', array($rpgBankCP,'withdraw_callback'));
add_action('wp_ajax_g2c', array($rpgBankCP,'g2c_callback'));
add_action('wp_ajax_nopriv_g2c', array($rpgBankCP,'g2c_callback'));
add_action('wp_ajax_c2g', array($rpgBankCP,'c2g_callback'));
add_action('wp_ajax_nopriv_c2g', array($rpgBankCP,'c2g_callback'));
add_filter('wpRPG_add_admin_tab_header', array($rpgBankCP, 'add_BankCP_admin_tab_header'));
add_filter('wpRPG_add_admin_tabs', array($rpgBankCP, 'add_BankCP_admin_tab'));
add_filter('wpRPG_add_plugin_code', array($rpgBankCP, 'BankCP_Jquery_Code'));
add_shortcode( 'wprpg_bankcp', 'showBankCP' );
add_filter( 'profile_section_mid_right','add_profile_section_mid_right' );
add_filter( 'wpRPG_add_pages_settings', array($rpgBankCP,'add_page_settings') );
if ( is_admin() ){
	add_action( 'admin_init', 'register_settings' );
}
if (!is_admin()) 
{
		add_action('wp_enqueue_scripts', array($rpgBankCP,'include_BankCP_jquery'));
}

function register_settings(){
	if ( !get_option( 'wpRPG_bank_Page' ) ) {
        add_option( 'wpRPG_bank_Page', 'bank', "", "yes" );
	}
	register_setting( 'rpg_settings', 'wpRPG_bank_Page' );
}

function showBankCP( ) {
	global $wpdb, $current_user;
	$result = '';
	if(file_exists(get_template_directory() . 'templates/wprpg/show_bankcp.php')){
		include_once (get_template_directory() . 'templates/wprpg/show_bankcp.php');
	}else{
		include_once (__DIR__ .'/templates/show_bankcp.php');
	}
	if ( get_option ( 'show_wpRPG_Version_footer' ) )	{
		$result .= '<footer style="display:block;margin: 0 2%;border-top: 1px solid #ddd;padding: 20px 0;font-size: 12px;text-align: center;color: #999;">';
		$result .= 'Powered by <a href="http://tagsolutions.tk/wordpress-rpg/">wpRPG '. WPRPG_VERSION .'</a></footer>';
	}
	return $result;
}

/**
 * Adds Cubepoints to profile section
 * @return array
 * @since 1.0.2
 */
function add_profile_section_mid_right($actions)
{
	$profile = new wpRPG_Profiles;
	$player = $profile->get_viewed_player();
	$profile_tabs = array(
		 'cp' =>  'Points: '.cp_getPoints($player->ID)
	);
	return array_merge( $actions, $profile_tabs );
}
?>