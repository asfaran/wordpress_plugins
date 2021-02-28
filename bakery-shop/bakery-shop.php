<?php
/**
 * Bakery Shop Manager
 *
 * @package     BakeryShop
 * @author      Mohamed Asfaran
 * @copyright   2020 Weberinc
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Bakery Shop Manager
 * Description: This plugin manage bakery shop stock and delivery and support woocomerce.
 * Version:     1.0.0
 * Author:      Mohamed Asfaran
 * Author URI:  https://weberinc.co
 * Text Domain: bakery-shop
 * License:     GPL v2 or later
 */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

function bakery_shop_scripts() {
    wp_enqueue_style( 'bakery_stylesheet',  plugin_dir_url( __FILE__ ) . 'styling/bakery-styling.css' );
    wp_enqueue_script( 'bakery_script', plugin_dir_url( __FILE__ ) . 'js/bakery.js', array( 'jquery' ), '1.2.0', true );
    //wp_enqueue_script( 'jquery' );
    
    wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js');
    
}
add_action( 'wp_enqueue_scripts', 'bakery_shop_scripts' );


function bakery_func_settings() {
   global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";
    // Require new custom Element
    include( plugin_dir_path( __FILE__ ) . 'general.php');
    print "</div>";

}
add_action('admin_menu', 'bakery_shop_manager_admin_menu');

//* Add select field to the checkout page
function bakery_func_delivery_opt_select( $checkout ) {

	echo '<h3>'.__('Select a Delivery Option').'</h3>';

	woocommerce_form_field( 'delivery_option', array(
	    'type'          => 'select',
	    'class'         => array( 'bakery-drop' ),
	    'label'         => __( 'Delivery options' ),
	    'options'       => array(
	    	'blank'		=> __( 'Select a Delivery Option', 'bakery' ),
	        'home_delivery'	=> __( 'Home delivery', 'bakery' ),
	        'pick_up'	=> __( 'Order pick up', 'bakery' ),
	        '3rd_party' 	=> __( '3rd party delivery', 'bakery' )
	    )
 ),

	$checkout->get_value( 'delivery_option' ));

}

add_action( 'woocommerce_after_checkout_billing_form', 'bakery_func_delivery_opt_select' );

//* Add timeslot to the checkout page
function bakery_func_pickup_time_select( $checkout ) {

	woocommerce_form_field( 'pickup_timeslot', array(
	    'type'          => 'select',
	    'class'         => array( 'bakery-drop' ),
	    'label'         => __( 'Pcikup timeslot' ),
	    'options'       => array(
	    	'blank'		=> __( 'Select a Pcikup timeslot', 'pickup' ),
	        '9am'	=> __( '9.00 am', 'pickup' ),
	        '11am'	=> __( '11.00 am', 'pickup' ),
	        '1pm' 	=> __( '1.00 pm', 'pickup' ),
	        '3pm' 	=> __( '3.00 pm', 'pickup' ),
	        '5pm' 	=> __( '5.00 pm', 'pickup' )
	    )
 ),

	$checkout->get_value( 'pickup_timeslot' ));

}
add_action('woocommerce_before_order_notes', 'bakery_func_pickup_time_select');

function bakery_shop_manager_admin_menu() {
	add_menu_page(
        'Bakery Shop Manager',// page title
        'Bakery Shop Manager',// menu title
        'manage_options',// capability
        'bakery-shop',// menu slug
        'bakery_func_settings', // callback function
        'dashicons-filter',
        '6'
    
    );
    add_submenu_page( 
		'bakery-shop', 
		'Manage Pickup Order',
		'Pickup Requests', 
		'manage_options', 
		'bakery-pickup-list', 
		'bakery_func_pickup_list'
	);

	add_submenu_page( 
		'bakery-shop', 
		'Manage Pickup Time Slot',
		'Pickup Time Slot', 
		'manage_options', 
		'bakery-time-slot', 
		'bakery_func_timeslot'
	);
	add_submenu_page( 
		'bakery-shop', 
		'Manage Daily Stock',
		'Daily Stock Manage', 
		'manage_options', 
		'bakery-daily-stock', 
		'bakery_func_daily_stock'
	);
	
}


function bakery_func_timeslot() {
   global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";
    // Require new custom Element
    include( plugin_dir_path( __FILE__ ) . 'time_slot.php');
    print "</div>";

}

function bakery_func_daily_stock() {
   global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";
    // Require new custom Element
    include( plugin_dir_path( __FILE__ ) . 'daily_stock.php');
    print "</div>";

}


function bakery_func_pickup_list() {
   global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";
    // Require new custom Element
    include( plugin_dir_path( __FILE__ ) . 'pickup_list.php');
    print "</div>";

}

