<?php

  define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 960 ) );
  define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 380 ) );
  
  add_action( 'after_setup_theme', 'aie_setup' );
  function aie_setup(){
    /* Add additional default headers*/
    $aie_dir = get_bloginfo('stylesheet_directory');
    
    register_default_headers(array(
        'aie1' => array (
            'url' => "$aie_dir/images/header.jpg",
            'thumbnail_url' => "$aie_dir/images/header-thumbnail.jpg",
            'description' => __( 'Glede Pa Reisen Header', 'gledepareisen' )
        )
    ));
    
    if ((function_exists('get_the_subheading'))) add_filter('wp_page_menu_args', 'subtitled_page_filter');
    remove_filter( 'wp_page_menu_args', 'twentyeleven_page_menu_args' );
  }
  
  class SubtitledPages extends Walker_Page {
    function start_el(&$output, $page, $depth, $args, $current_page) {
  		if ( $depth )
  			$indent = str_repeat("\t", $depth);
  		else
  			$indent = '';

  		extract($args, EXTR_SKIP);
  		$css_class = array('page_item', 'page-item-'.$page->ID);
  		if ( !empty($current_page) ) {
  			$_current_page = get_page( $current_page );
  			_get_post_ancestors($_current_page);
  			if ( isset($_current_page->ancestors) && in_array($page->ID, (array) $_current_page->ancestors) )
  				$css_class[] = 'current_page_ancestor';
  			if ( $page->ID == $current_page )
  				$css_class[] = 'current_page_item';
  			elseif ( $_current_page && $page->ID == $_current_page->post_parent )
  				$css_class[] = 'current_page_parent';
  		} elseif ( $page->ID == get_option('page_for_posts') ) {
  			$css_class[] = 'current_page_parent';
  		}

  		$css_class = implode(' ', apply_filters('page_css_class', $css_class, $page));

  		$output .= $indent . '<li class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '" title="' . esc_attr( wp_strip_all_tags( apply_filters( 'the_title', $page->post_title, $page->ID ) ) ) . '">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . "<em>" . get_the_subheading($page->ID) . "</em>" . $link_after . '</a>';

  		if ( !empty($show_date) ) {
  			if ( 'modified' == $show_date )
  				$time = $page->post_modified;
  			else
  				$time = $page->post_date;

  			$output .= " " . mysql2date($date_format, $time);
  		}
  	}
  }
  
  function subtitled_page_filter($args) {
    $args = array_merge($args, array('walker' => new SubtitledPages()));
    return $args;
  }
  
?>