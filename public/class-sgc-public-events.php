<?php

/**
 * Class SGC_Public_Events
 * All methods for public facing event posts
 * 
 * @author Matthew Linton
 *
 */

class SGC_Public_Events {
    /**
     * 
     */
    private static function get_id( $data ) {
        $event_id = NULL;
        
        if ( !empty($data['event_id']) ) {
            $theid = sanitize_key($data['event_id']);
            if( 'sgc_event' === get_post_type($theid) && 'publish' === get_post_status($theid) ) {
                $event_id = $theid;
            }

        } elseif( !empty( $data['event_name'] ) ) {
            $query = new WP_Query( array(
                'post_type'         => 'sgc_event',
                'post_status'       => 'publish',
                'name'              => $data['event_name'],
                'posts_per_page'    => 1,
            ) );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $event_id = get_the_ID(); // Get the ID of the found page
                }
            }
        
            wp_reset_postdata(); // Reset post data after the loop

        } elseif( 'sgc_event' === get_post_type() && 'publish' === get_post_status() ) {
            $event_id = get_the_id( );
        }

        return $event_id;
    }

    /**
     * 
     */
    private static function get_player_id( $data ) {
        $player_id = NULL;
        
        if ( !empty($data['player_id']) ) {
            $theid = sanitize_key($data['player_id']);
            if( 'sgc_player' === get_post_type($theid) ) {
                $player_id = $theid;
            }

        } elseif( !empty( $data['player_name'] ) ) {
            $query = new WP_Query( array(
                'post_type'         => 'sgc_player',
                'post_status'       => 'publish',
                'name'              => $data['player_name'],
                'posts_per_page'    => 1,
            ) );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $player_id = get_the_ID(); // Get the ID of the found page
                }
            }
        
            wp_reset_postdata(); // Reset post data after the loop

        } else {
            $player_id = get_the_id( );
        }

        return $player_id;
    }


    /**
     * 
     */
    public static function get_info( $data ) {
        // Verify Event ID
        $event_id = SGC_Public_Events::get_id( $data );

        if ( !is_null($event_id) ) {
            // get the team name and team post URL
            $team = get_post_meta($event_id, 'sgc_event_team', true);
            $team_name = '';
            $team_url = '';
            if ( ! empty( $team ) ) {
                $team_name = esc_html(get_the_title( $team ));
                $team_url = esc_url(get_the_permalink( $team ));
            } 
            
            // get the location name and location post URL and the tees
            $location = get_post_meta($event_id, 'sgc_event_location', true);
            $location_name = '';
            $location_url = '';
            if ( ! empty( $location ) ) {
                $location_name = get_the_title( $location );
                $location_url = get_the_permalink( $location );            
            }
            
            date_default_timezone_set( get_option('timezone_string') );
            return array(
                'status' => 'success',
                'data' => array( 
                    'ID' => $event_id,
                    'name' => get_the_title($event_id),
                    'URL' => get_permalink($event_id),
                    'time' => esc_html(get_post_meta($event_id, 'sgc_event_timestamp', true)),
                    'team_name' => esc_html($team_name),
                    'team_id' => esc_html($team),
                    'team_url' => esc_url($team_url),
                    'location_name' => esc_html($location_name),
                    'location_id' => esc_html($location),
                    'location_url' => esc_url($location_url),
                    'tee' => esc_html(get_post_meta($event_id, 'sgc_event_tee', true))
                ),
                'message' => 'Retrieved info for Event ' . get_the_title($event_id) . ' (' . $event_id . ')'
            );
        }
        
        // Create correct message for ID or Name
        $message = '[Unknown Event]';
        if ( !empty($data['event_id']) ) {
            $message = 'ID "' . esc_html($data['event_id']) . '"';
        } elseif( !empty( $data['event_name'] ) ) {
            $message = 'Name "' . esc_html($data['event_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Event "' . $message .'"'
        );
    }
    
    /**
     * 
     */
    public static function get_tees( $data ) {
        // Verify Event ID
        $event_id = SGC_Public_Events::get_id( $data );

        if ( !is_null($event_id) ) {
            // Get location ID. Exit if none exists
            $location_id = get_post_meta( $event_id, 'sgc_event_location', true );

            if ( !empty( $location_id ) ) { 
                // Get Tee List. Exit if empty (success)
                $tees_list = get_post_meta($location_id, 'sgc_location_tees', true );
                if( !empty( $tees_list ) ) {
                    return array(
                        'status' => 'success',
                        'data' => json_decode( $tees_list ),
                        'message' => 'Retrieved Tee List for event ' . get_the_title($event_id) . ' (' . $event_id . ')'
                    );
                }
                
                return array(
                    'status' => 'success',
                    'data' => array(),
                    'message' => 'Tee list is empty for Event ' . get_the_title($event_id) . ' (' . $event_id . ') '
                );
            }
            
            return array(
                'status' => 'failed',
                'data' => array(),
                'message' => 'Unable to find Location ID ' . $location_id
            );
        }

        // Create correct message for ID or Name
        $message = '[Unknown Event]';
        if ( !empty($data['event_id']) ) {
            $message = 'ID "' . esc_html($data['event_id']) . '"';
        } elseif( !empty( $data['event_name'] ) ) {
            $message = 'Name "' . esc_html($data['event_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Event "' . $message .'"'
        );
    }
    
    /**
     * 
     */
    public static function get_groups( $data ) {
        // Verify Event ID
        $event_id = SGC_Public_Events::get_id( $data );

        if ( !is_null($event_id) ) {
            $group_list = get_post_meta($event_id, 'sgc_event_group_list', true);
            
            // return the list of groups
            return array(
                'status' => 'success',
                'data' => json_decode( filter_var( $group_list ), FILTER_FLAG_NO_ENCODE_QUOTES),
                'message' => 'Retrieved Groups List for Event ' . get_the_title($event_id) . ' (' . $event_id . ')'
            );
        }
        
        // Create correct message for ID or Name
        $message = '[Unknown Event]';
        if ( !empty($data['event_id']) ) {
            $message = 'ID "' . esc_html($data['event_id']) . '"';
        } elseif( !empty( $data['event_name'] ) ) {
            $message = 'Name "' . esc_html($data['event_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Event "' . $message .'"'
        );
    }
    
    /**
     * 
     */
    public static function get_players( $data ) {
        // Verify Event ID
        $event_id = SGC_Public_Events::get_id( $data );

        if ( !is_null($event_id) ) {
            // Get Team ID. Exit if none exists
            $team_id = get_post_meta( $event_id, 'sgc_event_team', true);

            if ( !empty( $team_id ) ) { 
                // Build the team player array
                $player_list = [];
                $checkin_list = get_post_meta( $event_id, 'sgc_team_player_checkedin', false);

                foreach( get_post_meta( $team_id, 'sgc_team_player', false) as $player_id ) {
                    // See if this player is checked in
                    $checkedin = 'false';
                    foreach( $checkin_list as $here ) {
                        if( $player_id == $here ) {
                            $checkedin = 'true';
                        }
                    }
                    
                    // Don't hash empty email or phone numbers
                    $player_email = get_post_meta($player_id, 'sgc_player_email', true);
                    if( !empty($player_email) ) {
                        $player_email = wp_hash( $player_email );
                    }
                    $player_phone = get_post_meta($player_id, 'sgc_player_phone', true);
                    if( !empty($player_phone) ) {
                        $player_phone = wp_hash( $player_phone );
                    }
                    
                    // Add the player
                    array_push( $player_list, array(
                        'ID' => $player_id,
                        'name' => esc_html(get_the_title( $player_id )),
                        'URL' => esc_url(get_the_permalink( $player_id )),
                        'email' => $player_email,
                        'phone' => $player_phone,
                        'checkedin' => $checkedin
                    ));
                }

                return array(
                    'status' => 'success',
                    'data' => $player_list,
                    'message' => 'Retrieved player list for Event ' . get_the_title($event_id) . ' (' . $event_id . ')'
                );
            }
            
            return array(
                'status' => 'failed',
                'data' => array(),
                'message' => 'No team for Event ' . get_the_title($event_id) . ' (' . $event_id . ')'
            );
        }

        // Create correct message for ID or Name
        $message = '[Unknown Event]';
        if ( !empty($data['event_id']) ) {
            $message = 'ID "' . esc_html($data['event_id']) . '"';
        } elseif( !empty( $data['event_name'] ) ) {
            $message = 'Name "' . esc_html($data['event_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Event "' . $message .'"'
        );
    }
    
     /**
     * 
     */
    public static function get_teams( $data ) {
        // Verify Event ID
        $event_id = SGC_Public_Events::get_id( $data );

        if ( !is_null($event_id) ) {
            // Get Team ID. Exit if none exists
            $team_id = get_post_meta( $event_id, 'sgc_event_team', true);

            if ( !empty( $team_id ) ) { 
                return array(
                    'status' => 'success',
                    'data' => array(
                        array(
                            'ID' => $team_id,
                            'name' => get_the_title($team_id),
                            'URL' => get_the_permalink($team_id)
                        ),
                    ),
                    'message' => 'Retrieved team for Event ' . get_the_title($team_id) . ' (' . $team_id . ')'
                );
            }
        }

        // Create correct message for ID or Name
        $message = '[Unknown Event]';
        if ( !empty($data['event_id']) ) {
            $message = 'ID "' . esc_html($data['event_id']) . '"';
        } elseif( !empty( $data['event_name'] ) ) {
            $message = 'Name "' . esc_html($data['event_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Event "' . $message .'"'
        );
    }

    /**
     * 
     */
    public static function get_scorecards( $data ) {
        // Verify Event ID
        $event_id = SGC_Public_Events::get_id( $data );

        if ( !is_null($event_id) ) {
            // Fetch the list of scorecards
            $scorecards = get_posts(array(
                'meta_query' => array(
                    array(
                        'key' => 'sgc_scorecard_event',
                        'value' => get_the_id()
                        )
                ),
                'post_status' => 'publish',
                'post_type' => 'sgc_scorecard',
                'orderby' => 'post_title',
                'order' => 'ASC',
                'posts_per_page' => -1
            ));
            
            if ( !empty( $scorecards ) ) { 
                // Get tee information
                $location_id = get_post_meta( $event_id, 'sgc_event_location', true );
                $course_data = json_decode( get_post_meta($location_id, 'sgc_location_tees', true ), true );
                $tee_color = get_post_meta( $event_id, 'sgc_event_tee', true);
                $tee_diff = '';
                $tee_par = [];
                
                if ( is_array( $course_data ) ) {
                    foreach ( $course_data as $tee ) {
                        if ( $tee['color'] == $tee_color ) {
                            $tee_diff = $tee['difficulty'];
                            $tee_par = $tee['par'];
                        }
                    }
                }
                
                // Fill the return array with scorecard data
                $player_scores = [];
                foreach( $scorecards as $card ) {
                    $player_id = get_post_meta($card->ID, 'sgc_scorecard_player', true);

                    array_push( $player_scores, array(
                        'status' => 'success',
                        'player' => esc_html(get_the_title( $player_id )),
                        'player_url' => esc_url(get_the_permalink( $player_id )),
                        'tee_color' => esc_html($tee_color),
                        'tee_difficulty' => esc_html($tee_diff),
                        'greens' => esc_html(get_post_meta($card->ID, 'sgc_scorecard_greens', true)),
                        'fairways' => esc_html(get_post_meta($card->ID, 'sgc_scorecard_fairways', true)),
                        'putts' => esc_html(get_post_meta($card->ID, 'sgc_scorecard_putts', true)),
                        'strokes' => json_decode( filter_var(
                                get_post_meta($card->ID, 'sgc_scorecard_strokes', true), 
                                FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)),
                        'tee_par' => filter_var_array($tee_par, FILTER_SANITIZE_NUMBER_INT)
                    ));
                }

                return array(
                    'status' => 'success',
                    'data' => $player_scores,
                    'message' => 'Retrieved scorecards list for event ' . get_the_title($event_id) . ' (' . $event_id . ')'
                );
            }

            return array(
                'status' => 'Success',
                'data' => array(),
                'message' => 'Scorecard list is empty for Event ' . get_the_title($event_id) . ' (' . $event_id . ')'
            );
        }
        
        // Create correct message for ID or Name
        $message = '[Unknown Event]';
        if ( !empty($data['event_id']) ) {
            $message = 'ID "' . esc_html($data['event_id']) . '"';
        } elseif( !empty( $data['event_name'] ) ) {
            $message = 'Name "' . esc_html($data['event_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Event "' . $message .'"'
        );
    }
    
    /**
     * 
     */
    public static function toggle_checkin( $data ) {
        // Verify Event ID
        $event_id = SGC_Public_Events::get_id( $data );

        if ( !is_null($event_id) ) {
            // Verify Player ID
            $player_id = SGC_Public_Events::get_player_id( $data );

            if ( !is_null($player_id) ) {
                // Check to see if we need ID to continue
                if( get_option('sgc_require_id') == 'True' ) {
                    // Check to see if we've been given a valid email
                    $player_email = sanitize_email( get_post_meta($player_id, 'sgc_player_email', true) );
                    $player_email_hash = wp_hash( $player_email ); 
                    $valid = ( !empty($player_email) && $player_email_hash == 
                            sanitize_text_field($data['email']) );
                    
                    // Check to see if we've been given a valid phone number
                    $clean = array(' ', '-', '+', '(', ')', '.', '#', '*');
                    $player_phone = str_replace( $clean, '',
                        get_post_meta($player_id, 'sgc_player_phone', true) );
                    $player_phone_hash = wp_hash( $player_phone );
                    $valid |= ( !empty($player_phone) && $player_phone_hash == 
                            sanitize_text_field($data['phone']) );
                    
                    if( !$valid ) { return array(
                        'status' => 'failed',
                        'data' => array(),
                        'message' => 'Not authorized to update'
                        );
                    }
                }
                
                // get the list of checkedin players
                $checkin_list = get_post_meta( $event_id, 'sgc_team_player_checkedin', false);
                
                $ischeckedin = false;
                foreach( $checkin_list as $here ) {
                    if( $player_id == $here ) {
                        $ischeckedin = true;
                    }
                }
                
                if ( $ischeckedin ) {       // If the player is checkedin remove them
                    delete_post_meta( $event_id, 'sgc_team_player_checkedin', $player_id );
                } else {                    // If the player is not checkedin add them
                    add_post_meta( $event_id, 'sgc_team_player_checkedin', $player_id, false );
                }
                
                return array(
                    'status' => 'success',
                    'data' => array( 
                        'event' => esc_html($event_id),
                        'player' => esc_html($player_id),
                        'checkedin' => esc_html(!$ischeckedin)),
                    'message' => 'Updated checkedin status for Player ' . get_the_title($player_id) . ' (' . $player_id 
                        . ') at Event ' . get_the_title($event_id) . ' (' . $event_id . ')'
                );
            }

            return array(
                'status' => 'failed',
                'data' => array(),
                'message' => 'Unable to find Player with ID ' . esc_html($data['player_id'])
            );
        }
        
        // Create correct message for ID or Name
        $message = '[Unknown Event]';
        if ( !empty($data['event_id']) ) {
            $message = 'ID "' . esc_html($data['event_id']) . '"';
        } elseif( !empty( $data['event_name'] ) ) {
            $message = 'Name "' . esc_html($data['event_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Event "' . $message .'"'
        );
    }
    
    /**
     * 
     */
    public static function sc_get_info( $attr ) {
        $eventinfo = SGC_Public_Events::get_info($attr);

        if( array_key_exists('status', $eventinfo) && 'success' === $eventinfo['status'] ) {
            date_default_timezone_set( get_option('timezone_string') );

            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-event-info-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . __('Could not find Info for this Event', SGC_TEXTDOMAIN)
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_groups( $attr ) {
        $groups = SGC_Public_Events::get_groups($attr);

        if( array_key_exists('status', $groups) && 'success' === $groups['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-groups-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . __('Could not find Groups for this Event', SGC_TEXTDOMAIN)
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_players( $attr ) {
        $playerslist = SGC_Public_Events::get_players($attr);

        if( array_key_exists('status', $playerslist) && 'success' === $playerslist['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-players-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . __('Could not find Players for this Event', SGC_TEXTDOMAIN)
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_teams( $attr ) {
        $teamslist = SGC_Public_Events::get_teams($attr);

        if( array_key_exists('status', $teamslist) && 'success' === $teamslist['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-teams-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . __('Could not find Teams for this Event', SGC_TEXTDOMAIN)
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_tees( $attr ) {
        $teeslist = SGC_Public_Events::get_tees($attr);

        if( array_key_exists('status', $teeslist) && 'success' === $teeslist['status'] ) {
            $units = (get_option('sgc_default_units') === 'imperial') ? __('Yards', SGC_TEXTDOMAIN) : __('Meters', SGC_TEXTDOMAIN);

            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-tees-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . __('Could not find Tees for this Event', SGC_TEXTDOMAIN)
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_checkin( $attr ) {
        $eventid = SGC_Public_Events::get_id($attr);

        $playerslist = SGC_Public_Events::get_players($attr);

        if( array_key_exists('status', $playerslist) && 'success' === $playerslist['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-event-checkin-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . __('Could not find Tees for this Event', SGC_TEXTDOMAIN)
            . '</div>';
    }


    /**
     * 
     */
    public function add_shortcodes () {
        add_shortcode( 'sgc_event_info', array( 'SGC_Public_Events', 'sc_get_info' ) );
        add_shortcode( 'sgc_event_groups', array( 'SGC_Public_Events', 'sc_get_groups' ) );
        add_shortcode( 'sgc_event_players', array( 'SGC_Public_Events', 'sc_get_players' ) );
        add_shortcode( 'sgc_event_teams', array( 'SGC_Public_Events', 'sc_get_teams' ) );
        add_shortcode( 'sgc_event_tees', array( 'SGC_Public_Events', 'sc_get_tees' ) );
        add_shortcode( 'sgc_event_checkin', array( 'SGC_Public_Events', 'sc_get_checkin' ) );
    }

    /**
     * 
     */
    public function add_rest_events() {
        register_rest_route('simplegolfclub/v1', '/event/info/(?P<event_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Events', 'get_info'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/event/tees/(?P<event_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Events', 'get_tees'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/event/groups/(?P<event_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Events', 'get_groups'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/event/players/(?P<event_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Events', 'get_players'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/event/scorecards/(?P<event_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Events', 'get_scorecards'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/event/checkin/(?P<event_id>\d+)/(?P<player_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Events', 'toggle_checkin'),
            'permission_callback' => '__return_true'
        ));
        
        // Add metadata to post data
        register_post_meta( 'sgc_event', 'sgc_event_timestamp', array(
            'type'         => 'string',
            'description'  => 'Event Timestamp',
            'single'       => true,
            'show_in_rest' => true
         ));
    }
    
}

// #### BEGIN publicaly accessible PHP function wrappers #######################
if (! function_exists( 'sgc_event_getinfo' )) {
    function sgc_event_getinfo( $data = [] ) {
        return SGC_Public_Events::get_info( $data );
    }
}
if (! function_exists( 'sgc_event_gettees' )) {
    function sgc_event_gettees( $data = [] ) {
        return SGC_Public_Events::get_tees( $data );
    }
}
if (! function_exists( 'sgc_event_getgroups' )) {
    function sgc_event_getgroups( $data = [] ) {
        return SGC_Public_Events::get_groups( $data );
    }
}
if (! function_exists( 'sgc_event_getplayers' )) {
    function sgc_event_getplayers( $data = [] ) {
        return SGC_Public_Events::get_players( $data );
    }
}
if (! function_exists( 'sgc_event_getscorecards' )) {
    function sgc_event_getscorecards( $data = [] ) {
        return SGC_Public_Events::get_scorecards( $data );
    }
}
if (! function_exists( 'sgc_event_togglecheckin' )) {
    function sgc_event_togglecheckin( $data = [] ) {
        return SGC_Public_Events::toggle_checkin( $data );
    }
}
