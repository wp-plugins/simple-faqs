<?php
/**
 * Plugin Name: Simple FAQs
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: FAQ plugin to allow creating and showing FAQ easily on Wordpress website
 * Version: 1.1.1
 * Author: Waqas Ahmed
 * Author URI: http://speedsoftsol.com
 * License: GPL2
 */



/** Registering the Custom Post Types and the taxonomy at initialization **/
add_action( 'init', 'create_simple_faq_taxonomy', 0 );
function create_simple_faq_taxonomy() {	
	$simple_faq_args = 		array(
			'label' => 'Simple FAQs',
			'description' => 'Easy to manage FAQs with multiple layout styles',
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'page',
			'taxonomies' => array( 'faq_category'),
			'supports' => array('title','editor', 'revisions', 'page-attributes')	
	);
  	register_post_type( 'simple-faqs', $simple_faq_args); 	
  
  	$simple_faq_category_args = array(
		'hierarchical' => true,
		'label' => 'Simple FAQ Category',
		'show_ui' => true,
		'query_var' => true
  	);
  	register_taxonomy('faq_category',array('simple-faqs'), $simple_faq_category_args);  
}


/** Rendering the output when the shortcode is placed **/
function render_faqs( $atts ) {
	$parameter = extract( shortcode_atts( array(
		'category' => 'all',
		'style' => 'accordion',
	), $atts ) );

	$items = get_requested_faqs($category);
	switch (strtolower($style)) {
		case "accordion":
			add_action ('wp_head', 'initialize_accordion');
			$output = faq_style_accordion($items);
			break;
		case "simple":
			$output = faq_style_simple($items);
			break;
		case "bookmarks":
			$output = faq_style_bookmarks($items);
			break;
		default:
			add_action ('wp_head', 'initialize_accordion');
			$output = faq_style_accordion($items);
	}
	return $output;
}
add_shortcode( 'simple-faq', 'render_faqs' );


function get_requested_faqs ($parameter) {
	if ($parameter == 'all') {
		$args = array (
			'post_type' => 'simple-faqs',
			'orderby' => 'menu_order',
			'order'	=> 'ASC',
			'posts_per_page' => -1
			);
	}
	else {
		$args = array (
			'post_type' => 'simple-faqs',
			'orderby' => 'menu_order',
			'order'	=> 'ASC',
			'posts_per_page' => -1,
			'tax_query' => array (
				array(
					'taxonomy' => 'faq_category',
					'field' => 'slug',
					'terms' => $parameter
					)
				)
			);
	}
	
	$getRecords = new WP_Query($args);
	$output = array();
	$i = 0;
	while ($getRecords->have_posts() ) {
		$getRecords->the_post();
		$output[$i]['title'] = get_the_title();
		$output[$i]['content'] = get_the_content();
		$output[$i]['link'] = get_permalink();
		$i++;
	}
	wp_reset_postdata();
	return $output;
}


function faq_style_simple($items) {
	$output = '<ul class="simple-faq-list">';
	$item_number = 1;
	foreach ($items as $item) {
		$output .= '<li class="simple-faq-item simple-faq-number-'.$item_number.'">';
		$output .= '<h3>' . $item['title'] . '</h3>';
		$output .= '<br />' . $item['content'] . '</li>';
		$item_number++;
	}
	$output .= '</ul>';
	return $output;
}


function faq_style_bookmarks($items) {
	$item_number = 1;
	$output = '';
	
	//For the top bookmark list
	foreach ($items as $item) {
		$output .= '<a href="#simple-faq-item-' . $item_number . '" class="simple-faq-item simple-faq-item-number-' .$item_number.'">';
		$output .= $item['title'];
		$output .= '</a><br />';
		$item_number++;
	}

	$output .= '<div class="simple-faqs-detail">';
	//For the actual FAQ content below
	$item_number = 1; //Reset item counter
	foreach ($items as $item) {
		$output .= '<a id="simple-faq-item-' . $item_number . '"><h3 class="simple-faq-item simple-faq-item-number-' .$item_number.'">';
		$output .= $item['title'];
		$output .= '</h3></a>';
		$output .= $item['content'];
		$item_number++;		
	}
	$output .= '</div>';
	return $output;
}


function faq_style_accordion($items) {
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-accordion');

	echo "<script>
	jQuery(document).ready(function($){
		$( '#simple-faq-accordion' ).accordion({
			collapsible: true,
			heightStyle: 'content'
		});
	});
	</script>";

	$item_number = 1;
	$output = '<div id="simple-faq-accordion">';
	foreach ($items as $item) {
		$output .= '<h3>';
		$output .= $item['title'];
		$output .= '</h3><div>';
		$output .= $item['content'];
		$output .= '</div>';
		$item_number++;		
	}
	$output .= "</div>";
	return $output;
}