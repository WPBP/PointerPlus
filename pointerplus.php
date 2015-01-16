<?php

/**
 * Plugin Name: Simple Pointers with PointerPlus
 * Plugin URI: https://github.com/Mte90/pointerplus
 * Description: Facilitates the creation of single pointers for WP Admin.
 * Author: Mte90 & QueryLoop
 * Author URI: http://mte90.net
 * Version: 1.0.0
 * Text Domain: your-domain
 * Domain Path: /languages
 */
if ( !defined( 'WPINC' ) ) {
	die;
}

// Load and initialize class. If you're loading the PointerPlus class in another plugin or theme, this is all you need.
require_once 'class-pointerplus.php';
new PointerPlus( array( 'prefix' => 'your-domain' ) );

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////// Everything after this point is only for pointerplus configuration ///////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Initialize localization routines. If you're already doing it in your plugin or theme dismiss this.
 *
 * @since 1.0.0
 */
function pointerplus_load_localization() {
	load_plugin_textdomain( 'your-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'pointerplus_load_localization' );

/**
 * Add pointers.
 *
 * @param $pointers
 * @param $prefix for your pointers
 *
 * @return mixed
 */
function custom_initial_pointers( $pointers, $prefix ) {
	/*
	 * Default parameters:
	  $defaults = array(
	  'class' => 'pointerplus',
	  'width' => 300, //fixed value
	  'align' => 'middle',
	  'edge' => 'left',
	  'post_type' => array(),
	  'pages' => 'array(),
	  );
	 */

	return array_merge( $pointers, array(
		$prefix . '_settings' => array(
			'selector' => '#menu-settings',
			'title' => __( 'PointerPlus Test', 'your-domain' ),
			'text' => __( 'The plugin is active and ready to start working.', 'your-domain' ),
			'width' => 260,
			'icon_class' => 'dashicons-admin-settings',
		),
		$prefix . '_posts' => array(
			'selector' => '#menu-posts',
			'title' => __( 'PointerPlus for Posts', 'your-domain' ),
			'text' => __( 'One more pointer.', 'your-domain' ),
			'post_type' => array( 'post' ),
			'icon_class' => 'dashicons-admin-post',
			'width' => 350,
		),
		$prefix . '_pages' => array(
			'selector' => '#menu-pages',
			'title' => __( 'PointerPlus Pages', 'your-domain' ),
			'text' => __( 'A pointer for pages.', 'your-domain' ),
			'post_type' => array( 'page' ),
			'icon_class' => 'dashicons-admin-post'
		),
		$prefix . '_users' => array(
			'selector' => '#menu-users',
			'title' => __( 'PointerPlus Users', 'your-domain' ),
			'text' => __( 'A pointer for users.', 'your-domain' ),
			'pages' => array( 'users.php' ),
			'icon_class' => 'dashicons-admin-users'
		),
		$prefix . '_contextual_tab' => array(
			'selector' => '#show-settings-link',
			'title' => __( 'PointerPlus Help', 'your-domain' ),
			'text' => __( 'A pointer for help tab.<br>Go to Posts, Pages or Users for other pointers.', 'your-domain' ),
			'edge' => 'top',
			'align' => 'right',
			'icon_class' => 'dashicons-welcome-learn-more'
		)
			) );
}

add_filter( 'pointerplus_list', 'custom_initial_pointers', 10, 2 );
