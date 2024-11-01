<?php
/**
 * SGC_Admin_Teams : Custom function to create Teams for Simple Golf Club
 * @author Matthew Linton
 *
 */

class SGC_Admin_Teams
{
    /* ### PUBLIC FUNCTIONS ################################################# */
    
    /**
     * create_post_type_teams : Create the custom post
     */
    public function create_post_type_teams() {
        $labels = array(
            'name' => 'Teams',
            'singular_name' => 'Team',
            'add_new' => 'Add New Team',
            'add_new_item' => 'Add New Team',
            'edit_item' => 'Edit Team',
            'new_item' => 'New Team',
            'all_items' => 'All Teams',
            'view_item' => 'View Team',
            'search_items' => 'Search Teams',
            'not_found' =>  'No Teams Found',
            'not_found_in_trash' => 'No Teams found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Teams'
        );
        
        register_post_type( 'sgc_team', array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
            'rest_base' => 'sgc_teams',
            'supports' => array( 'title', 'thumbnail', 'editor', 'custom-fields' ),
            'taxonomies' => array('post_tag', 'category'),
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'rewrite' => array( 'slug' => 'sgc_teams' ),
            'menu_icon' => 'dashicons-groups'
        ));
    }
    
    /**
     * add_teams_fields : Add custom fields to the post page
     */
    public function add_teams_fields($posts) {
        add_meta_box(
            'sgc_custom_team_players',
            __('Players', SGC_TEXTDOMAIN),
            array('SGC_Admin_Teams', 'teams_fields_html'),
            'sgc_team',
            'side',
            'low'
            );
    }
    
    /**
     * teams_fields_html : load the content for custom post fields
     */
    public static function teams_fields_html($posts) {
        global $wpdb, $post;
        
        // Build the team player array
        $team['playerlist'] = [];
        foreach( get_post_meta( get_the_id(), 'sgc_team_player', false) as $player_id ) {
            array_push( $team['playerlist'], array(
                'ID' => $player_id,
                'name' => esc_html(get_the_title( $player_id )),
                'URL' => esc_url(get_the_permalink( $player_id )),
                'delete' => false
            ));
        }
        
        include_once( plugin_dir_path( dirname( __FILE__ ) ) 
                . 'admin/partials/sgc-admin-teams.php' );
    }
    
    /**
     * save_teams_meta : Save data when "Post" or "Update" is run on the custom post
     */
    public function save_teams_meta($post_id)
    {
        if (array_key_exists('sgc_team_players', $_POST)) {
            
            update_post_meta(
                $post_id,
                'sgc_team_players',
                sanitize_text_field($_POST['sgc_team_players']));
            
             // Add individual players as metadata so we can search for it later
            delete_post_meta( $post_id, 'sgc_team_player' );
            
            foreach( json_decode( get_post_meta( $post_id, 'sgc_team_players', true), true ) as $player ) {
                if( !$player['delete'] ) {
                    add_post_meta(
                        $post_id,
                        'sgc_team_player',   
                        sanitize_text_field($player['ID']),
                        false);
                }
            } 
        }
    }
    
} // END SCG_Admin_Teams
