<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://gitlab.com/mlinton/
 * @since      1.0.0
 *
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/public
 * @author     Matthew Linton <matthew.linton@gmail.com>
 */
class Simplegolfclub_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Main Public Styling
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sgc-public.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name);
		// Shortcode styling
		wp_register_style( $this->plugin_name . '_event_checkin_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-event-checkin-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_event_checkin_card');
		wp_register_style( $this->plugin_name . '_player_info_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-player-info-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_player_info_card');
		wp_register_style( $this->plugin_name . '_teams_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-teams-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_teams_card');
		wp_register_style( $this->plugin_name . '_players_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-players-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_players_card');
		wp_register_style( $this->plugin_name . '_groups_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-groups-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_groups_card');
		wp_register_style( $this->plugin_name . '_event_info_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-event-info-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_event_info_card');
		wp_register_style( $this->plugin_name . '_events_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-events-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_events_card');
		wp_register_style( $this->plugin_name . '_tees_card', plugin_dir_url( __FILE__ ) . 'css/shortcodes/sgc-tees-card.css', array(), $this->version, 'all' );
			wp_enqueue_style($this->plugin_name . '_tees_card');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Main Public Script
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sgc-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'SGCtxt', array( 
			'siteurl' => get_option('siteurl'),
			'pluginurl' => plugin_dir_url( __FILE__ )
		));
			wp_enqueue_script( $this->plugin_name,  array('jquery'), false, true);

		// Event Checkin Script
		wp_register_script( $this->plugin_name . '_event-checkin-sc', plugin_dir_url( __FILE__ ) . 'js/sgc-checkin.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name . '_event-checkin-sc',  array('jquery'), false, true);     
	}
}
