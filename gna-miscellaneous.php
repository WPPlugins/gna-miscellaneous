<?php
/*
Plugin Name: GNA Miscellaneous
Version: 1.0.5
Plugin URI: http://wordpress.org/plugins/gna-miscellaneous/
Author: Chris Mok
Author URI: http://webgna.com/
Description: Easy to set-up the some feature functions
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gna-miscellaneous
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('gna-miscellaneous-core.php');

register_activation_hook(__FILE__, array('GNA_Miscellaneous', 'activate_handler'));		//activation hook
register_deactivation_hook(__FILE__, array('GNA_Miscellaneous', 'deactivate_handler'));	//deactivation hook
