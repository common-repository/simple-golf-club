<?php
/**
 * 
 * @author Matthew Linton
 *
 */
class SGC_Admin_Players
{
    
    /**
     *
     */
    public function create_post_type_players() {
        $labels = array(
            'name' => 'Players',
            'singular_name' => 'Player',
            'add_new' => 'Add New Player',
            'add_new_item' => 'Add New Player',
            'edit_item' => 'Edit Player',
            'new_item' => 'New Player',
            'all_items' => 'All Players',
            'view_item' => 'View Players',
            'search_items' => 'Search Players',
            'not_found' =>  'No Players Found',
            'not_found_in_trash' => 'No Players found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Players'
        );
        
        register_post_type( 'sgc_player', array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
            'rest_base' => 'sgc_players',
            'supports' => array( 'title', 'thumbnail', 'editor', 'custom-fields'),
            'taxonomies' => array('post_tag', 'category'),
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'rewrite' => array( 'slug' => 'sgc_players' ),
            'menu_icon' => 'dashicons-id-alt'
        ));
    }
    
    /**
     *
     */
    public function add_players_fields() {
        add_meta_box(
            'sgc_custom_player_fields',           // Unique ID
            __('Player Info', SGC_TEXTDOMAIN),  // Box title
            array('SGC_Admin_Players', 'players_fields_html'),
            'sgc_player',
            'side',
            'low'
            );
    }
    
    /**
     *
     */
    public static function players_fields_html() {
        global $wpdb, $post;
        
        // fetch team list for this player
        $player_teams = get_posts(array(
            'meta_query' => array(
                array(
                    'key' => 'sgc_team_player',
                    'value' => $post->ID
                )
            ),
            'post_status' => 'publish',
            'post_type' => 'sgc_team',
            'orderby' => 'post_title',
            'order' => 'ASC',
            'posts_per_page' => -1
        ));

        // Build the player team array
        $player['teamlist'] = [];
        foreach( $player_teams as $team ) {
            array_push( $player['teamlist'], array(
                'ID' => $team->ID,
                'name' => esc_html(get_the_title( $team->ID )),
                'URL' => esc_url(get_the_permalink( $team->ID )),
                'delete' => false
            ));
        }
        
        // Fetch other necessary info
        $player['handicap'] = get_post_meta(get_the_id(), 'sgc_player_handicap', true);
        $player['phone'] = get_post_meta(get_the_id(), 'sgc_player_phone', true);
        $player['email'] = get_post_meta(get_the_id(), 'sgc_player_email', true);
        
        include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sgc-admin-players.php' );
    }
    
    /**
     * save_players_meta : Save data when "Post" or "Update" is run on the custom post
     */
    public function save_players_meta($post_id)
    {
        if (array_key_exists('sgc_player_handicap', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_player_handicap',
                sanitize_text_field($_POST['sgc_player_handicap']));
        }
        if (array_key_exists('sgc_player_phone', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_player_phone',
                sanitize_text_field($_POST['sgc_player_phone']));
        }
        if (array_key_exists('sgc_player_email', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_player_email',
                sanitize_email($_POST['sgc_player_email']));
        }
        if (array_key_exists('sgc_player_teams', $_POST)) {
            update_post_meta(
                $post_id,
                'sgc_player_teams',
                sanitize_text_field($_POST['sgc_player_teams']),
                false);
            
            // Update sgc_team metadata
            foreach( json_decode( get_post_meta( $post_id, 'sgc_player_teams', true), true ) as $team ) {
                if( !$team['delete']) {
                    //check to see if the value already exists
                    $exists = false;
                    foreach( get_post_meta(sanitize_text_field($team['ID']), 'sgc_team_player', false) as $existing_player ) {
                        if( $existing_player == $post_id ) { 
                            $exists = true;
                            break;
                        }
                    }
                    
                    // If it does not exist add it
                    if( !$exists ) {
                        add_post_meta(
                            sanitize_text_field($team['ID']),
                            'sgc_team_player',
                            $post_id,
                            false);
                    }
                } else {
                    delete_post_meta(
                        sanitize_text_field($team['ID']),
                        'sgc_team_player',
                        $post_id);
                }
            }
        }
    }
    
}
