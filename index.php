<?php

/* 
Plugin Name: My Xbox Profile
Plugin URI: http://www.peterjharrison.me/2010/01/my-xbox-profile-wordpress-plugin/
Description: Xbox 360 Gamercard Plugin, that displays your Xbox 360 gamertag details anywhere on your website.
Author: Peter J Harrison
Version: 2.0
Author URI: http://www.peterjharrison.me 

/*************************************************************************/
//	A Huge Thank you goes out to Duncan Mackenzie (duncanmackenzie.net)	 //
//  for providing the Xbox Live data service. 							 // 
//	Without him this plugin would never of been made.                    //
/*************************************************************************/

/*
Copyright 2010 - Peter J Harrison (email : me@peterjharrion.me)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*************************************************************************/
/*	Display Xbox Gamercard 												 */
/*************************************************************************/
function myxboxprofile() {
	
	// Get Gamercard Details
	$doc = new DOMDocument();
	$doc->load( 'http://xboxapi.duncanmackenzie.net/gamertag.ashx?GamerTag='.str_replace(' ', '+', get_option('tag_myxboxprofile')) );
	
	$gamercard = $doc->getElementsByTagName( "XboxInfo" );
	
	// Loop through details
	foreach( $gamercard as $tagdetails ) {
		
		// Get Picture
		$tileurls = $tagdetails->getElementsByTagName( "TileUrl" );
		$tileurl = $tileurls->item(0)->nodeValue;
		
		// Get Gamertag
		$gamertags = $tagdetails->getElementsByTagName( "Gamertag" );
		$gamertag = $gamertags->item(0)->nodeValue;
		
		// Profile Url
		$gamerprourls = $tagdetails->getElementsByTagName( "ProfileUrl" );
		$gamerprourl = $gamerprourls->item(0)->nodeValue;		
		
		// Get Info
		$infos = $tagdetails->getElementsByTagName( "Info" );
		$info = $infos->item(0)->nodeValue;
		
		// Get More Info
		$moreinfos = $tagdetails->getElementsByTagName( "Info2" );
		$moreinfo = $moreinfos->item(0)->nodeValue;
		
		// Get GamerScore
		$gamerscores = $tagdetails->getElementsByTagName( "GamerScore" );
		$gamerscore = $gamerscores->item(0)->nodeValue;
		
		// Get Zone
		$zones = $tagdetails->getElementsByTagName( "Zone" );
		$zone = $zones->item(0)->nodeValue;
		
		// Loop Through Recent Games
		$recentgames = $doc->getElementsByTagName( "XboxUserGameInfo" );
		foreach ($recentgames as $recentgame) {
			
			// Get Game Pic 32px
			$gameimage32s = $recentgame->getElementsByTagName( "Image32Url" );
			$gameimage32 = $gameimage32s->item(0)->nodeValue;
			
			// Get Game Pic 64px
			$gameimage64s = $recentgame->getElementsByTagName( "Image64Url" );
			$gameimage64 = $gameimage64s->item(0)->nodeValue;
			
			// Get Name
			$gamenames = $recentgame->getElementsByTagName( "Name" );
			$gamename = $gamenames->item(0)->nodeValue;
			
			// Get Achievements
			$gameachievements = $recentgame->getElementsByTagName( "Achievements" );
			$gameachievement = $gameachievements->item(0)->nodeValue;
			
			// Get Total Achievements
			$totalgameachievements = $recentgame->getElementsByTagName( "TotalAchievements" );
			$totalgameachievement = $totalgameachievements->item(0)->nodeValue;
			
			// Get Gamer Score
			$gamescores = $recentgame->getElementsByTagName( "GamerScore" );
			$gamescore = $gamescores->item(0)->nodeValue;
			
			// Get Total Gamer Score
			$totalgamescores = $recentgame->getElementsByTagName( "TotalGamerScore" );
			$totalgamescore = $totalgamescores->item(0)->nodeValue;
			
			// Find Game Image Size
			if (get_option('gis_myxboxprofile') == '64') { $gimage = $gameimage64; } else { $gimage = $gameimage32; }
			
			// Build game array
			$games[$gamename] = array('Image' => $gimage, 'Achievements' => $gameachievement, 'TotalAchievements' => $totalgameachievement, 'GamerScore' => $gamescore, 'TotalGamerScore' => $totalgamescore);
			
		}
		
	}

	// Display Gamercard
	echo '<div id="xboxgamercard">';
		echo '<a href="'.$gamerprourl.'" target="_blank"><img id="xboxgamercard_tileurl" src="'.$tileurl.'" alt="'.$gamertag.'" title="'.$gamertag.'" /></a>';
		echo '<ul id="gamerinfo">';
			echo '<li id="xboxgamercard_gamertag">'.$gamertag.'</li>';
			echo '<li id="xboxgamercard_info">'.$info.'</li>';
			echo '<li id="xboxgamercard_moreinfo">'.$moreinfo.'</li>';
			echo '<li id="xboxgamercard_zone">G: '.$gamerscore.'<span id="xboxgamercard_gamerscore">Zone: '.$zone.'</span></li>';
		echo '</ul>';
		
		// Display Recent Games
		if (get_option('srg_myxboxprofile') == 'yes') {
			if (!empty($games)) {
				$games = array_slice($games, 0, 5);
				
				$x=0;
				echo '<ul>'; 
				foreach ($games as $key => $val) {
					echo '<li class="show_gamedetails"><img id="game_id_'.$x.'" src="'.$val['Image'].'" alt="'.$key.'" title="'.$key.'" /></li>';
					$x++;
				}
				echo '</ul>';
				
				$x=0;
				echo '<div id="xboxgamercard_gameholder">';
				foreach ($games as $key => $val) {
					echo '<ul class="xboxgamercard_gamedetails" id="game_id_'.$x.'">';
						echo '<li id="xboxgamercard_name">'.$key.'</li>';
						echo '<li id="xboxgamercard_achievements">Achievements: '.$val['Achievements'].'/'.$val['TotalAchievements'].'</li>';
						echo '<li id="xboxgamercard_gamerscore">GamerScore: '.$val['GamerScore'].'/'.$val['TotalGamerScore'].'</li>';
					echo '</ul>';
					$x++;
				}		
				echo '</div>';
			}
			
		}
		
		echo '<div style="clear: both;"></div>';
	echo '</div>';
}
add_shortcode('myxboxprofile', 'myxboxprofile');

