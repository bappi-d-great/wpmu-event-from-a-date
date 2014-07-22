<?php
  /*
    Usage: [eab_event_date date="2014-07-05"], [eab_event_date date="today"], [eab_event_date date="tomorrow"]
  */
    
	function process_eab_event_date( $atts ) {
	      $atts = shortcode_atts( array(
		      'date' => date('d-m-y')
	      ), $atts );
		
		switch( $atts['date'] ){
			case 'today':
				$atts['date'] = date('d-m-y');
				break;
				
			case 'tomorrow':
				$datetime = new DateTime('tomorrow');
				$atts['date'] = $datetime->format('d-m-y');
				break;
				
			default:
				$atts['date'] = $atts['date'];	
		}
			
	      $date = explode( '-', $atts['date']);
	      
	      $args = array(
			'post_type' => 'incsub_event', 
			'post_status' => 'publish',
			'meta_key' => 'incsub_event_start',
			'meta_query' => array(
				array(
				'key' => 'incsub_event_start',
				'value' => array( $date[0] . '-' . $date[1] . '-' . $date[2] . ' 00:00:00', $date[0] . '-' . $date[1] . '-' . $date[2] . ' 23:59:59' ) ,
				'compare' => 'BETWEEN',
				'type' => 'DATETIME'
				)
			),
			'posts_per_page' => apply_filters('eab-collection-upcoming-max_results', EAB_MAX_UPCOMING_EVENTS),
		);
		$posts = get_posts( $args );
		$output = Eab_Template::util_apply_shortcode_template($posts, array('template' => 'get_shortcode_archive_output'));
		wp_enqueue_style('eab_front');
		wp_enqueue_script('eab_event_js');
		return $output;
	      
	}
	add_shortcode( 'eab_event_date', 'process_eab_event_date' );
