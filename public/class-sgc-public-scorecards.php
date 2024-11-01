<?php

/**
 * Class SGC_Public_Scorecards
 * All methods for public facing location posts
 * 
 * @author Matthew Linton
 *
 */

class SGC_Public_Scorecards {    
    /**
     * 
     */
    private static function get_id( $data ) {
        $scorecard_id = NULL;
        
        if ( !empty($data['scorecard_id']) ) {
            $theid = sanitize_key($data['scorecard_id']);
            if( 'sgc_scorecard' === get_post_type($theid) && 'publish' === get_post_status($theid)) {
                $scorecard_id = $theid;
            }

        } elseif( !empty( $data['scorecard_name'] ) ) {
            $query = new WP_Query( array(
                'post_type'         => 'sgc_scorecard',
                'post_status'       => 'publish',
                'name'              => $data['scorecard_name'],
                'posts_per_page'    => 1,
            ) );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $scorecard_id = get_the_ID(); // Get the ID of the found page
                    echo 'scid: ' . $scorecard_id;
                }
            }
        
            wp_reset_postdata(); // Reset post data after the loop

        } elseif( 'sgc_scorecard' === get_post_type() && 'publish' === get_post_status() ) {
            $scorecard_id = get_the_id( );
            echo 'we gessin';
        }
        
        return $scorecard_id;
    }

    /**
     *
     */
    public static function get_info( $data ) {
        // Verify Scorecard ID
        $scorecard_id = SGC_Public_Scorecards::get_id( $data );

        if ( !is_null($scorecard_id) ) {
            // Fetch the event info
            $event_id = get_post_meta( $scorecard_id, 'sgc_scorecard_event', true);
            $event_name = '';
            $event_url = '';

            if ( !empty($event_id) ) {
                $event_name = esc_html(get_the_title($event_id));
                $event_url = esc_url(get_the_permalink($event_id));
            }
            
            // Fetch the player info
            $player_id = get_post_meta($scorecard_id, 'sgc_scorecard_player', true);
            $player_name = '';
            $player_url = '';

            if ( !empty( $player_id ) ) {
                $player_name = esc_html(get_the_title( $player_id ));
                $player_url = esc_url(get_the_permalink( $player_id ));
            }
            
            //Fetch the tee info
            $location_id = get_post_meta($event_id, 'sgc_event_location', true);
            $scorecard_tee = get_post_meta( $scorecard_id, 'sgc_scorecard_tee', true );
            $tee_color = '';
            $tee_diff = '';
            $tee_par = [];
            $tee_rating = [];
            $tee_length = [];

            if ( !empty( $location_id ) ) {
                $tees = json_decode( get_post_meta( $location_id, 'sgc_location_tees', true) );
                if( is_array( $tees ) ) {
                    foreach( $tees as $tee ) {
                        if ( $tee->color == $scorecard_tee ) {
                            $tee_color = $tee->color;
                            $tee_diff = $tee->difficulty;
                            $tee_par = $tee->par;
                            $tee_rating = $tee->rating;
                            $tee_length = $tee->length;
                            break;
                        }
                    }
                }
            }
            
            return array(
                'status' => 'success',
                'data' => array(
                    'event_id' => esc_html($event_id),
                    'event_name' => esc_html($event_name),
                    'event_url' => esc_url($event_url), 
                    'player_id' => esc_html($player_id),
                    'player_name' => esc_html($player_name),
                    'player_url' => esc_url($player_url),
                    'tee_color' => esc_html($tee_color),
                    'tee_difficulty' => esc_html($tee_diff),
                    'greens' => esc_attr(get_post_meta( $scorecard_id, 'sgc_scorecard_greens', true)),
                    'fairways' => esc_attr(get_post_meta( $scorecard_id, 'sgc_scorecard_fairways', true)),
                    'putts' => esc_attr(get_post_meta( $scorecard_id, 'sgc_scorecard_putts', true)),
                    'strokes' => json_decode( filter_var(
                            get_post_meta( $scorecard_id, 'sgc_scorecard_strokes', true),
                            FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)),
                    'tee_par' => filter_var_array($tee_par, FILTER_SANITIZE_NUMBER_INT),
                    'tee_rating' => filter_var_array($tee_rating, FILTER_SANITIZE_NUMBER_INT),
                    'tee_length' => filter_var_array($tee_length, FILTER_SANITIZE_NUMBER_INT)
                ),
                'message' => 'Retrieved Score Card for Player ' . get_the_title($player_id) . ' (' . $player_id . ')'
            );
        }
        
        // Create correct message for ID or Name
        $message = '[Unknown Scorecard]';
        if ( !empty($data['scorecard_id']) ) {
            $message = 'ID "' . esc_html($data['scorecard_id']) . '"';
        } elseif( !empty( $data['scorecard_name'] ) ) {
            $message = 'Name "' . esc_html($data['scorecard_name']) . '"';
        }

        return array(
            'status' => 'failed',
            'data' => array(),
            'message' => 'Unable to find Scorecard with ' . $message
        );
    }
    
    /**
     * 
     */
    public static function sc_get_scorecard ( $attr ) {     
        $scorecard = SGC_Public_Scorecards::get_info($attr);
        
        if( array_key_exists('status', $scorecard) && 'success' === $scorecard['status'] ) {
            ob_start();
            include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcodes/sgc-scorecard-card.php' );
            return ob_get_clean();
        }

        return '<div class="sgc-sc-warning">' 
            . esc_html($scorecard['message'])
            . '</div>';
    }

    /**
     * 
     */
    public function add_shortcodes () {
        add_shortcode( 'sgc_scorecard_info', array( 'SGC_Public_Scorecards', 'sc_get_scorecard' ) );
    }

    /**
     * 
     */
    public function add_rest_events() {
        register_rest_route('simplegolfclub/v1', '/scorecard/info/(?P<scorecard_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array('SGC_Public_Scorecards', 'get_info'),
            'permission_callback' => '__return_true'
        ));
    }
    
}

// #### BEGIN publicaly accessible PHP function wrappers #######################
if (! function_exists( 'sgc_scorecard_getinfo' )) {
    function sgc_scorecard_getinfo( $data = [] ) {
        return SGC_Public_Scorecards::get_info( $data );
    }
}
