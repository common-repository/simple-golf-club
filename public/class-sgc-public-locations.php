<?php

/**
 * Class SGC_Public_Locations
 * All methods for public facing location posts
 * 
 * @author Matthew Linton
 *
 */

class SGC_Public_Locations { 
    /**
     * 
     */
    private static function get_id( $data ) {
        $location_id = NULL;

        if ( !empty($data['location_id']) ) {
            $theid = sanitize_key($data['location_id']);
            if( 'sgc_location' === get_post_type($theid) && 'publish' === get_post_status($theid) ) {
                $location_id = $theid;
            }

        } elseif( !empty( $data['location_name'] ) ) {
            $query = new WP_Query( array(
                'post_type'         => 'sgc_location',
                'post_status'       => 'publish',
                'name'              => $data['location_name'],
                'posts_per_page'    => 1,
            ));

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $location_id = get_the_ID(); // Get the ID of the found page
                }
            }
            
            wp_reset_postdata(); // Reset post data after the loop

        } elseif( 'sgc_location' === get_post_type() && 'publish' === get_post_status() ) {
            $location_id = get_the_id( );
        }

        return $location_id;
    }
    
    /**
     * 
     */
    public static function get_tees( $data ) {
        // Verify Location ID
        $location_id = SGC_Public_Locations::get_id( $data );
        
        if ( !is_null($location_id) ) {
            return array(
                'status' => 'success',
                'data' => json_decode( get_post_meta($location_id, 'sgc_location_tees', true) ),
                'message' => 'Retrieved Tee data for Location ' . get_the_title($location_id) . ' (' . $location_id . ')'
            );
        }
        
        // Create correct message for ID or Name
        $message = '[Unknown Location]';
        if ( !empty($data['location_id']) ) {
            $message = 'ID "' . esc_html($data['location_id']) . '"';
        } elseif( !empty( $data['location_name'] ) ) {
            $message = 'Name " ' . esc_html($data['location_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Location ' . $message
        );
    }
    
    /**
     * 
     */
    public static function get_events ( $data ) {
        // Verify Location ID
        $location_id = SGC_Public_Locations::get_id( $data );

        if ( !is_null($location_id) ) {
            // Set up defaults
            $date_start = '';
            $date_end = '';
            $order = 'DESC';      
            
            // Get passed values if they exist
            if( ! empty( $data ) ) {
                if( ! empty($data['start_date'] ) ) { $date_start = sanitize_key($data['start_date']); }
                if( ! empty($data['end_date'] ) ) { $date_end = sanitize_key($data['end_date']); }
                if( ! empty($data['sort'] ) ) { $order = sanitize_key($data['sort']); }
            }

            // fetch event list
            $events = get_posts(array(
                'post_status' => 'publish',
                'post_type' => 'sgc_event',
                'orderby' => 'date',
                'order' => $order,
                'posts_per_page' => -1,

                'meta_query' => array(
                    array(
                        'key' => 'sgc_event_location',
                        'value' => $location_id
                    )
                ),

                'date_query' => array(
                    array(
                        'after' => $date_start,
                        'before' => $date_end,
                        'inclusive' => true
                    ),
                    
                )
            ));
            
            $event_list = [];
            foreach( $events as $event ) {
                array_push( $event_list, array(
                    'time' => esc_attr(get_post_meta($event->ID, 'sgc_event_timestamp', true)),
                    'name' => esc_html($event->post_title),
                    'URL' => esc_url(get_the_permalink( $event->ID )),
                    'tee' => esc_attr(get_post_meta($event->ID, 'sgc_event_tee', true))
                ) );
            }
            
            return array(
                'status' => 'success',
                'data' => $event_list,
                'message' => 'Retrieved Event list for Location ' . get_the_title($location_id) . ' (' . $location_id . ')'
            );
        }

        // Create correct message for ID or Name
        $message = '[Unknown Location]';
        if ( !empty($data['location_id']) ) {
            $message = 'ID "' . esc_html($data['location_id']) . '"';
        } elseif( !empty( $data['location_name'] ) ) {
            $message = 'Name " ' . esc_html($data['location_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Location ' . $message
        );
    }

    /**
     * 
     */
    public static function sc_get_events( $attr ) {
        $eventslist = SGC_Public_Locations::get_events($attr);

        if( array_key_exists('status', $eventslist) && 'success' === $eventslist['status'] ) {
            // make sure we have the local server timezone
            date_default_timezone_set( get_option('timezone_string') );

            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-events-card.php' );
            return ob_get_clean();
        }
        
        return '<div class="sgc-sc-warning">' 
            . __('Could not find Events for this Location', SGC_TEXTDOMAIN)
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_tees( $attr ) {
        $teeslist = SGC_Public_Locations::get_tees($attr);
        
        if( array_key_exists('status', $teeslist) && 'success' === $teeslist['status'] && NULL !== $teeslist['data'] ) {
            $units = (get_option('sgc_default_units') === 'imperial') ? __('Yards', SGC_TEXTDOMAIN) : __('Meters', SGC_TEXTDOMAIN);

            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-tees-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . __('Could not find Tees for this Location', SGC_TEXTDOMAIN)
            . '</div>';
    }

    /**
     * 
     */
    public function add_shortcodes () {
        add_shortcode( 'sgc_location_events', array( 'SGC_Public_Locations', 'sc_get_events' ) );
        add_shortcode( 'sgc_location_tees', array( 'SGC_Public_Locations', 'sc_get_tees' ) );
    }

    /**
     * REST EVENTS
     */
    public function add_rest_events() {
        register_rest_route('simplegolfclub/v1', '/location/tees/(?P<location_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Locations', 'get_tees'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/location/events/(?P<location_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Locations', 'get_events'),
            'permission_callback' => '__return_true'
        ));
        
        // Add metadata to post data
        register_post_meta( 'sgc_location', 'sgc_location_par', array(
            'type'         => 'string',
            'description'  => 'Par',
            'single'       => true,
            'show_in_rest' => true
         ));
        register_post_meta( 'sgc_location', 'sgc_location_rating', array(
            'type'         => 'string',
            'description'  => 'Rating',
            'single'       => true,
            'show_in_rest' => true
         ));
        register_post_meta( 'sgc_location', 'sgc_location_slope', array(
            'type'         => 'string',
            'description'  => 'Slope',
            'single'       => true,
            'show_in_rest' => true
         ));
    }
}

// #### BEGIN publicaly accessible PHP function wrappers #######################
if (! function_exists( 'sgc_location_gettees' )) {
    function sgc_location_gettees( $data = [] ) {
        return SGC_Public_Locations::get_tees( $data );
    }
}
if (! function_exists( 'sgc_location_getevents' )) {
    function sgc_location_getevents( $data = [] ) {
        return SGC_Public_Locations::get_events( $data );
    }
}