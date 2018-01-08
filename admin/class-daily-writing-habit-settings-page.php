<?php
/**
 * Provide a widget admin dashboard area view for the plugin
 *
 * @since      1.0.0
 * @package    Daily_Writing_Admin\admin
 * @subpackage Daily_Writing_Admin\admin\partials
 */

class Daily_Writing_Habit_Settings_Page{


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


	public function add_settings_page() {

		add_submenu_page(
			'dwh',
			esc_html__( 'Writing goals', 'text_domain' ),
			esc_html__( 'Writing goals configuration', 'text_domain' ),
			'manage_options',
			'dwh-settings',
			'settings_page_layout'
		);

	}

	public function init_settings_page() {

		register_setting(
			'settings_habit_group',
			'habit_target_options'
		);

		add_settings_section(
			'habit_target_options_section',
			'',
			false,
			'habit_target_options'
		);

		add_settings_field(
			'number_words',
			__( 'number_words_label', 'text_domain' ),
			array( $this, 'render_number_words_field' ),
			'habit_target_options',
			'habit_target_options_section'
		);
		add_settings_field(
			'daily_or_weekly',
			__( 'daily_or_weekly_label', 'text_domain' ),
			array( $this, 'render_daily_or_weekly_field' ),
			'habit_target_options',
			'habit_target_options_section'
		);

	}

	public function settings_page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'text_domain' ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">' . "\n";
		echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
		echo '	<form action="options.php" method="post">' . "\n";

		settings_fields( 'settings_habit_group' );
		do_settings_sections( 'habit_target_options' );
		submit_button();

		echo '	</form>' . "\n";
		echo '</div>' . "\n";

	}

	function render_number_words_field() {

		// Retrieve data from the database.
		$options = get_option( 'habit_target_options' );

		// Set default value.
		$value = isset( $options['number_words'] ) ? $options['number_words'] : '750';

		// Field output.
		echo '<input type="number" name="habit_target_options[number_words]" class="regular-text number_words_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Number of words you want to set as goal', 'text_domain' ) . '</p>';

	}

	function render_daily_or_weekly_field() {

		// Retrieve data from the database.
		$options = get_option( 'habit_target_options' );

		// Set default value.
		$value = isset( $options['daily_or_weekly'] ) ? $options['daily_or_weekly'] : '';

		// Field output.
		echo '<input type="radio" name="daily_or_weekly" class="daily_or_weekly_field" value="' . $value['daily'] . '" ' . checked( $value['daily'], 'daily', false ) . '> ' . __( 'daily_label', 'text_domain' ) . '<br>';
		echo '<input type="radio" name="daily_or_weekly" class="daily_or_weekly_field" value="' . $value['weekly'] . '" ' . checked( $value['weekly'], 'weekly', false ) . '> ' . __( 'weekly_label', 'text_domain' ) . '<br>';
		echo '<p class="description">' . __( 'Should this be a daily or weekly goal?', 'text_domain' ) . '</p>';

	}

}