<?php

/**
 * The core behaviuor for the admin part of the plugin
 *
 * @since      1.0.0
 * @package    Daily_Writing_Habit\admin
 */


class Serious_Daily_Writing_Habit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
		$this->load_dependencies();
	}


	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/partials/class-serious-daily-writing-habit-dashboard-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/partials/class-serious-daily-writing-habit-settings-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/partials/serious-daily-writing-habit-admin-display.php';
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/serious-daily-writing-habit-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chartjs/Chart.js', array( 'jquery' ), $this->version, false );
	}

	public function init_admin_menu() {
		//adding the top menu
		add_menu_page(
			'dwh',
			'Writing Habit',
			'manage_options',
			'dwh',
			'results_page_layout',  //top menu option links also to the results page directly
			'dashicons-edit',
			null
		);

		//adding the results page
		add_submenu_page(
			'dwh',
			'Daily Writing Habit reports',
			'Accomplishments',
			'manage_options',
			'dwh',
			'results_page_layout'
		);

		//adding the options page
		add_submenu_page(
			'dwh',
			'Daily Writing goals - Configuration',
			'Writing goals',
			'manage_options',
			'dwh-options',
			array(new Serious_Daily_Writing_Habit_Settings_Page, 'settings_page_layout')
		);


	}


	public function get_latests_writing_increment($ndays) {
		$today=date("Ymd");
		$afterdate=$today-$ndays-1;

		global $wpdb;
		$results = $wpdb->get_results( "SELECT p.post_modified, pm.meta_value FROM {$wpdb->prefix}postmeta pm,{$wpdb->prefix}posts p  WHERE pm.meta_key ='increment' AND 
					p.ID=pm.post_id and p.post_modified  AFTER {$afterdate}", OBJECT );

		$word_counts = array();
		foreach( $results as $result ){
			$day=$result->meta_value['date_inc'];
			$day_position=date_diff($today, $day);
			if( $day_position>=0)  //we avoid counting word increments before the range we are considering
				$word_counts[$day_position]=$word_counts[$day_position]+$result->meta_value['value_inc'];
		}

		return $word_counts;

	}

	public function post_updated_count_callback( $post_id, $post_after, $post_before) {

		$new_length=strlen($post_after->post_content);
		$today=date("Ymd");
		if ( !isset($post_before) ) //it's a new post, the whole length of the body is the increment
		{
			add_post_meta($post_id,'increment',array(date => $today, inc => $new_length),false);
		}
		else
		{
			$old_length=strlen($post_before->post_content);
			$diff=$new_length-$old_length; if ( $diff<0 ) $diff=0; //we don't penalize edits that result in a smaller post. They don't help towards your daily work but won't punish you either

			//we check if there is already metadata to be updated or we need to create a new entry for today's increment
			$meta_counts=$post_before->increment;  //meta_counts is a bidimensional array of pairs <date,inc>
			$entry_exists=array_search(date( "Ymd" ), array_column($meta_counts, 'date'));
			if ($entry_exists)  //we have found an entry for the same post for today
			{
				delete_post_meta($post_id,'increment',$meta_counts[$entry_exists]); // we delete it to replace it with the new one
			}
			add_post_meta($post_id,'increment',array(date => $today, inc => $diff));
		}
	}


}
