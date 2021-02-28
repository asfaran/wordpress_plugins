<?php

/*
Plugin Name: wpvc leafcutter
Plugin URI: https://leafcutter.com.au
Description: An extension for Visual Composer that display  custom post type leafcutter Contents
Author: Mohamed Asfaran for Leafcutter
Version: 1.0.0
*/


// If this file is called directly, abort

if ( ! defined( 'ABSPATH' ) ) {
     die ('Silly human what are you doing here');
}


// Before VC Init

add_action( 'vc_before_init', 'wpvc_leafcutter_before_init_actions' );

function wpvc_leafcutter_before_init_actions() {

// Require new custom Element

include( plugin_dir_path( __FILE__ ) . 'wpvc-leafcutter-element.php');

}

// Link directory stylesheet

function wpvc_leafcutter_scripts() {
    wp_enqueue_style( 'wpc_community_directory_stylesheet',  plugin_dir_url( __FILE__ ) . 'styling/wpvc-leafcutter-styling.css' );
    wp_enqueue_script( 'isotope', plugin_dir_url( __FILE__ ) . '/js/isotope.min.js');
}
add_action( 'wp_enqueue_scripts', 'wpvc_leafcutter_scripts' );
