<?php
/**
* Plugin Name: Digital Manatee - Hour Log
* Plugin URI: http://digitalmanatee.com
* Description: A plugin to log the hours you have worked.
* Author: Cooper J. Eaton
* Author URI: http://digitalmanatee.com
* Version: 1.0.0
*/

// This denotes the current version of you database structure -- I don't think it's really neccesary
global $dm_hl_version;
$dm_hl_version = '1.0';

// This function will create your hourlog table in your SQL database.
function dm_hourlog_install () {

   global $wpdb;
   global $dm_hl_version;

   		$table_name = $wpdb->prefix . 'hourlog'; 

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
 				user_id tinytext NOT NULL,
 				job_number tinytext NOT NULL,
 				date date DEFAULT '0000-00-00' NOT NULL,
 				clock_in time DEFAULT '00:00:00' NOT NULL,
 				clock_out time DEFAULT '00:00:00' NOT NULL,
 				total time DEFAULT '00:00:00' NOT NULL,
 				phase tinytext NOT NULL,
 				notes text NOT NULL,
 				PRIMARY KEY  (id)
 		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
		add_option( 'dm_hl_version', $dm_hl_version );


}

// This function input some initial data into your hourlog -- really not sure how this works yet...
/*
function dm_hourlog_install_data() {
	global $wpdb;
	
		$welcome_name = 'Huey Manatee';
		$welcome_text = 'Congratulations, you just completed the installation!';
	
		$table_name = $wpdb->prefix . 'hourlog';
	
		$wpdb->insert( 
			$table_name, 
			array( 
				'tdate' => current_date( 'mysql' ), 
				'user' => $welcome_name, 
				'text' => $welcome_text, 
		) 
	);
}
*/

// This function will drop your hourlog table from your SQL database.
function dm_hourlog_drop()
{
    global $wpdb;
    	$table_name = $wpdb->prefix . 'hourlog';
    	$sql = "DROP TABLE IF EXISTS $table_name";
    	$wpdb->query($sql);
    	delete_option( 'dm_hl_version', $dm_hl_version );
}

// This will run the table creation function 'dm_hourlog_install' upon plugin activation
register_activation_hook( __FILE__, 'dm_hourlog_install' );

// This will run the initial data function 'dm_hourlog_install_data' upon plugin activation
/*
register_activation_hook( __FILE__, 'dm_hourlog_install_data' );
*/

// This will run the table drop function 'dm_hourlog_drop' upon plugin deactivation
register_deactivation_hook(__FILE__, 'dm_hourlog_drop' );

/* 

THIS IS WHERE THE FUN BEGINS! I am going to try and attempt to create a view in order to control the model above. Wish me luck!
----------------------------------------------------------------------------------------------------------
*/


//This should add the hourlog onto your admin menu
function dm_hourlog_menu(){
  	add_menu_page('DM - Hour Log', 'Hour Log', 'manage_options', 'dm_hourlog_menu', 'dm_hourlog_functions', 'dashicons-clock', '3');
}

add_action('admin_menu', 'dm_hourlog_menu');

//change


// This is the start of the basic GUI
function dm_hourlog_functions(){

	echo '

		<h1>Digital Manatee - Hour Log</h1><br>

	';

	$current_user = wp_get_current_user();
	echo '<h2>' . 'Welcome back, ' . $current_user->user_login . '!' . '</hr>' . '<br>' . '<br>';


// Here's the button to clock in and clock out - we'll use this button to add data to the table
	echo '
    
    	<input id="clockin_button" type="button" value="Clock In" onclick="ClockInClockOutButton()"></input>
		<script>
		function ClockInClockOutButton() {
    		if (document.getElementById("clockin_button").value=="Clock In"){
    		document.getElementById("clockin_button").value="Clock Out";
    		} else {
    		document.getElementById("clockin_button").value="Clock In";
    		}
		}
		</script>

	 ';

}