/*************************************************************************/
/*	Add Styles and Scripts to Header									 */
/*************************************************************************/
function meta_myxboxprofile() {
	echo "\n\n".'<!-- '.__('Start Of Code Generated By My Xbox Profile', 'myxboxprofile').' -->'."\n";
		wp_enqueue_style('myxboxprofile', WP_CONTENT_URL.'/plugins/my-xbox-profile/css/myxboxprofile.css', false);
		wp_print_styles('myxboxprofile');
		
		if (get_option('hidejs_myxboxprofile', '') == 'Yes') {
			wp_enqueue_script('jquery.js', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
			wp_print_scripts('jquery.js', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		}
		
		wp_enqueue_script('myxboxprofile_hide.js', WP_CONTENT_URL.'/plugins/my-xbox-profile/js/myxboxprofile_hide.js');
		wp_print_scripts('myxboxprofile_hide.js', WP_CONTENT_URL.'/plugins/my-xbox-profile/js/myxboxprofile_hide.js');
	echo '<!-- '.__('End Of Code Generated By My Xbox Profile', 'myxboxprofile').' -->'."\n\n";
}
add_action('wp_head', 'meta_myxboxprofile');


/*************************************************************************/
/*	Install Plugin Options												 */
/*************************************************************************/
function install_myxboxprofile() {
	// Gamertag
	add_option('tag_myxboxprofile', '', '', 'yes');
	// Game Image Size
	add_option('gis_myxboxprofile', '32', '', 'yes');
	// Show Recent Games
	add_option('srg_myxboxprofile', 'yes', '', 'yes');
	// Hide jQuery
	add_option('hidejs_myxboxprofile', 'yes', '', 'yes');
}
register_activation_hook( __FILE__, 'install_myxboxprofile' );


/*************************************************************************/
/*	Uninstall Plugin Options											 */
/*************************************************************************/
function uninstall_myxboxprofile() {
	// Gamertag
	delete_option('tag_myxboxprofile');
	// Game Image Size
	delete_option('gis_myxboxprofile');
	// Show Recent Games
	delete_option('srg_myxboxprofile');
	// Show JQuery
	delete_option('hidejs_myxboxprofile');
}
register_deactivation_hook( __FILE__, 'uninstall_myxboxprofile' );


/*************************************************************************/
/*	Plugin Admin Settings									 			 */
/*************************************************************************/
add_action('admin_menu', 'menu_myxboxprofile');

function menu_myxboxprofile() {
  add_options_page('My Xbox Profile Settings', 'My Xbox Profile', 'update_plugins', 'myxboxprofile', 'admin_myxboxprofile');
}

function admin_myxboxprofile() {
	global $wpdb;

	if($_POST['msl_hidden'] == 'Y') {  
		
		// Gamertag
		update_option('tag_myxboxprofile', $_POST['tag_myxboxprofile']);
		// Game Image Size
		update_option('gis_myxboxprofile', $_POST['gis_myxboxprofile']);
		// Show Recent Games
		update_option('srg_myxboxprofile', $_POST['srg_myxboxprofile']);
		// Show Recent Games
		update_option('hidejs_myxboxprofile', $_POST['hidejs_myxboxprofile']);
		
		$content = $_POST['savecontent'];
		$file = "../wp-content/plugins/my-xbox-profile/css/myxboxprofile.css";
		$Saved_File = fopen($file, 'w');
		fwrite($Saved_File, $content);
		fclose($Saved_File); 
		
		
		// Display Success Message
		$status_message = __('Your changes have been saved successfully!', 'myxboxprofile');
		echo "<div id='message' class='updated fade'><p><strong>".$status_message."</strong></p></div>";
		
	}
	
	echo '<div class="wrap">';
			echo "<div id='icon-plugins' class='icon32'></div><h2>" . __( 'My Xbox Profile Settings') . "</h2>";
			
			echo '<form name="msl_form" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">
					<h3>Gamertag Details:</h3>
					<table width="100%" class="form-table">
						<tr>
							<td width="200px"><label>Gamertag: </label></td>
							<td><input type="text" name="tag_myxboxprofile" value="'.get_option('tag_myxboxprofile').'" size="25"></td>
						</tr>
					</table>
					<h3>Advanced Settings (Optional):</h3>
					<table width="100%" class="form-table">
						<tr>
							<td width="200px"><label>Show Recent Games: </label></td>
							<td>';
								$srg_array = array('Yes', 'No');
								foreach ($srg_array as $val) {
									echo '<input ';
									if (get_option('srg_myxboxprofile') == strtolower($val)) {
										echo 'checked="checked" ';
									}
									echo 'type="radio" name="srg_myxboxprofile" value="'.strtolower($val).'" />'.$val.'&nbsp;';
								}
					  echo '</td>
						</tr>
						<tr>
							<td><label>Recent Game Image Size: </label></td>
							<td>';
								$gis_array = array('32', '64');
								foreach ($gis_array as $val) {
									echo '<input ';
									if (get_option('gis_myxboxprofile') == $val) {
										echo 'checked="checked" ';
									}
									echo 'type="radio" name="gis_myxboxprofile" value="'.$val.'" /> '.$val.'px&nbsp;';
								}
					  echo '<small>(Modification of the default CSS will be needed to use 64px images)</small></td>
						</tr>
						<tr>
							<td width="200px"><label>Hide jQuery File: </label></td>
							<td>';
								$srg_array = array('Yes', 'No');
								foreach ($srg_array as $val) {
									echo '<input ';
									if (get_option('hidejs_myxboxprofile') == strtolower($val)) {
										echo 'checked="checked" ';
									}
									echo 'type="radio" name="hidejs_myxboxprofile" value="'.strtolower($val).'" />'.$val.'&nbsp;';
								}
					  echo '</td>
						</tr>
					</table>
					<h3>Custom CSS:</h3>
					<table width="100%" class="form-table">
						<tr>
							<td colspan="2"><textarea name="savecontent" cols="120" rows="25">';
							$loadcontent = file_get_contents(WP_CONTENT_URL."/plugins/my-xbox-profile/css/myxboxprofile.css");
							echo $loadcontent;
							echo '</textarea></td>
						</tr>
					</table>
					<p><input class="button-primary" type="submit" name="Submit" value="Save Settings" /></p>
					<input type="hidden" name="msl_hidden" value="Y">
				</form>
			</div>';
				
}

/*************************************************************************/
?>