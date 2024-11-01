<?php
class wpRPG_BankCP extends wpRPG
{
	protected $file = __FILE__;
	protected $plugslug = '';
	function __construct()
	{
		parent::__construct();
		global $wp_rewrite;
		$this->plugslug = basename(dirname(__FILE__));
		$this->version = WPRPG_BankCP_Version;
	}

	function add_page_settings( $pages ) {
			$setting = array(
				'bank'=> array('name'=>'Bank', 'shortcode'=>'[wprpg_bankcp]')
			);
			return array_merge( $pages, $setting );
		}
	
	function BankCP_Jquery_Code($code)
	{
		global $current_user;
		$BankCode = array(
			"$('button#depositbutton').click(function() {
				var gol = $('th#player_gold').html();
				var dep = $('input#deposit').val() ;
				if(gol >= dep){
					$.ajax({
						method: 'post',
						url: '". site_url('wp-admin/admin-ajax.php')."',
						data: {
							'action': 'deposit',
							'gold': dep,
							'ajax': true,
							'attacking': true
						},
						success: function(data) {
							$('#rpg_area').empty();
							$('#rpg_area').html(data);
						}
					});
				}else{
					alert('You tried to deposit more than you have! ');
				}
			});",
			"$('button#withdrawbutton').click(function() {
				var gol = $('th#player_bank').html();
				var dep = $('input#withdraw').val() ;
				if(gol >= dep){
					$.ajax({
						method: 'post',
						url: '". site_url('wp-admin/admin-ajax.php')."',
						data: {
							'action': 'withdraw',
							'gold': dep,
							'ajax': true,
							'attacking': true
						},
						success: function(data) {
							$('#rpg_area').empty();
							$('#rpg_area').html(data);
						}
					});
				}else{
					alert('You tried to withdraw more than you have! ');
				}
			});"
		);
		if(get_option('cp_ver')){
			$cpCode = array(
			"$('button#xc2gbutton').click(function() {
				var gol = ".  (get_user_meta($current_user->ID, 'cpoints', 1)?get_user_meta($current_user->ID, 'cpoints', 1):"0") .";
				var pts = $('input#xc2g').val() ;
				if(pts <= gol){
					$.ajax({
						method: 'post',
						url: '". site_url('wp-admin/admin-ajax.php')."',
						data: {
							'action': 'c2g',
							'gold': pts,
							'ajax': true,
							'attacking': true
						},
						success: function(data) {
							$('#rpg_area').empty();
							$('#rpg_area').html(data);
						}
					});
				}else{
					alert('You tried to withdraw more than you have! You want to xchange:' + pts + ' pts out of '+gol+' total pts.' );
				}
			});",
			"$('button#xg2cbutton').click(function() {
				var gol = $('th#player_gold').html();
				var pts = $('input#xg2c').val() ;
				if(gol >= pts){
					$.ajax({
						method: 'post',
						url: '". site_url('wp-admin/admin-ajax.php')."',
						data: {
							'action': 'g2c',
							'gold': pts,
							'ajax': true,
							'attacking': true
						},
						success: function(data) {
							$('#rpg_area').empty();
							$('#rpg_area').html(data);
						}
					});
				}else{
					alert('You tried to withdraw more than you have! ');
				}
			});");
			$BankCode = array_merge($BankCode, $cpCode);
		}
		return array_merge($code, $BankCode);
	}
	
	function add_BankCP_admin_tab($tabs)
	{
		$tab_page = array('bankcp'=>$this->BankCP_options(1));
		return array_merge($tabs, $tab_page);
	}
	
	function add_BankCP_admin_tab_header($tabs)
	{
		$attack_tabs = array('bankcp'=>'Bank Settings');
		return array_merge($tabs, $attack_tabs);
	}

	private function depositGold(){
		global $current_user, $wpdb;
		if(class_exists('Player')){
			$player = new Player($current_user->ID);
			if($player->gold >= $_POST['gold'])
			{
				$player->update_meta('gold', ($player->gold - $_POST['gold']));
				$player->update_meta('bank', ($player->bank + $_POST['gold']));
				echo 'You\'ve Deposited ' . $_POST['gold'].' Gold<br /><a href="#" onclick="location.reload(true); return false;">Reload Bank</a>'; 
			}else{
				echo 'You\'ve tried to deposit a higher amount of gold than you possess';
				echo '<br /><a href="#" onclick="location.reload(true); return false;">Reload Bank</a>';
			}
		}else{
			$player_gold = get_user_meta($current_user->ID, 'gold');
			$player = $player_gold[0];
			$player_bank = get_user_meta($current_user->ID, 'bank');
			$bank = $player_bank[0];
			if ($player >= $_POST['gold'])
			{
				update_user_meta($current_user->ID, 'gold', ($player - $_POST['gold']));
				update_user_meta($current_user->ID, 'bank', $bank + $_POST['gold']);
				echo 'You\'ve Deposited ' . $_POST['gold'].' Gold<br /><a href="#" onclick="location.reload(true); return false;">Reload Bank</a>';
			}else{
				echo 'You\'ve tried to deposit a higher amount of gold than you possess';
				echo '<br /><a href="#" onclick="location.reload(true); return false;">Reload Bank</a>';
			}
		}
	}
	
	private function withdrawGold(){
		global $current_user, $wpdb;
		$player_gold = get_user_meta($current_user->ID, 'gold');
		$player = $player_gold[0];
		$player_bank = get_user_meta($current_user->ID, 'bank');
		$bank = $player_bank[0];
		if ($bank >= $_POST['gold'])
		{
			update_user_meta($current_user->ID, 'gold', ($player + $_POST['gold']));
			update_user_meta($current_user->ID, 'bank', $bank - $_POST['gold']);
			echo 'You\'ve Withdrew ' . $_POST['gold'].' Gold<br /><a href="#" onclick="location.reload(true); return false;">Reload Bank</a>';
		}else{
			echo 'You\'ve tried to deposit a higher amount of gold than you possess';
		}
	}
	
	private function c2gGold(){
		global $current_user, $wpdb;
		$player_gold = get_user_meta($current_user->ID, 'gold'); 
		$player = $player_gold[0];
		if (cp_getPoints($current_user->ID) >= $_POST['gold'])
		{
			$points = $_POST['gold'] * get_option('wpRPG_BankCP_c2g_rate');
			cp_updatePoints($current_user->ID, cp_getPoints($current_user->ID) - $_POST['gold']);
			update_user_meta($current_user->ID, 'gold', ($player + ($_POST['gold'] * $points)));
			echo 'You\'ve Exchanged ' . $_POST['gold'].' Cubepoints <br /><a href="#" onclick="location.reload(true); return false;">Reload Bank</a>';
		}else{
			echo 'You\'ve tried to deposit a higher amount of gold than you possess';
		}
	}
	
	private function g2cGold(){
		global $current_user, $wpdb;
		$player_gold = get_user_meta($current_user->ID, 'gold');
		$player = $player_gold[0];
		if ($player >= $_POST['gold'])
		{
			$points = $_POST['gold'] * get_option('wpRPG_BankCP_g2c_rate');
			cp_updatePoints($current_user->ID, cp_getPoints($current_user->ID) + ($_POST['gold']*$points));
			update_user_meta($current_user->ID, 'gold', ($player - $_POST['gold']));
			echo 'You\'ve Exchanged ' . $_POST['gold'].' Gold<br /><a href="#" onclick="location.reload(true); return false;">Reload Bank</a>';
		}else{
			echo 'You\'ve tried to deposit a higher amount of gold than you possess';
		}
	}
	
	function c2g_callback() {
		$this->c2gGold();
		//$_POST = '';
		die();
	}
	
	function g2c_callback() {
		$this->g2cGold();
		//$_POST = '';
		die();
	}
	
	function deposit_callback() {
		$this->depositGold();
		//$_POST = '';
		die();
	}
	
	function withdraw_callback() {
		$this->withdrawGold();
		//$_POST = '';
		die();
	}

	function BankCP_options($opt = 0) {
		$html = "<tr>";
		$html .= "<td>";
		$html .= "<h3>Welcome to Wordpress RPG BankCP Module!</h3>";
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "<br />";
		$html .= (get_option('cp_ver')?'<tr><td><span>You have cubepoints installed!</span></td></tr>':'<tr><td><span>You don\'t have Cubepoints installed!</span></td></tr>');
		$html .= "<tr>";
		$html .= "<td>";
		$html .= "<table border=1><tr><th>Setting Name</th><th>Setting</th></tr>";
		$c2g = get_option('wpRPG_BankCP_c2g_rate');
		$html .= "<tr><td>Cubepoints to Gold Exchange Rate:</td><td><input type=text value=$c2g name='wpRPG_BankCP_c2g_rate' id='wpRPG_BankCP_c2g_rate' /></td></tr>";
		$g2c = get_option('wpRPG_BankCP_g2c_rate');
		$html .= "<tr><td>Gold to Cubepoints Exchange Rate:</td><td><input type=text value=$g2c name='wpRPG_BankCP_g2c_rate' id='wpRPG_BankCP_g2c_rate' /></td></tr>";
		$html .= "</table>";
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "<tr><td><span class='description'>Version: ".$this->version."</span></td></tr>";
		if(!$opt)
			echo $html;
		else
			return $html;
	}
		
	function include_BankCP_jquery() {
		wp_enqueue_script('jquery');
		wp_register_script( 'jquery-address', plugins_url('/js/jquery.address-1.5.min.js',__FILE__ ));
		wp_enqueue_script('jquery-address');
		
	}


	function wpRPG_bankCP_load_plugin() 
	{ 
		if ( ! current_user_can( 'activate_plugins' ) ) 
			return; 
		if(is_admin()&&get_option('Activated_Plugin')=='wpRPG-BankCP') 
		{ 
			delete_option('Activated_Plugin'); 
			//add_action( 'admin_notices', array($this,'wpRPG_BankCP_check_admin_notices'), 0 ); 
		}
	}

	function check_tables() 
	{
		global $wpdb;
		
//		$wpdb->query($sql);
		return true;
	}

	function check_column($table, $col_name) {
		global $wpdb;
		if ($table != null) {
			$results = $wpdb->get_results("DESC $table");
			if ($results != null) {
				foreach ($results as $row) {
					if ($row->Field == $col_name) {
						return true;
					}
				}
				return false;
			}
			return false;
		}
		return false;
	}
	function WpRPG_BankCP_check_plugin_requirements() 
	{
		$errors = array();
		if (!class_exists('wpRPG')) {
			$errors[] = "WPRPG must be installed!<br />";
			deactivate_plugins(WPRPG_BankCP_Plugin_File);
		}elseif (!get_option('WPRPG_BankCP_installed')) {
			 if ($this->check_tables() != FALSE) {
				update_option('WPRPG_BankCP_installed', "1");
			} else {
				$errors[] = "You had an error occur!<br />";
			}
		}else{
			//die(get_option('WPRPG_rpg_installed'));
		}
		return $errors;
	}

	function RegisterSettings() 
	{
		// Add options to database if they don't already exist
		add_option('WPRPG_BankCP_installed', "", "", "yes");
		
		// Register settings that this form is allowed to update
		register_setting('rpg_settings', 'WPRPG_BankCP_installed');
		
		add_option( 'wpRPG_BankCP_c2g_rate', "2", "", "yes");
		register_setting( 'rpg_settings', 'wpRPG_BankCP_c2g_rate' );
		
		add_option( 'wpRPG_BankCP_g2c_rate', ".5", "", "yes");
		register_setting( 'rpg_settings', 'wpRPG_BankCP_g2c_rate' );
		

	}
	

}
?>