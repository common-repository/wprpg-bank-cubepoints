<?php
	$wpRPG = new wpRPG;
	$wpdb->show_errors();
	$res = $wpRPG->get_player_meta($current_user->ID);
    if ( $res ) {
	global $message;
?>

<div id="rpg_area">
					
									<div class="simpleTabsContent" id="bio" style="height:500px;">
										<div name="player_heading">
											<h3><?php echo $res->nickname ?></h3>
											<?php if( isset($message)){ ?><h3><?php echo $message; ?></h3><?php } ?>
										</div>
										<br/>
										<div style="width:100%;">
											<ul style="list-style:none;">
												<li style="width:100%">
													<table>
														<tr><th>Current Gold:</th><th id="player_gold" name="player_gold"><?php echo $res->gold ?></th></tr>
														<tr><th>Banked Gold:</th><th id="player_bank" name="player_bank"><?php echo $res->bank ?></th></tr>
														<tr><td>Deposit Gold To Bank:</td><td><input type='text' name='deposit' id='deposit' value=0 /></td><td><button name='depositbutton' id='depositbutton'>Deposit</button></td></tr>
														<tr><td>Withdraw Gold From Bank:</td><td><input type='text' name='withdraw' id='withdraw' value=0 /></td><td><button name='withdrawbutton' id='withdrawbutton'>Withdraw</button></td></tr>
													</table>
												</li>
											</ul>
										</div><?php if (get_option('cp_ver')){ ?>
										<div style="margin-right:auto;width:100%">
											<ul style="list-style:none;">
												<li style="width:100%;">
													<table>
													<tr><th>Cubepoints</th></tr>
														<tr><th>Total Cubepoints:</th><th id="player_cp" name="player_cp"><?php echo (get_user_meta($current_user->ID, 'cpoints', 1)?get_user_meta($current_user->ID, 'cpoints', 1):"0"); ?></th></tr>
														<tr><td>Exchange Gold For Cubepoints:</td><td><input type='text' name='xg2c' id='xg2c' value=0 /></td><td><button name='xg2cbutton' id='xg2cbutton'>Exchange</button></td></tr>
														<tr><td>Exchange Cubepoints For Gold:</td><td><input type='text' name='xc2g' id='xc2g' value=0 /></td><td><button name='xc2gbutton' id='xc2gbutton'>Exchange</button></td></tr>
													</table>
												</li>
											</ul>
										</div><?php } ?>
									</div>
					</div><br/><br/>
<?php
}else
{
?>
<h1>Bank</h1>
<strong>You must be logged in to view this page!</strong>
<?php } ?>