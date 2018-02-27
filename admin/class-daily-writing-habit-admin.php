<?php

/**
 * The core behaviuor for the admin part of the plugin
 *
 * @since      1.0.0
 * @package    Daily_Writing_Habit\admin
 */


class Daily_Writing_Habit_Admin {

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
		$this->load_dependencies();

	}


	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-daily-writing-habit-dashboard-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-daily-writing-habit-settings-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/partials/daily-writing-habit-admin-display.php';
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/daily-writing-habit-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	//	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chartjs/Chart.js', array( 'jquery' ), $this->version, false );
	}

	public function get_today_writing_increment() {

		$today = getdate();
		$args = array(
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
			),
		);
		$query_today_posts = new WP_Query( $args );

		//adding current writing counts per post
		$posts=$query_today_posts->get_posts();
		$count=0;
		foreach( $posts as $post ){
			$meta_counts=$post->increment;  //getting the increment metadata rows associated to the post
			$inc=array_search(date( "Ymd" ), $meta_counts);
			$count = $count + $inc;
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

	public function post_updated_count_callback( $post_id, $post_after, $post_before) {

		$new_length=strlen($post_after->post_content);
		$today=date("Ymd");
		if ( !isset($post_before) ) //it's a new post, the whole lenght of the body is the increment
		{
			add_post_meta($post_id,'increment',array($today => $new_length),false);
		}
		else
		{
			$old_length=strlen($post_before->post_content);
			$diff=$new_length-$old_length; if ( $diff<0 ) $diff=0; //we don't penalize edits that result in a smaller post. They don't help towards your daily work but won't punish you either

			//we check if there is already metadata to be updated or we need to create a new entry for today's increment
			$meta_counts=$post_before->increment;
			$inc=array_search(date( "Ymd" ), $meta_counts);
			if (isset($inc))
			{
				delete_post_meta($post_id,'íncrement',array($today => $inc));
			}
			add_post_meta($post_id,'increment',array($today => $diff));
		}
	}


}
