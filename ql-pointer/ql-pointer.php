<?php
/**
 * Plugin Name: QueryLoop Simple Pointers
 * Plugin URI: http://queryloop.com
 * Description: Facilitates the creation of single pointers for WP Admin.
 * Author: QueryLoop
 * Author URI: http://queryloop.com
 * Version: 1.0.0
 * Text Domain: queryloop
 * Domain Path: /languages
 */

if ( !defined( 'WPINC' ) ) {
	die;
}

// Load and initialize class. If you're loading the QL_Pointer class in another plugin or theme, this is all you need.
require_once 'class-ql-pointer.php';
new QL_Pointer();

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////// Everything after this point is only for pointers configuration ///////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Initialize localization routines. If you're already doing it in your plugin or theme dismiss this.
 *
 * @since 1.0.0
 */
function queryloop_pointer_test_localization() {
	load_plugin_textdomain( 'queryloop', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'queryloop_pointer_test_localization' );

/**
 * Add pointers.
 *
 * @param $pointers
 * @param $prefix
 *
 * @return mixed
 */
function custom_queryloop_initial_pointers( $pointers, $prefix ) {
	return array_merge( $pointers, array(
		$prefix . '_settings' => array(
			'selector' => '#menu-settings',
			'title' => '<h3>' . __( 'QueryLoop Test Pointer', 'queryloop' ) . '</h3>',
			'text' => '<p>' . __( 'The plugin is active and ready to start working.', 'queryloop' ) . '</p>',
			'edge' => 'left',
			'align' => 'middle',
			'width' => 260,
			'class' => 'ql-pointer',
		),
		$prefix . '_posts' => array(
			'selector' => '#menu-posts',
			'title' => '<h3>' . __( 'Pointer for Posts', 'queryloop' ) . '</h3>',
			'text' => '<p>' . __( 'One more pointer.', 'queryloop' ) . '</p>',
			'edge' => 'left',
			'align' => 'middle',
			'width' => 350,
			'class' => 'ql-pointer',
		),
		$prefix . '_pages' => array(
			'selector' => '#menu-pages',
			'title' => '<h3>' . __( 'Pages Pointer', 'queryloop' ) . '</h3>',
			'text' => '<p>' . __( 'A pointer for pages.', 'queryloop' ) . '</p>',
			'edge' => 'left',
			'align' => 'middle',
			'width' => 300,
			'class' => 'ql-pointer',
		),
		$prefix . '_show-settings-link' => array(
			'selector' => '#show-settings-link',
			'title' => '<h3>' . __( 'Help Pointer', 'queryloop' ) . '</h3>',
			'text' => '<p>' . __( 'A pointer for help tab.', 'queryloop' ) . '</p>',
			'edge' => 'top',
			'align' => 'right',
			'width' => 300,
			'class' => 'ql-pointer',
		)
	) );
}
add_filter( 'queryloop_initial_pointers', 'custom_queryloop_initial_pointers', 10, 2 );