<?php

/**
 * Class SGC_Public_Players
 * All methods for public facing player posts
 * 
 * @author Matthew Linton
 *
 */

class SGC_Public_Players {
    /**
     * 
     */
    private static function get_id( $data = [] ) {
        $player_id = NULL;
        
        if ( !empty($data['player_id']) ) {
            $theid= sanitize_key($data['player_id']);
            if( 'sgc_player' === get_post_type($theid) && 'publish' === get_post_status($theid) ) {
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

        } elseif( 'sgc_player' === get_post_type() && 'publish' === get_post_status() ) {
            $player_id = get_the_id( );
        }

        return $player_id;
    }

    /**
     *
     */
    public static function get_info( $data = [] ) {
        // Verify Player ID
        $player_id = SGC_Public_Players::get_id( $data );

        if ( !is_null($player_id) ) {
            // Get personal info
            $phone = '';
            $email = '';
            if( get_option('sgc_display_personal') == 'True' ) {
                $phone = get_post_meta($player_id, 'sgc_player_phone', true);
                $email = get_post_meta($player_id, 'sgc_player_email', true);
            }
            
            return array(
                'status' => 'success',
                'data' => array(
                    'status' => 'success',
                    'name' => esc_html(get_the_title($player_id)),
                    'URL' => esc_url(get_the_permalink($player_id)),
                    'handicap' => esc_html(get_post_meta($player_id, 'sgc_player_handicap', true)),
                    'phone' => esc_html($phone),
                    'email' => esc_html($email)
                ),
                'message' => 'Retrieved info for Player ' . get_the_title($player_id) . ' (' . $player_id . ')'
            );
        }

        // Create correct message for ID or Name
        $message = '[Unknown Player]';
        if ( !empty($data['player_id']) ) {
            $message = 'ID: "' . esc_html($data['player_id']) . '"';
        } elseif( !empty( $data['player_name'] ) ) {
            $message = 'Name: "' . esc_html($data['player_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Player with ' . $message
        );
    }
    
    /**
     * 
     */
    public static function get_teams( $data = [] ) {
        // Verify Player ID
        $player_id = SGC_Public_Players::get_id( $data );

        if ( !is_null($player_id) ) {
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

            $teams = get_posts(array(
                'post_status' => 'publish',
                'post_type' => 'sgc_team',
                'orderby' => 'post_title',
                'order' => $order,
                'posts_per_page' => -1,

                'meta_query' => array(
                    array(
                        'key' => 'sgc_team_player',
                        'value' => $player_id
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

            // Build the player team array
            $teams_list= [];
            foreach( $teams as $team ) {
                array_push( $teams_list, array(
                    'ID' => $team->ID,
                    'name' => esc_html(get_the_title( $team->ID )),
                    'URL' => esc_url(get_the_permalink( $team->ID ))
                ));
            }

            return array(
                'status' => 'success',
                'data' => $teams_list,
                'message' => 'Retrieved Teams list for Player ' . get_the_title($player_id) . ' (' . $player_id . ')'
            );
        }
 
        // Create correct message for ID or Name
        $message = '[Unknown Player]';
        if ( !empty($data['player_id']) ) {
            $message = 'ID: "' . esc_html($data['player_id']) . '"';
        } elseif( !empty( $data['player_name'] ) ) {
            $message = 'Name: "' . esc_html($data['player_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Player with ' . $message
        );
    }

    /**
     * 
     */
    public static function get_scorecards( $data = [] ) {
        // Verify Player ID
        $player_id = SGC_Public_Players::get_id( $data );

        if ( !is_null($player_id) ) {
            // Set up defaults
            $order = 'DESC';      
            $paged = 1;
            $per_page = 5;
            
            // Get passed values if they exist
            if( ! empty($data['order'] ) ) { $order = sanitize_key($data['order']); }
            if( ! empty($data['paged'] ) ) { $paged = sanitize_key($data['paged']); }
            if( ! empty($data['per_page'] ) ) { $per_page = sanitize_key($data['per_page']); }
            
            //Fetch the scorecards for this player
            $scorecards = get_posts(array(
                'meta_query' => array(
                    array(
                        'key' => 'sgc_scorecard_player',
                        'value' => $player_id
                    )
                ),
                'post_status' => 'publish',
                'post_type' => 'sgc_scorecard',
                'orderby' => 'post_date',
                'order' => $order,
                'paged' => $paged,
                'posts_per_page' => $per_page
            ));
            
            // Return early if we don't have scorecards
            if ( empty( $scorecards ) ) { 
                return array(
                    'status' => 'success',
                    'data' => array(),
                    'message' => 'No Scorecards availble for ' . get_the_title($player_id) . ' (' . $player_id . ')' 
                );
            }
            
            // Fill the return array with scorecard data
            $player_scores = [];
            foreach( $scorecards as $round ) {
                $event_id = get_post_meta($round->ID, 'sgc_scorecard_event', true);
                $location_id = get_post_meta($event_id, 'sgc_event_location', true);
                $scorecard_tee = get_post_meta( $round->ID, 'sgc_scorecard_tee', true );
                
                // Fetch the tee info 
                $tee_par = [];
                $tee_color = '';
                $tee_diff = '';
                if ( ! empty( $location_id ) ) {
                    $tees = get_post_meta( $location_id, 'sgc_location_tees', true);
                    if( !empty($tees) ) { 
                        foreach( json_decode( $tees ) as $tee ) {
                            if ( $tee->color == $scorecard_tee ) {
                                $tee_par = $tee->par;
                                $tee_color = $tee->color;
                                $tee_diff = $tee->difficulty;
                                break;
                            }
                        }
                    }
                }

                array_push( $player_scores, array(
                    'event' => esc_html(get_the_title($event_id)),
                    'event_url' => esc_url(get_the_permalink($event_id)),
                    'tee_color' => esc_html($tee_color),
                    'tee_difficulty' => esc_html($tee_diff),
                    'greens' => esc_html(get_post_meta($round->ID, 'sgc_scorecard_greens', true)),
                    'fairways' => esc_html(get_post_meta($round->ID, 'sgc_scorecard_fairways', true)),
                    'putts' => esc_html(get_post_meta($round->ID, 'sgc_scorecard_putts', true)),
                    'strokes' => json_decode( filter_var(
                            get_post_meta($round->ID, 'sgc_scorecard_strokes', true),
                            FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), true),
                    'par' => filter_var_array($tee_par, FILTER_SANITIZE_NUMBER_INT)
                ));
            }
                    
            return array(
                'status' => 'success',
                'data' => $player_scores,
                'message' => 'Retrieved Scorecards list for Player ' . get_the_title($player_id) . ' (' . $player_id . ')'
            );
        }

        // Create correct message for ID or Name
        $message = '[Unknown Player]';
        if ( !empty($data['player_id']) ) {
            $message = 'ID: "' . esc_html($data['player_id']) . '"';
        } elseif( !empty( $data['player_name'] ) ) {
            $message = 'Name: "' . esc_html($data['player_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Player with ' . $message
        );
    }

    /**
     * 
     */
    public static function sc_get_info( $attr ) {
        $playerinfo = SGC_Public_Players::get_info($attr);

        if( array_key_exists('status', $playerinfo) && 'success' === $playerinfo['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-player-info-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . esc_html($playerinfo['message'])
            . '</div>';
    }

    /**
     * 
     */
    public static function sc_get_teams( $attr ) {
        $teamslist = SGC_Public_Players::get_teams($attr);

        if( array_key_exists('status', $teamslist) && 'success' === $teamslist['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-teams-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . esc_html($teamslist['message'])
            . '</div>';
    }

    /**
     * 
     */
    public function add_shortcodes () {
        add_shortcode( 'sgc_player_info', array( 'SGC_Public_Players', 'sc_get_info' ) );
        add_shortcode( 'sgc_player_teams', array( 'SGC_Public_Players', 'sc_get_teams' ) );
    }

    /**
     *
     */
    public function add_rest_events() {
        register_rest_route('simplegolfclub/v1', '/player/info/(?P<player_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Players', 'get_info'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/player/teams/(?P<player_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Players', 'get_teams'),
            'permission_callback' => '__return_true'
        ));
        register_rest_route('simplegolfclub/v1', '/player/scorecards/(?P<player_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Players', 'get_scorecards'),
            'permission_callback' => '__return_true'
        ));
        
        // Add player team ID meta field when returning player info
        register_post_meta( 'sgc_player', 'sgc_player_handicap', array(
            'type'         => 'integer',
            'description'  => 'Handicap',
            'single'       => true,
            'show_in_rest' => true
         ));
    }
}

// #### BEGIN publicaly accessible PHP function wrappers #######################
if (! function_exists( 'sgc_player_getinfo' )) {
    function sgc_player_getinfo( $data = [] ) {
        return SGC_Public_Players::get_info( $data );
    }
}
if (! function_exists( 'sgc_player_getteams' )) {
    function sgc_player_getteams( $data = [] ) {
        return SGC_Public_Players::get_teams( $data );
    }
}
if (! function_exists( 'sgc_player_getscorecards' )) {
    function sgc_player_getscorecards( $data = [] ) {
        return SGC_Public_Players::get_scorecards( $data );
    }
}
