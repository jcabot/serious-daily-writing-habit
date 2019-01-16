<?php

/**
 * The core behaviuor for the admin part of the plugin
 *
 * @since      1.0.0
 * @package    Serious_Daily_Writing_Habit\admin
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



	public static function get_today_writing_increment() {

		$today = getdate();

		/*$args = array(
			'post_type' => 'post',
			'post_status' => 'any', // we also want the drafts
			'nopaging'=>true,
			'date_query' => array(
				'year'  => $today['year'],
				'month' => $today['mon'],
				'day'   => $today['mday'],
			)
		);*/

		$args = array(
			'post_type' => 'post',
			'post_status' => 'any', // we also want the drafts
			'nopaging'=>true,
			'date_query' => array(
				'relation' => 'OR',
				array(    // returns posts created today
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
				array(    // returns posts modified today
					'column' => 'post_modified',
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			)
		);

		$query_today_posts = new WP_Query( $args );

		//adding current writing counts per post
		$posts=$query_today_posts->get_posts();
		$count=0;
		foreach( $posts as $post ){
			$today_post_inc=0;
			$meta_incs= get_post_meta($post->ID, 'increment',false);
			if ( !empty( $meta_incs ) ) {
				foreach ( $meta_incs as $inc ) //the number of increments associated to a post will be typically rather small
				{
					if ( $inc['d'] == date( "Ymd" ) ) {
						$today_post_inc = $inc['v'];
					}
				}
				$count = $count + $today_post_inc;
			}
		}



		return $count;
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


	//Action called every time a modification of the post is stored in the database (after save, update,...)
	public function post_updated_count_callback( $post_id, $post_after, $post_before) {

		$new_word_length=str_word_count($post_after->post_content);
		$today=date("Ymd");
		if ( !isset($post_before) ) //it's a new post, the whole length of the body is the increment
		{
			add_post_meta($post_id,'increment', [ 'd' => $today, 'v' => $new_word_length ],false);
		}
		else
		{
			$old_word_length=str_word_count($post_before->post_content);
			$diff=$new_word_length-$old_word_length;
			if($diff<0) $diff=0; //we don't penalize removing words
			if ($diff!=0) { //if something has changed
				//we check if there is already metadata to be updated or we need to create a new entry for today's increment
				$meta_incs= get_post_meta($post_before->ID, 'increment',false);
				if ( !empty( $meta_incs ) ) {
					$previous_today_inc=0;
					foreach ($meta_incs as $inc) //the number of increments associated to a post will be typically rather small
					{
						if ($inc['d']==date( "Ymd" ))
						{
							$previous_today_inc = $inc['v'];
						}
					}
				}
				if ( $previous_today_inc!=0 ) //if not, this will be the first time we modify the post today
				{
					delete_post_meta( $post_id, 'increment', array( "d"  => $today,"v" => $previous_today_inc ));
					add_post_meta( $post_id, 'increment', array( "d"  => $today,"v" => $diff + $previous_today_inc));
				} else //the diff is the complete addition to the post we have done so far today
				{
					add_post_meta( $post_id, 'increment', array( "d" => $today, "v" => $diff ) );
				}
			}
		}
	}


}
