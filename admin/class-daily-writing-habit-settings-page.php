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
			'target_number_words',
			__( 'target_number_words_label', 'text_domain' ),
			array( $this, 'render_target_number_words_field' ),
			'habit_target_options',
			'habit_target_options_section'
		);
		add_settings_field(
			'exclude_counting_pages',
			__( 'exclude_counting_pages_label', 'text_domain' ),
			array( $this, 'render_exclude_counting_pages_field' ),
			'habit_target_options',
			'habit_target_options_section'
		);
		add_settings_field(
			'number_days_show_habit',
			__( 'number_days_show_habit_label', 'text_domain' ),
			array( $this, 'render_number_days_show_habit_field' ),
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

	function render_target_number_words_field() {

		// Retrieve data from the database.
		$options = get_option( 'habit_target_options' );

		// Set default value.
		$value = isset( $options['target_number_words'] ) ? $options['target_number_words'] : '750';

		// Field output.
		echo '<input type="number" name="habit_target_options[target_number_words]" class="regular-text target_number_words_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Number of words you want to set as goal', 'text_domain' ) . '</p>';

	}

	function render_exclude_counting_pages_field() {

		// Retrieve data from the database.
		$options = get_option( 'habit_target_options' );

		// Set default value.
		$value = isset( $options['exclude_counting_pages'] ) ? $options['exclude_counting_pages'] : 'True';

		// Field output.
		echo '<input type="checkbox" name="habit_target_options[exclude_counting_pages]" class="exclude_counting_pages_field" value="1" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'text_domain' );
		echo '<span class="description">' . __( 'Should writing of pages be part of your daily word count?', 'text_domain' ) . '</span>';

	}

	function render_number_days_show_habit_field() {

		// Retrieve data from the database.
		$options = get_option( 'habit_target_options' );

		// Set default value.
		$value = isset( $options['number_days_show_habit'] ) ? $options['number_days_show_habit'] : '30';

		// Field output.
		echo '<input type="number" name="habit_target_options[number_days_show_habit]" class="regular-text number_days_show_habit_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Number of days to visualize when displaying your writing history', 'text_domain' ) . '</p>';

	}


}