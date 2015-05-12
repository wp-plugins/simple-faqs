<?php
/**
 * Plugin Name: Simple FAQs
 * Plugin URI: http://wordpress.org/plugins/simple-faqs/
 * Description: FAQ plugin to allow creating and showing FAQ easily on Wordpress website
 * Version: 2.2
 * Author: Waqas Ahmed
 * Author URI: http://speedsoftsol.com
 * License: GPL2
 */


/** Add new button in TinyMCE Editor and associate a generator **/
add_action('admin_head', 'simple_faq_button');
function simple_faq_button() {
	add_filter("mce_external_plugins", "simple_faq_add_editor_button_script");
	add_filter('mce_buttons', 'simple_faq_button_register');
}

function simple_faq_add_editor_button_script($plugin_array) {
   	$plugin_array['simple_faq_button'] = plugins_url( '/simple_faq_button.js', __FILE__ );
   	return $plugin_array;
}

function simple_faq_button_register($buttons) {
   array_push($buttons, "faq_button");
   return $buttons;
}

function simple_faq_admin_styles() {
	wp_enqueue_style('simple-faq-admin', plugins_url('/simple_faq_generator.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'simple_faq_admin_styles');

 
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
		'skin' => 'none',
		'order' => 'default'
	), $atts ) );

	$simple_faq_skin = strtolower($skin);
	if (isset($simple_faq_skin) && $simple_faq_skin != "none") {
		if ($simple_faq_skin == "black") {
			wp_enqueue_style( 'simple-faq-skin', plugins_url( 'skins/black.css', __FILE__ )  );
		}
		elseif ($simple_faq_skin == "green") {
			wp_enqueue_style( 'simple-faq-skin', plugins_url( 'skins/green.css', __FILE__ )  );
		}
		elseif ($simple_faq_skin == "blue") {
			wp_enqueue_style( 'simple-faq-skin', plugins_url( 'skins/blue.css', __FILE__ )  );
		}
		elseif ($simple_faq_skin == "red") {
			wp_enqueue_style( 'simple-faq-skin', plugins_url( 'skins/red.css', __FILE__ )  );
		}
	}
	$items = get_requested_faqs($category, $order);
	switch (strtolower($style)) {
		case "accordion":
			$output = faq_style_accordion($items);
			break;
		case "simple":
			$output = faq_style_simple($items);
			break;
		case "bookmarks":
			$output = faq_style_bookmarks($items);
			break;
		default:
			$output = faq_style_accordion($items);
	}
	return do_shortcode($output);
}
add_shortcode( 'simple-faq', 'render_faqs' );


// Get all the FAQ you want - and in the correct order
function get_requested_faqs ($category, $order) {
	
	/** Possible order options are
		default, name, date
	**/
	switch (strtolower($order)) {
		case "default":
			$orderby = 'menu_order';
			break;
		case "date":
			$orderby = 'date';
			break;
		case "alphabetical":
			$orderby = 'name';
			break;
		default:
			$orderby = 'menu_order';
	}
	$category = strtolower($category);
	if ($category == 'all') {
		$args = array (
			'post_type' => 'simple-faqs',
			'orderby' => $orderby,
			'order'	=> 'ASC',
			'posts_per_page' => -1
			);
	}
	else {
		$args = array (
			'post_type' => 'simple-faqs',
			'orderby' => $orderby,
			'order'	=> 'ASC',
			'posts_per_page' => -1,
			'tax_query' => array (
				array(
					'taxonomy' => 'faq_category',
					'field' => 'slug',
					'terms' => array($category)
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
		$output .=  $item['content'] . '</li>';
		$item_number++;
	}
	$output .= '</ul>';
	return $output;
}


function faq_style_bookmarks($items) {
	wp_register_script( 'simple-faq-smooth-scroll', plugins_url( '/jquery.scrollTo-min.js', __FILE__ ) );
	wp_enqueue_script('simple-faq-smooth-scroll');
	wp_register_script( 'simple-faq-smooth-scroll-local', plugins_url( '/jquery.localScroll.min.js', __FILE__ ) );
	wp_enqueue_script('simple-faq-smooth-scroll-local');


	echo "<script>
	jQuery(document).ready(function($){
		$.localScroll({
			queue:true,
			duration:1000,
			hash:true
		});
	});
	</script>";

	$item_number = 1;
	$output = '<a id="simple-faq-top"></a>';
	
	//For the top bookmark list
	foreach ($items as $item) {
		$output .= '<a href="#simple-faq-item-' . $item_number . '" class="simple-faq-item simple-faq-item-number-' .$item_number.'">';
		$output .= $item['title'];
		$output .= '</a><br />';
		$item_number++;
	}

	$output .= '<div class="simple-faqs-bookmarks">';
	//For the actual FAQ content below
	$item_number = 1; //Reset item counter
	foreach ($items as $item) {
		$output .= '<a id="simple-faq-item-' . $item_number . '"><h3 class="simple-faq-item simple-faq-item-number-' .$item_number.'">';
		$output .= $item['title'];
		$output .= '</h3></a>';
		$output .= $item['content'];
		$output .= '<br /><a class="simple-faq-back" href="#simple-faq-top">Back To Top</a>';
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
		$( '.simple-faq-accordion' ).accordion({
			collapsible: true,
			heightStyle: 'content'
		});
	});
	</script>";

	$item_number = 1;
	$output = '<div class="simple-faq-accordion">';
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