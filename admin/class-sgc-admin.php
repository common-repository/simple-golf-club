<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/admin
 * @author     Matthew Linton <matthew.linton@gmail.com>
 */
class Simplegolfclub_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        // SGC General CSS
	    wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sgc-admin.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name);

		// settings
		wp_register_style( $this->plugin_name . '_settings', plugin_dir_url( __FILE__ ) . 'css/sgc-admin-settings.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . '_settings');
            
		// widgets
		wp_register_style( $this->plugin_name . '_widgets', plugin_dir_url( __FILE__ ) . 'css/sgc-admin-widgets.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . '_widgets');

		// Posts styling
		wp_register_style( $this->plugin_name . '_scorecard_post', plugin_dir_url( __FILE__ ) . 'css/posts/sgc-admin-scorecard.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . '_scorecard_post');
		wp_register_style( $this->plugin_name . '_events_post', plugin_dir_url( __FILE__ ) . 'css/posts/sgc-admin-events.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . '_events_post');
		wp_register_style( $this->plugin_name . '_locations_post', plugin_dir_url( __FILE__ ) . 'css/posts/sgc-admin-locations.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . '_locations_post');
		wp_register_style( $this->plugin_name . '_teams_post', plugin_dir_url( __FILE__ ) . 'css/posts/sgc-admin-teams.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . '_teams_post');
		wp_register_style( $this->plugin_name . '_players_post', plugin_dir_url( __FILE__ ) . 'css/posts/sgc-admin-players.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . '_players_post');

		// Select CSS
		wp_register_style( $this->plugin_name . 'select', plugin_dir_url( __FILE__ ) . 'css/sgc-admin-select.css', array(), $this->version, 'all' );
	    	wp_enqueue_style($this->plugin_name . 'select');
	    
		//Pick A Date Script (http://amsul.ca/pickadate.js/)
		wp_register_style( $this->plugin_name . '_pickadate_default', plugin_dir_url( __FILE__ ) . 'css/pickadate/default.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_pickadate_default');
		wp_register_style( $this->plugin_name . '_pickadate_defaultdate', plugin_dir_url( __FILE__ ) . 'css/pickadate/default.date.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_pickadate_defaultdate');
		wp_register_style( $this->plugin_name . '_pickadate_defaulttime', plugin_dir_url( __FILE__ ) . 'css/pickadate/default.time.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_pickadate_defaulttime');
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Main Admin script
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sgc-admin.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'SGCtxt', array( 
			'locale' => str_replace( '_', '-', get_locale() ),
			'timezone' => wp_timezone_string(),
			'url_site' => get_option('siteurl'),
			'url_plugin' => plugin_dir_url( __FILE__ ),
			'url_rest' => '/wp-json/simplegolfclub/v1',
			'opt_anytee' => __('Any Tee', SGC_TEXTDOMAIN),
			'opt_groupname' => __('Group Name...', SGC_TEXTDOMAIN),
			'opt_selectplayer' => __('Select a Player...', SGC_TEXTDOMAIN),
			'txt_add' => __('Add', SGC_TEXTDOMAIN),
			'txt_delete' => __('Delete', SGC_TEXTDOMAIN),
			'txt_edittee' => __('Edit Tee', SGC_TEXTDOMAIN),
			'txt_group' => __('Group', SGC_TEXTDOMAIN),
			'txt_remove' => __('Remove', SGC_TEXTDOMAIN),
			'txt_select' => __('Select', SGC_TEXTDOMAIN),
			'txt_tee' => __('Tee', SGC_TEXTDOMAIN),
			'txt_unknown' => __('Unknown', SGC_TEXTDOMAIN),
			'txt_selectevent' => __('An Event needs to be selected before a Scorecard can be saved', SGC_TEXTDOMAIN)
		));
            wp_enqueue_script($this->plugin_name,  array('jquery'), false, true );
            
        // Search javascript parent class
	    wp_register_script( $this->plugin_name . 'search', plugin_dir_url( __FILE__ ) . 'js/sgc-search/sgc-admin-search.js', array( 'jquery', $this->plugin_name ), $this->version, true );
	    	wp_enqueue_script($this->plugin_name . 'search',  array('jquery'), false, true );
        // Select javascript parent class
	    wp_register_script( $this->plugin_name . 'select', plugin_dir_url( __FILE__ ) . 'js/sgc-search/sgc-admin-select.js', array( 'jquery', $this->plugin_name ), $this->version, true );
	    	wp_enqueue_script($this->plugin_name . 'select',  array('jquery'), false, true );
        // Location cutom post type javascript classes
	    wp_register_script( $this->plugin_name . 'locations', plugin_dir_url( __FILE__ ) . 'js/sgc-admin-locations.js', array( 'jquery', $this->plugin_name ), $this->version, true );
	    	wp_enqueue_script($this->plugin_name . 'locations',  array('jquery'), false, true );
	    // Scorecard cutom post type javascript classes
	    wp_register_script( $this->plugin_name . 'scorecards', plugin_dir_url( __FILE__ ) . 'js/sgc-admin-scorecards.js', array( 'jquery', $this->plugin_name ), $this->version, true );
	    	wp_enqueue_script($this->plugin_name . 'scorecards',  array('jquery'), false, true );
	    // Events cutom post type javascript classes
	    wp_register_script( $this->plugin_name . 'events', plugin_dir_url( __FILE__ ) . 'js/sgc-admin-events.js', array( 'jquery', $this->plugin_name ), $this->version, true );
	    	wp_enqueue_script($this->plugin_name . 'events',  array('jquery'), false, true );
        // Player custom post type javascript classes
        wp_register_script( $this->plugin_name . 'players', plugin_dir_url( __FILE__ ) . 'js/sgc-admin-players.js', array( 'jquery', $this->plugin_name ), $this->version, true );
	    	wp_enqueue_script($this->plugin_name . 'players',  array('jquery'), false, true );
		// Team custom post type javascript classes
		wp_register_script( $this->plugin_name . 'teams', plugin_dir_url( __FILE__ ) . 'js/sgc-admin-teams.js', array( 'jquery', $this->plugin_name ), $this->version, true );
	    	wp_enqueue_script($this->plugin_name . 'teams',  array('jquery'), false, true );
            
	    //Pick A Date Script (http://amsul.ca/pickadate.js/)
	    wp_register_script( $this->plugin_name . 'pickadate_legacy', plugin_dir_url( __FILE__ ) . 'js/pickadate/legacy.js');
	    	wp_enqueue_script($this->plugin_name . 'pickadate_legacy',  array('jquery'), false, true );
	    wp_register_script( $this->plugin_name . 'pickadate_picker', plugin_dir_url( __FILE__ ) . 'js/pickadate/picker.js');
	    	wp_enqueue_script($this->plugin_name . 'pickadate_picker',  array('jquery'), false, true );
	    wp_register_script( $this->plugin_name . 'pickadate_pickerdate', plugin_dir_url( __FILE__ ) . 'js/pickadate/picker.date.js');
	    	wp_enqueue_script($this->plugin_name . 'pickadate_pickerdate',  array('jquery'), false, true );
	    wp_register_script( $this->plugin_name . 'pickadate_pickertime', plugin_dir_url( __FILE__ ) . 'js/pickadate/picker.time.js');
	    	wp_enqueue_script($this->plugin_name . 'pickadate_pickertime',  array('jquery'), false, true );
	}

	public function plugin_admin_menu() {
	    add_options_page(__('Simple Golf Club Settings', SGC_TEXTDOMAIN), 
                    __('Simple Golf Club', SGC_TEXTDOMAIN), 'manage_options', 
                    $this->plugin_name . '-manage_options', 
                    array($this, 'display_plugin_settings_page'));
	}
	
	public function add_action_links( $links ) {
	    $settings_link = array(
	        '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name . '-manage_options') 
                . '">' . __('Settings', SGC_TEXTDOMAIN) . '</a>',
	    );
	    return array_merge(  $settings_link, $links );
	    
	}
	
	public function plugin_admin_settings () {
            // General
            register_setting( $this->plugin_name . '_settings', 'sgc_default_units' );
            register_setting( $this->plugin_name . '_settings', 'sgc_display_personal' );
            // Events
            register_setting( $this->plugin_name . '_settings', 'sgc_default_numevents' );
            register_setting( $this->plugin_name . '_settings', 'sgc_require_id' );
            register_setting( $this->plugin_name . '_settings', 'sgc_display_post_sgc_event' );
            // Players
            register_setting( $this->plugin_name . '_settings', 'sgc_display_post_sgc_player' );
            // Teams
            register_setting( $this->plugin_name . '_settings', 'sgc_default_team' );
            register_setting( $this->plugin_name . '_settings', 'sgc_display_post_sgc_team' );
            // Locations
	    register_setting( $this->plugin_name . '_settings', 'sgc_default_location' );
            register_setting( $this->plugin_name . '_settings', 'sgc_display_post_sgc_location' );
            // Score Cards
            register_setting( $this->plugin_name . '_settings', 'sgc_display_post_sgc_scorecard' );
	}
	
	public function display_plugin_settings_page() {
	    global $wpdb, $post;
	    
	    // fetch team list
	    $teams = get_posts(array(
	        'post_type' => 'sgc_team',
	        'post_status' => 'publish',
	        'orderby'    => 'post_title',
	        'sort_order' => 'asc',
	        'posts_per_page' => -1
	    ));
	    
	    // fetch locations list
	    $locations = get_posts(array(
	        'post_type' => 'sgc_location',
	        'post_status' => 'publish',
	        'orderby'    => 'post_title',
	        'sort_order' => 'asc',
	        'posts_per_page' => -1
	    ));
	    
	    include_once( 'partials/sgc-admin-settings.php' );
	}
        
	/**
	 * 
	 */
	public function add_posts_to_home_query( $query ) {
		$post_types = array('sgc_location', 'sgc_team', 'sgc_player', 'sgc_event', 'sgc_scorecard', );
		$posts = array('post');
		
		// Build array list for enabled posts
		foreach( $post_types as $custom_post ) {
			if( get_option('sgc_display_post_' . $custom_post) == 'True' ) {
				array_push( $posts, $custom_post );
			}
		}
		
		if ( is_home() && $query->is_main_query() ) {
			$query->set( 'post_type', $posts );
		}
		return $query;
	}
}
