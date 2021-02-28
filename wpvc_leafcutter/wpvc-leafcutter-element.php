<?php

/**
* Adds new shortcode "info-box-shortcode" and registers it to
* the WPBakery Visual Composer plugin
*
*/





// If this file is called directly, abort

if ( ! defined( 'ABSPATH' ) ) {
    die ('Silly human what are you doing here');
}


if ( ! class_exists( 'vcInfoBox' ) ) {

	class vcInfoBox {


		/**
		* Main constructor
		*
		*/
		public function __construct() {

			// Registers the shortcode in WordPress
			add_shortcode( 'info-box-shortcode', array( 'vcInfoBox', 'output' ) );

			// Map shortcode to Visual Composer
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'info-box-shortcode', array( 'vcInfoBox', 'map' ) );
			}


			
		}


		/**
		* Map shortcode to VC
    *
    * This is an array of all your settings which become the shortcode attributes ($atts)
		* for the output.
		*
		*/
		public static function map() {

			$categories_array = array();
			$categories_list = get_categories();
			foreach( $categories_list as $category ){
			$categories_array[$category->name] = $category->term_id;
			}

			
		  
             

			return array(
				'name'        => esc_html__( 'Training Programs', 'text-domain' ),
				'description' => esc_html__( 'Add new listing', 'text-domain' ),
				'base'        => 'vc_infobox',
				'category' => __('Leafcutter', 'text-domain'),
				'icon' => plugin_dir_path( __FILE__ ) . 'assets/img/note.png',
				'params'      => array(
					array(
                        'type' => 'textfield',
                        'holder' => 'h3',
                        'class' => 'lc-wpvc-title-class',
                        'heading' => __( 'Post Title', 'text-domain' ),
                        'param_name' => 'posttitle',
                        'value' => __( '', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Listing',
                    ),
					array(
                        'type' => 'textarea',
                        'holder' => 'h3',
                        'class' => 'lc-wpvc-subtitle-class',
                        'heading' => __( 'Sub Title', 'text-domain' ),
                        'param_name' => 'subtitle',
                        'value' => __( '', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Listing',
					),
					array(
						'type' => 'dropdown',
						'heading' => 'category Select',
						'param_name' => 'category_type',
						'description' => 'select a category',
						'value' => $categories_array, 
						'group' => 'Listing',
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Number Columns Per Row',
						'param_name' => 'column_number',
						'description' => 'Select How many Column for Row',
						'value' => array('Default', '1', '2', '3', '4'), 
						'group' => 'Listing',
					),
                    array(
						'type' => 'textfield',
						'heading' => __( 'Number of posts', 'text-domain' ),
						'description' => __( 'Enter number of posts to display.', 'text-domain' ),
						'param_name' => 'number',
						'value' => get_option('posts_per_page'),
						'admin_label' => true,
						'group' => 'Listing',
					),
 					array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __( 'Post Title Color', 'text-domain' ),
                        'param_name' => 'posttitle_Color',
                        'value' => __( '#1D1E1C', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Style',
					),
					array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __( 'Sub Title Color', 'text-domain' ),
                        'param_name' => 'subtitle_Color',
                        'value' => __( '#1D1E1C', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Style',
					),

				),
			);
		}


		/**
		* Shortcode output
		*
		*/
		public static function output( $atts, $content = null ) {

			extract(
				shortcode_atts(
					array(
						'posttitle'   => '',
						'column_number'   => '',
						'subtitle'   => '',
                        'number'   => get_option('posts_per_page'),
                        'category_type'   => '',
						'posttitle_Color'   => '',
						'subtitle_Color'   => '',
					),
					$atts
				)
			);

		  $img_url = wp_get_attachment_image_src( $bgimg, "large");
		  $posts_per_page = ($atts['number'])? $atts['number'] : "12" ;
          
        $args = array(
			'posts_per_page' => $posts_per_page,
			'category__in'   => $category_type
			// 'post_type' => 'leafcutter',
			
		);
		
        
		
		$row_col_size =  12 / $column_number ;

		$args_category = array(
			'type'                     => 'post',
			'child_of'                 =>  $category_type,
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => FALSE,
			'hierarchical'             => 1,
			'taxonomy'                 => 'category',

		);
		$leafcutter_categories = get_categories( $args_category );

		$leafcutter_categories_select_html = "<select  class='program-filter' name='program_filter'>";
		$leafcutter_categories_select_html .= "<option value='*'>Show All</option>";

		foreach($leafcutter_categories as $category) {
			$leafcutter_categories_select_html .= "<option value='.".$category->slug."'>".$category->name."</option>";
		}
		$leafcutter_categories_select_html .= "</select>";
        
       
 

        $q = new \WP_Query($args);
        
        // Fill $html var with data
        $html = '
        <div class="wpvc-leafcutter-wrap">

            <h2 class="wpvc-leafcutter-title" style="color:'.$posttitle_Color.'">' . $posttitle . '</h2>

			<div class="wpvc-leafcutter-text" style="color:'.$subtitle_Color.'">'. $subtitle .'</div>
			<div class="wpvc-leafcutter-category" >'. $leafcutter_categories_select_html .'</div>

		</div>';



		$html .= '<div class="wpvc-leafcutter-container grid ">';		
		if( $q->have_posts() ) :

			while( $q->have_posts() ) : $q->the_post();
			$work_category           = get_the_category( $q->post->ID );
			$work_category_slug = (array) null;
			foreach ( $work_category as $key => $value) { $work_category_slug[] = $value->category_nicename ; }
			$work_category_slug = implode(" ",$work_category_slug);

			$read_more_button_text = get_post_meta($q->post->ID, 'read_more_button_text', true);
			$register_button_text = get_post_meta($q->post->ID, 'register_button_text', true);

			$html .= '<div class="wpvc-leafcutter-block element-item  '.$work_category_slug.' vc_col-xs-12 vc_col-sm-' . $row_col_size . '"   >';	

			$html .= '<div class="row list-title"><h3 class="display-3">' . $q->post->post_title . '</h3></div>';
			$html .= '<div class="row list-excerpt"><p class="lead">' . $q->post->post_excerpt . '</p></div>';
			$html .= '<div class="row button-row">
						<div class="vc_col-xs-12 vc_col-sm-6">
							<span class="list-button-left" ><a class="btn btn-lg btn-success" href="' . get_post_meta($q->post->ID, 'registration_url', true) . '" role="button">' . (($register_button_text != '')? $register_button_text : 'Register' ). '</a></span>
						</div>
						<div class="vc_col-xs-12 vc_col-sm-6">
							<span class="list-button-right"><a class="btn btn-lg btn-success" href="' . get_permalink( $q->post->ID ) . '" role="button">' . (($read_more_button_text != '')? $read_more_button_text : 'Learn more' ). '</a></span>
						</div>
				</div>';
			$html .= '</div>';
 
			endwhile;
 
			$html .= '</div>';
		else:
			$html .= '<p>No posts found</p>';
		endif;
 
		$html .= '</div></div>';

		return $html;
		

		}

	}

}
new vcInfoBox;
