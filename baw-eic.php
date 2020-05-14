<?php
/*
Plugin Name: BAW Easy Invitation Codes
Plugin URI: https://github.com/stephenhouser/Easy-Invitation-Codes
Description: Visitors have to enter an invitation code to register on your BuddyPress site. The easy way!
Version: 1.2
Author: Stephen Houser
Author URI: http://stephenhouser.com
License: GPLv2
*/

define( 'BAWEIC__FILE__', __FILE__ );

if ( ! is_admin() ) {
	include( 'inc/front-end.php' );
} else {
	include( 'inc/back-end.php' );
}