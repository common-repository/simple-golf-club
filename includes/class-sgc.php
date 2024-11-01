<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://gitlab.com/mlinton/
 * @since      1.0.0
 *
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/includes
 * @author     Matthew Linton <matthew.linton@gmail.com>
 */
class Simplegolfclub {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Simplegolfclub_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Custom post type classes
     */
    protected $settings;
    
    protected $admin_events;
    protected $admin_scorecards;
    protected $admin_players;
    protected $admin_teams;
    protected $admin_locations;
    
    protected $public_events;
    protected $public_scorecards;
    protected $public_players;
    protected $public_teams;
    protected $public_locations;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('SGC_VERSION')) {
            $this->version = SGC_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'simplegolfclub';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Simplegolfclub_Loader. Orchestrates the hooks of the plugin.
     * - Simplegolfclub_i18n. Defines internationalization functionality.
     * - Simplegolfclub_Admin. Defines all hooks for the admin area.
     * - Simplegolfclub_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sgc-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sgc-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sgc-admin.php';
        
        /**
         * Custom post type Admin includes
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sgc-admin-events.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sgc-admin-scorecards.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sgc-admin-players.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sgc-admin-teams.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sgc-admin-locations.php';
        
        $this->admin_events = new SGC_Admin_Events();
        $this->admin_scorecards = new SGC_Admin_ScoreCards();
        $this->admin_players = new SGC_Admin_Players();
        $this->admin_teams = new SGC_Admin_Teams();
        $this->admin_locations = new SGC_Admin_Locations();
        
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sgc-public.php';

        /**
         * Custom post type Public includes
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sgc-public-events.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sgc-public-scorecards.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sgc-public-players.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sgc-public-teams.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sgc-public-locations.php';
        
        $this->public_events = new SGC_Public_Events();
        $this->public_scorecards = new SGC_Public_Scorecards();
        $this->public_players = new SGC_Public_Players();
        $this->public_teams = new SGC_Public_Teams();
        $this->public_locations = new SGC_Public_Locations();
        
        
        $this->loader = new Simplegolfclub_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Simplegolfclub_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Simplegolfclub_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Simplegolfclub_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('pre_get_posts', $plugin_admin, 'add_posts_to_home_query');

        // Add Admin menu item
        $this->loader->add_action('admin_menu', $plugin_admin, 'plugin_admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'plugin_admin_settings');

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

        // create custom score card post type
        $this->loader->add_action('init', $this->admin_scorecards, 'create_post_type_scorecards');
        $this->loader->add_action('add_meta_boxes', $this->admin_scorecards, 'add_scorecards_fields');
        $this->loader->add_action('wp_insert_post_data', $this->admin_scorecards, 'modify_post_data', 99, 2);
        $this->loader->add_action('save_post', $this->admin_scorecards, 'save_scorecards_meta');
        $this->loader->add_action('admin_enqueue_scripts', $this->admin_scorecards, 'disable_auto_save_posts');
        
        // create custom Events post type
        $this->loader->add_action('init', $this->admin_events, 'create_post_type_events');
        $this->loader->add_action('add_meta_boxes', $this->admin_events, 'add_event_fields');
        $this->loader->add_action('save_post', $this->admin_events, 'save_event_meta');
        $this->loader->add_action('wp_dashboard_setup', $this->admin_events, 'add_event_widgets');
        
        // create custom players post type
        $this->loader->add_action('init', $this->admin_players, 'create_post_type_players');
        $this->loader->add_action('add_meta_boxes', $this->admin_players, 'add_players_fields');
        $this->loader->add_action('save_post', $this->admin_players, 'save_players_meta');

        // Create custom teams post type
        $this->loader->add_action('init', $this->admin_teams, 'create_post_type_teams');
        $this->loader->add_action('add_meta_boxes', $this->admin_teams, 'add_teams_fields');
        $this->loader->add_action('save_post', $this->admin_teams, 'save_teams_meta');

        // create custom locations post type
        $this->loader->add_action('init', $this->admin_locations, 'create_post_type_locations');
        $this->loader->add_action('add_meta_boxes', $this->admin_locations, 'add_locations_fields');
        $this->loader->add_action('save_post', $this->admin_locations, 'save_locations_meta');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Simplegolfclub_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        
        // Custom Events posts
        $this->loader->add_action('rest_api_init', $this->public_events, 'add_rest_events');
        $this->loader->add_action('init', $this->public_events, 'add_shortcodes');
        
        // Custom Scorecards posts
        $this->loader->add_action('rest_api_init', $this->public_scorecards, 'add_rest_events');
        $this->loader->add_action('init', $this->public_scorecards, 'add_shortcodes');
        
        // Custom Players posts
        $this->loader->add_action('rest_api_init', $this->public_players, 'add_rest_events');
        $this->loader->add_action('init', $this->public_players, 'add_shortcodes');
        
        // Custom Teams posts
        $this->loader->add_action('rest_api_init', $this->public_teams, 'add_rest_events');
        $this->loader->add_action('init', $this->public_teams, 'add_shortcodes');
        
        // Custom Locations posts
        $this->loader->add_action('rest_api_init', $this->public_locations, 'add_rest_events');
        $this->loader->add_action('init', $this->public_locations, 'add_shortcodes');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Simplegolfclub_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
    
}
