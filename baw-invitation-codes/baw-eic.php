<?php
/*
Plugin Name: BAW Easy Invitation Codes
Plugin URI: http://boiteaweb.fr/
Description: Visitors have to enter an invitation code to register on your blog. The easy way!
Version: 1.2
Author: Juliobox
Author URI: http://wp-rocket.me
License: GPLv2
*/

define( 'BAWEIC__FILE__', __FILE__ );

if ( ! is_admin() ) {
	include( 'inc/front-end.php' );
} else {
	include( 'inc/back-end.php' );
}