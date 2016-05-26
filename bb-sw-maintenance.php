<?php
/**
 * Plugin Name: Beaverlodge Maintenance
 * Plugin URI: https://www.beaverlodgehq.com
 * Description: Enable maintenance mode and use with Beaver Builder Template.
 * Version: 1.0
 * Author: Beaverlodge HQ
 * Author URI: https://www.beaverlodgehq.com
 */

function sw_maintenance_mode() {
	global $pagenow;
	if ( $pagenow !== 'wp-login.php' && ! current_user_can( 'manage_options' ) && ! is_admin() ) {
		header( $_SERVER["SERVER_PROTOCOL"] . ' 503 Service Temporarily Unavailable', true, 503 );
		header( 'Content-Type: text/html; charset=utf-8' );
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'maintenance.php' ) ) {
			require_once( plugin_dir_path( __FILE__ ) . 'maintenance.php' );
		}
		die();
	}
}

add_action( 'wp_loaded', 'sw_maintenance_mode' );



function sw_maintenance_mode_post() {

	$title = 'Maintenance Mode Template';
	$slug = 'sw-maintenance-template';
	$url = site_url();
	$page = get_page_by_title( $title, OBJECT, 'fl-builder-template');
	
	if( $page == NULL ) {
	
	        $maintenance_post = array(
			'post_title'    => $title,
			'post_name'     => $slug,
			'post_content'  => 'Please edit the <strong>Maintenance Mode Template</strong> <a href="'. $url .'/wp-admin/edit.php?post_type=fl-builder-template">Beaver Builder template</a> to create your design',
			'post_status'   => 'publish',
			'post_type' 	=> 'fl-builder-template'
		);

	}
	
	wp_insert_post( $maintenance_post );
	
}
register_activation_hook(__FILE__, 'sw_maintenance_mode_post');
