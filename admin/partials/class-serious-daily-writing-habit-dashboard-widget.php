<?php

/**
 * Provide a widget admin dashboard area view for the plugin
 *
 * @since      1.0.0
 * @package    Daily_Writing_Admin\admin
 * @subpackage Daily_Writing_Admin\admin\partials
 */
class Serious_Daily_Writing_Habit_Dashboard_Widget {

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
	}


	public function add_dashboard_widget() {

		wp_add_dashboard_widget('dhdw','Writing Daily Habit Stats', array($this,'render_dashboard_widget') );
	}

	public function render_dashboard_widget() {

		$inc=$this->get_today_writing_increment();

		$options = get_option( 'dwh_options' );
		$target= $options['target_number_words'];

		$html_vars = "target is " . $target ." inc is " . $inc;

		if (isset($target)) {

			if ( $inc == 0 ) {
				$html = 'Daily goal not yet accomplished. Time is running! Get to work and start writing';
			} elseif ( $inc >= $target ) {
				$html = 'Well done! You are good to go. Rest a bit and make sure you come back tomorrow for more writing';
			} else{
				$html = 'You are getting there! Keep pushing your writing. Still time to reach your goal. Do NOT disappoint yourself';
			}

		}else{
			$html='Go to the Daily Writing Habit Plugin settings page to set your writing target goals';
		}

		echo $html_vars + $html;
	}


	public function get_today_writing_increment() {

		$today = getdate();
		$args = array(
			'date_query' => array(
				'relation' => 'OR',
				array(    // returns posts created today
					'column' => 'post_date',
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

		//sum up current writing counts per post
		$posts=$query_today_posts->get_posts();
		$count=0;
		foreach( $posts as $post ){
			$meta_counts=$post->increment;  //getting the increment metadata rows associated to the post
		//	$inc=array_search(date( "Ymd" ), $meta_counts);
			//$count = $count + $inc;

			$count = $count + $meta_counts;
		}

		return $count;

	}


}
