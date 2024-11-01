<?php
/**
 * 
 * @author Matthew Linton
 *
 */
class SGC_Admin_Locations
{
    
    /**
     *
     */
    public function create_post_type_locations() {
        $labels = array(
            'name' => 'Locations',
            'singular_name' => 'Location',
            'add_new' => 'Add New Location',
            'add_new_item' => 'Add New Location',
            'edit_item' => 'Edit Location',
            'new_item' => 'New Location',
            'all_items' => 'All Locations',
            'view_item' => 'View Locations',
            'search_items' => 'Search Locations',
            'not_found' =>  'No Locations Found',
            'not_found_in_trash' => 'No Locations found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Locations'
        );
        
        register_post_type( 'sgc_location', array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
            'rest_base' => 'sgc_locations',
            'supports' => array( 'title', 'thumbnail', 'editor', 'custom-fields'),
            'taxonomies' => array('post_tag', 'category'),
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'rewrite' => array( 'slug' => 'sgc_locations' ),
            'menu_icon' => 'dashicons-location-alt'
        ));
    }
    
    /**
     *
     */
    public function add_locations_fields() {
        add_meta_box(
            'sgc_custom_location_fields',           // Unique ID
            __('Course Details', SGC_TEXTDOMAIN),  // Box title
            array('SGC_Admin_Locations', 'locations_fields_html'),
            'sgc_location',
            'side'
            );
    }
    
    /**
     *
     */
    static function locations_fields_html() {
        global $post;
        
        switch( get_option('sgc_default_units' ) ) {
            case 'metric' :
                $default_units = __('m', SGC_TEXTDOMAIN);
                break;
            default :
                $default_units = __('yd', SGC_TEXTDOMAIN);
                break;
        }
        
        //Fetch all other needed info
        $location['tees'] = get_post_meta($post->ID, 'sgc_location_tees', true);
        
        include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sgc-admin-locations.php' );
    }
    
    /**
     *
     */
    function save_locations_meta($post_id)
    {
        if (array_key_exists('sgc_location_tees', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_location_tees',
                sanitize_text_field($_POST['sgc_location_tees']));
        }
    }
    
}
