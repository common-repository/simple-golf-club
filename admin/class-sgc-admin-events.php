<?php

/**
 * Class SGC_Admin_Events
 * All class methods for creating the custom post type sgc_event
 * 
 * @author Matthew Linton
 *
 */
class SGC_Admin_Events {

    /**
     * 
     */
    public function create_post_type_events() {
        $labels = array(
            'name' => 'Events',
            'singular_name' => 'Event',
            'add_new' => 'Add New Event',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'new_item' => 'New Event',
            'all_items' => 'All Events',
            'view_item' => 'View Event',
            'search_items' => 'Search Events',
            'not_found' => 'No Events Found',
            'not_found_in_trash' => 'No Events found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Events'
        );

        register_post_type('sgc_event', array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
            'rest_base' => 'sgc_events',
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'custom-fields'),
            'taxonomies' => array('post_tag', 'category'),
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'rewrite' => array('slug' => 'sgc_events'),
            'menu_icon' => 'dashicons-tickets-alt'
        ));
    }

    /**
     * 
     */
    public function add_event_fields($posts) {
        add_meta_box(
                'sgc_custom_event_fields', 
                __('Event Details', SGC_TEXTDOMAIN), 
                array('SGC_Admin_Events', 'event_fields_html'), 
                'sgc_event', 
                'side'
        );
    }

    /**
     * 
     */
    public static function event_fields_html($posts) {
        global $wpdb, $post;

        //Fetch the location list
        $event['locations'] = [];
        $default_location = get_option('sgc_default_location');
        $event['locations_list'] = get_post_meta( get_the_id(), 'sgc_event_location', false);
        if ( !empty($event['locations_list']) && !empty($event['locations_list'][0]) ) {
            foreach( $event['locations_list'] as $location_id ) {
                array_push( $event['locations'], array(
                    'ID' => $location_id,
                    'name' => esc_html(get_the_title( $location_id )),
                    'URL' => esc_url(get_the_permalink( $location_id )),
                    'delete' => false
                ));
            }
        } elseif( $default_location != '' ) {
            array_push( $event['locations'], array(
                'ID' => $default_location,
                'name' => esc_html(get_the_title( $default_location )),
                'URL' => esc_url(get_the_permalink( $default_location )),
                'delete' => false
            ));
        } else {
            array_push( $event['locations'], array(
                'ID' => '',
                'name' => '',
                'URL' => '',
                'delete' => false
            ));
        }

        // Fetch the event tee
        $event['tee'] = get_post_meta($post->ID, 'sgc_event_tee', true);
        if (!$event['tee'] || $event['tee'] == '') {
            $event['tee'] = __('Any Tee', SGC_TEXTDOMAIN);
        }
        
        // Fetch list of tees for this location
        $event['tees'] = [];
        foreach( $event['locations'] as $location ) {
            if( $location['ID'] != '' ) {
                $event['tees'] = json_decode(get_post_meta($location['ID'], 'sgc_location_tees', true));
            }
        }

        // fetch the teams list
        $event['teams'] = [];
        $default_team = get_option('sgc_default_team');
        $event['teams_list'] = get_post_meta( get_the_id(), 'sgc_event_team', false);
        if ( !empty($event['teams_list']) && !empty($event['teams_list'][0]) ) {
            foreach( $event['teams_list'] as $team_id ) {
                array_push( $event['teams'], array(
                    'ID' => $team_id,
                    'name' => esc_html(get_the_title( $team_id )),
                    'URL' => esc_url(get_the_permalink( $team_id )),
                    'delete' => false
                ));
            }
        } elseif( $default_team != '' ) {
            array_push( $event['teams'], array(
                'ID' => $default_team,
                'name' => esc_html(get_the_title( $default_team )),
                'URL' => esc_url(get_the_permalink( $default_team )),
                'delete' => false
            ));
        } else {
            array_push( $event['teams'], array(
                'ID' => '',
                'name' => '',
                'URL' => '',
                'delete' => false
            ));
        }

        // fetch the timestamp
        $event['timestamp'] = strtotime( get_post_meta($post->ID, 'sgc_event_timestamp', true) );
        if( $event['timestamp'] == '' ) {
            date_default_timezone_set( get_option('timezone_string') );
            $event['timestamp'] = time();
        }
        
        // Fetch the rest of the necessary info
        date_default_timezone_set( get_option('timezone_string') );
        $event['date'] = date( 'F j Y', $event['timestamp'] );
        $event['time'] = date( 'g:i A', $event['timestamp'] );
        $event['groups'] = get_post_meta($post->ID, 'sgc_event_group_list', true);
        $event['checkedin'] = get_post_meta($post->ID, 'sgc_event_checkin', true);
        
        include_once( plugin_dir_path(__FILE__) . '../admin/partials/sgc-admin-events.php' );
    }

    /**
     * 
     */
    public function save_event_meta($post_id) {
        if (array_key_exists('sgc_event_date', $_POST) && array_key_exists('sgc_event_time', $_POST)) {
            date_default_timezone_set( get_option('timezone_string') );
            update_post_meta(
                $post_id, 'sgc_event_timestamp', 
                gmdate( 'c', 
                      strtotime($_POST['sgc_event_date'] . ' ' . $_POST['sgc_event_time'])) );
        }
        if (array_key_exists('sgc_event_locations', $_POST)) {
            update_post_meta(
                $post_id, 
                'sgc_event_locations', 
                sanitize_text_field($_POST['sgc_event_locations']));
            
            // Add individual locations as metadata
            delete_post_meta( $post_id, 'sgc_event_location' );
            
            foreach( json_decode( get_post_meta( $post_id, 'sgc_event_locations', true), true ) as $event ) {
                if( !$event['delete'] ) {
                    add_post_meta(
                        $post_id,
                        'sgc_event_location',   
                        sanitize_text_field($event['ID']),
                        false);
                }
            } 
        }
        if (array_key_exists('sgc_event_teams', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_event_teams',
                sanitize_text_field($_POST['sgc_event_teams']));
            
            // Add individual teams as metadata
            delete_post_meta( $post_id, 'sgc_event_team' );
            
            foreach( json_decode( get_post_meta( $post_id, 'sgc_event_teams', true), true ) as $event ) {
                if( !$event['delete'] ) {
                    add_post_meta(
                        $post_id,
                        'sgc_event_team',   
                        sanitize_text_field($event['ID']),
                        false);
                }
            } 
        }
        if (array_key_exists('sgc_event_group_list', $_POST)) {
            update_post_meta(
                $post_id, 
                'sgc_event_group_list', 
                sanitize_text_field($_POST['sgc_event_group_list']));
        }
        if (array_key_exists('sgc_event_tee', $_POST)) {
            update_post_meta(
                $post_id, 
                'sgc_event_tee', 
                sanitize_text_field($_POST['sgc_event_tee']));
        }
    }

    /**
     * 
     */
    public function add_event_widgets() {
        global $wp_meta_boxes;
 
        wp_add_dashboard_widget(
                'sgc_admin_widget_events', 
                __('Golfing Events', SGC_TEXTDOMAIN), 
                array('SGC_Admin_Events', 'widget_events_html')
                );
    }
 
    /**
     * 
     */
    public static function widget_events_html() {
        $default_numevents = get_option('sgc_default_numevents');
        if( empty($default_numevents) ) {
            $default_numevents = 5;
        }
        
        //Fetch the upcoming events
        $events_upcoming = get_posts(array(
            'post_type' => 'sgc_event',
            'post_status' => 'publish',
            'meta_query' => array(
                'event_timestamp' => array(
                    'key' => 'sgc_event_timestamp',
                    'value' => date('c'),
                    'compare' => '>')
            ),
            'orderby' => array('sgc_event_timestamp' => 'asc'),
            'paged' => 1,
            'posts_per_page' => $default_numevents
        ));
        
        //Fetch the past events
        $events_past = get_posts(array(
            'post_type' => 'sgc_event',
            'post_status' => 'publish',
            'meta_query' => array(
                'event_timestamp' => array(
                    'key' => 'sgc_event_timestamp',
                    'value' => date('c'),
                    'compare' => '<=')
            ),
            'orderby' => array('sgc_event_timestamp' => 'desc'),
            'paged' => 1,
            'posts_per_page' => $default_numevents
        ));
        
        include( plugin_dir_path(__FILE__) . '../admin/partials/sgc-widget-events.php' );
    }
    
}
