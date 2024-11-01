<?php

/**
 * 
 * @author Matthew Linton
 *
 */
class SGC_Admin_ScoreCards
{
    /**
     * 
     */
    public function create_post_type_scorecards() {
        $labels = array(
            'name' => 'Score Cards',
            'singular_name' => 'Score Card',
            'add_new' => 'Add New Score Card',
            'add_new_item' => 'Add New Score Card',
            'edit_item' => 'Edit Score Card',
            'new_item' => 'New Score Card',
            'all_items' => 'All Score Card',
            'view_item' => 'View Score Card',
            'search_items' => 'Search Score Card',
            'not_found' =>  'No Score Card Found',
            'not_found_in_trash' => 'No Score Card found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Score Cards'
        );
        
        register_post_type( 'sgc_scorecard', array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'supports' => array( 'title', 'editor', 'custom-fields' ),
            'taxonomies' => array('post_tag', 'category'),
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'rewrite' => array( 'slug' => 'sgc_scorecards' ),
            'menu_icon' => 'dashicons-list-view'
        ));
    }
    
    /**
     *
     */
    public function disable_auto_save_posts() {
        switch(get_post_type()) {
            case 'scg_scorecard':
                wp_dequeue_script('autosave');
                break;
        }
    }
    
    /**
     * 
     */
    public function modify_post_data( $data, $post ) {
        if ( !in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) ) {
            // Alter the permalink to match the title
            $data['post_name'] = sanitize_title( $data['post_title'] );
        }
        return $data;
    }
    
    /**
     *
     */
    public function add_scorecards_fields() {
        global $post;
        
        $post_title = __('Scorecard', SGC_TEXTDOMAIN);
        if( $post->post_title != '' ) { $post_title = $post->post_title; }
        
        add_meta_box(
            'sgc_custom_scorecard_fields',           // Unique ID
            esc_html($post_title),  // Box title
            array('SGC_Admin_ScoreCards', 'scorecards_fields_html'),
            'sgc_scorecard',
            'normal',
            'high'
            );
    }
    
    /**
     *
     */
    public static function scorecards_fields_html() {
        global $wpdb, $post;
        
         // Fetch the scorecard info
        $sc = array(
            'player' => array(
                'ID' => get_post_meta(get_the_id(), 'sgc_scorecard_player', true),
                'name' => get_the_title(get_post_meta(get_the_id(), 'sgc_scorecard_player', true))),
            'strokes' => json_decode(get_post_meta(get_the_id(), 'sgc_scorecard_strokes', true)),
            'greens' => get_post_meta(get_the_id(), 'sgc_scorecard_greens', true),
            'fairways' => get_post_meta(get_the_id(), 'sgc_scorecard_fairways', true),
            'putts'=> get_post_meta(get_the_id(), 'sgc_scorecard_putts', true),
            'tee' => get_post_meta(get_the_id(), 'sgc_scorecard_tee', true)
        );
        
        // Fetch scoorecard event info
        $sc['event'] = [];
        $event_id = get_post_meta( get_the_id(), 'sgc_scorecard_event', true);
        
        // Fetch the scorecard event location info
        $location_id = get_post_meta( $event_id, 'sgc_event_location', true );
        $tees_list = json_decode( get_post_meta($location_id, 'sgc_location_tees', true), true );
           
        // Fetch the scorecard event location tees info
        $tees = [];
        if( $tees_list !== null ) {
            foreach( $tees_list as $tee ) {
                array_push( $tees, array(
                    'color' => $tee['color'],
                    'difficulty' => $tee['difficulty'],
                    'isdefault' => $tee['isdefault']
                ));
            }
        }
        
        // Build the scorecard event location array
        $location = array(
            'ID' => $location_id,
            'name' => get_the_title($location_id),
            'URL' => get_the_permalink($location_id),
            'tees' => $tees
        );
                
        // Fetch scorecard event teams info
        $team_ids = get_post_meta( $event_id, 'sgc_event_team', false );
        
        // Build the team list for the event array
        $team_list = [];
        if( $team_ids ) {
            foreach( $team_ids as $team_id ) {
                //fetch the players list
                $player_ids = get_post_meta( $team_id, 'sgc_team_player', false);

                // Build the players list for the team array
                $players = [];
            if( is_array($player_ids ) ) {
                    foreach( $player_ids as $player_id ) {
                        array_push( $players, array(
                            'ID' => $player_id,
                            'name' => get_the_title($player_id),
                            'URL' => get_the_permalink($player_id)
                        ));
                    }
                }

                array_push( $team_list , array(
                    'ID' => $team_id,
                    'name' => get_the_title($team_id),
                    'URL' => get_the_permalink($team_id),
                    'players' => $players
                ));
            }
        }
        
        // Build the scorecard event array
        $sc['event'] = array(
            'ID' => $event_id,
            'name' => get_the_title( $event_id ),
            'date' => get_post_meta( $event_id, 'sgc_event_timestamp', true ),
            'URL' => get_the_permalink( $event_id ),
            'location' => $location,
            'teams' => $team_list
        );
        
        include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sgc-admin-scorecards.php' );
    }
    
    /**
     *
     */
    public function save_scorecards_meta($post_id)
    {
        if (array_key_exists('sgc_scorecard_player', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_player',
                sanitize_key($_POST['sgc_scorecard_player']));
        }
        if (array_key_exists('sgc_scorecard_events', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_events',
                sanitize_text_field($_POST['sgc_scorecard_events']));
            
            // Add individual locations as metadata
            delete_post_meta( $post_id, 'sgc_scorecard_event' );
            
            foreach( json_decode( get_post_meta( $post_id, 'sgc_scorecard_events', true), true ) as $event ) {
                if( !$event['delete'] ) {
                    add_post_meta(
                        $post_id,
                        'sgc_scorecard_event',   
                        sanitize_text_field($event['ID']),
                        false);
                }
            } 
        }
        if (array_key_exists('sgc_scorecard_tee', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_tee',
                sanitize_text_field($_POST['sgc_scorecard_tee']));
        }
        if (array_key_exists('sgc_scorecard_strokes', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_strokes',
                sanitize_text_field($_POST['sgc_scorecard_strokes']));
        }
        if (array_key_exists('sgc_scorecard_greens', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_greens',
                sanitize_text_field($_POST['sgc_scorecard_greens']));
        }
        if (array_key_exists('sgc_scorecard_fairways', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_fairways',
                sanitize_text_field($_POST['sgc_scorecard_fairways'])
                );
        }
        if (array_key_exists('sgc_scorecard_putts', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_putts',
                sanitize_text_field($_POST['sgc_scorecard_putts']));
        }
        if (array_key_exists('sgc_scorecard_timestamp', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_scorecard_timestamp',
                sanitize_key($_POST['sgc_scorecard_timestamp']));
        }
    }
}

