<?php

/**
 * Class SGC_Public_Teams
 * All methods for public facing team posts
 * 
 * @author Matthew Linton
 *
 */

class SGC_Public_Teams { 
    /**
     * 
     */
    private static function get_id( $data ) {
        $team_id = NULL;
        
        if ( !empty($data['team_id']) ) {
            $theid = sanitize_key($data['team_id']);
            if( 'sgc_team' === get_post_type($theid) && 'publish' === get_post_status($theid) ) {
                $team_id = $theid;
            }
            
        } elseif( !empty( $data['team_name'] ) ) {
            $query = new WP_Query( array(
                'post_type'         => 'sgc_team',
                'post_status'       => 'publish',
                'name'              => $data['team_name'],
                'posts_per_page'    => 1,
            ) );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $team_id = get_the_ID(); // Get the ID of the found page
                }
            }
        
            wp_reset_postdata(); // Reset post data after the loop

        } elseif( 'sgc_team' === get_post_type() && 'publish' === get_post_status() ) {
            $team_id = get_the_id( );
        }

        return $team_id;
    }
    
    /**
     *
     */
    public static function get_players( $data ) {
        // Verify Team ID
        $team_id = SGC_Public_Teams::get_id( $data );

        if ( !is_null($team_id) ) {
            $player_list = [];

            // Build the team player array
            foreach( get_post_meta( $team_id, 'sgc_team_player', false) as $player_id ) {
                array_push( $player_list, array(
                    'ID' => $player_id,
                    'name' => esc_html(get_the_title( $player_id )),
                    'URL' => esc_url(get_the_permalink( $player_id ))
                ));
            }

            return array(
                'status' => 'success',
                'data' => $player_list,
                'message' => 'Retrieved Player list for Team ' . get_the_title($team_id) . ' (' . $team_id . ')'
            );
        }

        // Create correct message for ID or Name
        $message = '[Unknown Team]';
        if ( !empty($data['team_id']) ) {
            $message = 'ID "' . esc_html($data['team_id']) . '"';
        } elseif( !empty( $data['team_name'] ) ) {
            $message = 'Name "' . esc_html($data['team_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Team with ' . $message .'"'
        );
    }
    
    /**
     * 
     */
    public static function get_events( $data ) {
        // Verify Team ID
        $team_id = SGC_Public_Teams::get_id( $data );
        
        if ( !is_null($team_id) ) {
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
                'orderby' => 'post_title',
                'order' => $order,
                'posts_per_page' => -1,

                'meta_query' => array(
                    array(
                        'key' => 'sgc_event_team',
                        'value' => $team_id
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
                    'URL' => esc_url(get_the_permalink($event->ID)),
                    'tee' => esc_attr(get_post_meta($event->ID, 'sgc_event_tee', true))
                ) );
            }
            
            return array(
                'status' => 'success',
                'data' => $event_list,
                'message' => 'Retrieved Events for Team ' . get_the_title($team_id) . ' (' . $team_id . ')'
            );
        }
        
        // Create correct message for ID or Name
        $message = '[Unknown Team]';
        if ( !empty($data['team_id']) ) {
            $message = 'ID "' . esc_html($data['team_id']) . '"';
        } elseif( !empty( $data['team_name'] ) ) {
            $message = 'Name "' . esc_html($data['team_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Team with ' . $message .'"'
        );
    }

    /**
     * 
     */
    public static function sc_get_events( $attr ) {
        $eventslist = SGC_Public_Teams::get_events($attr);

        if( array_key_exists('status', $eventslist) && 'success' === $eventslist['status'] ) {
            // make sure we have the local server timezone
            date_default_timezone_set( get_option('timezone_string') );

            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-events-card.php' );
            return ob_get_clean();
        }
        
        return '<div class="sgc-sc-warning">' 
            . esc_html($eventslist['message'])
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_players( $attr ) {
        $playerslist = SGC_Public_Teams::get_players($attr);
        
        if( array_key_exists('status', $playerslist) && 'success' === $playerslist['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-players-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . esc_html($playerslist['message'])
            . '</div>';
    }

    /**
     * 
     */
    public function add_shortcodes () {
        add_shortcode( 'sgc_team_events', array( 'SGC_Public_Teams', 'sc_get_events' ) );
        add_shortcode( 'sgc_team_players', array( 'SGC_Public_Teams', 'sc_get_players' ) );
    }

    /**
     *
     */
    public function add_rest_events() {
        register_rest_route( 'simplegolfclub/v1', '/team/players/(?P<team_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Teams', 'get_players'),
            'permission_callback' => '__return_true'
        ) );
        register_rest_route( 'simplegolfclub/v1', '/team/events/(?P<team_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Teams', 'get_events'),
            'permission_callback' => '__return_true'
        ) );
    }
}

    

// #### BEGIN publicaly accessible PHP function wrappers #######################
if (! function_exists( 'sgc_team_getplayers' )) {
    function sgc_team_getplayers( $data = [] ) {
        return SGC_Public_Teams::get_players( $data );
    }
}
if (! function_exists( 'sgc_team_getevents' )) {
    function sgc_team_getevents( $data = [] ) {
        return SGC_Public_Teams::get_events( $data );
    }
}