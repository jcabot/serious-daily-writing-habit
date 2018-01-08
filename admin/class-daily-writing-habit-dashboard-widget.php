<?php

/**
 * Provide a widget admin dashboard area view for the plugin
 *
 * @since      1.0.0
 * @package    Daily_Writing_Admin\admin
 * @subpackage Daily_Writing_Admin\admin\partials
 */
class Daily_Writing_Habit_Dashboard_Widget {


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

		wp_add_dashboard_widget('dhdw','Writing Daily Habit Stats', 'render_dashboard_widget' );

	}

	public function render_dashboard_widget() {

		$html = 'test test for the dashboard widget';

		echo $html;

	}



}

?>