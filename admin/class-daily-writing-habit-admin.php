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

		/**
		 * The class responsible for adding the new setting in the Discussion page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-daily-writing-habit-dashboard-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-daily-writing-habit-settings-page.php';
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

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

					'column' => 'post_modified_gmt',
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
			$meta_count=$post->increment;
			$count=$count+$meta_count['date_inc'==$today];
		}

		return $count;

	}




}
