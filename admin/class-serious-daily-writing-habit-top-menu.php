<?php
/**
 * Simple class in charge of adding the top menu of the plugin
 *
 * @since      1.0.0
 * @package    Daily_Writing_Admin\admin
 * @subpackage Daily_Writing_Admin\admin\partials
 */

class Serious_Daily_Writing_Habit_Top_Menu{


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


	public function add_top_menu()
	{
		//adding the top menu
		add_menu_page(
			'DWH',
			'Writing Habit',
			'manage_options',
			'dwh',
			'',
			'',
			null
		);

		//adding the options page
		add_submenu_page(
			'dwh',
			'Writing goals',
			'Writing goals configuration',
			'manage_options',
			'dwh-options',
			'settings_page_layout'
		);

		//adding the results page
		add_submenu_page(
			'dwh',
			'Goal reports',
			'How you have been doing so far',
			'manage_options',
			'dwh-results',
			'results_page_layout'
		);

	}





}