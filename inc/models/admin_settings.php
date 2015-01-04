<?php

/**
* This class is responsible for generating the Administration page
*
* @category Models
*/

namespace PawsPlus\Doorknob\Models;

class AdminSettings
{

	/** @var string */
	private $page;

	/** @var string */
	private $section;

	/** @var array */
	private $options;

	/** @var array */
	private $fields;

	/**
	* Constructor method sets the instance variables and initializes the
	* wordpress actions to render the form.
	*
	* @param string $page    The name of the page to display the options
	* @param string $section The name of the section to group form fields
	*
	* @return void
	*/
	public function __construct( $page, $section )
	{
		$this->page    = $page;
		$this->section = $section;
		$this->options = get_option( 'doorknob_options' );
		$this->fields  = $this->admin_fields();

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	* Register the administrator settings and iterate over the fields
	* to render them on the page
	*
	* @return void
	*/
	public function admin_init()
	{
		add_settings_section( $this->section, 'Doorknob Settings', array( $this, 'general_options_callback' ), $this->page );
		register_setting( $this->page, 'doorknob_options', array( $this, 'sanitize' ) );

		foreach( $this->fields as $field ) {
			add_settings_field( $field['name'], $field['placeholder'], array( $this, $field['type'] ), $this->page, $this->section, $field );
		}
	}

	/**
	* Hook into Wordpress and setup the options page for the admin section
	*
	* @return void
	*/
	public function admin_menu()
	{
		add_options_page(
			'Doorknob Settings',
			'PawsPlus Doorknob',
			'manage_options',
			'doorknob-settings',
			array( $this, 'options_page' )
		);
	}

	/**
	* Callback method for displaying sub description on the page under the heading
	*
	* @return void
	*/
	public function general_options_callback()
	{
		echo '<p>These settings will take an immediate effect on the behavior of the oauth2 client.</p>';
	}

	/**
	* Helper function for echoing an HTML text field
	*
	* @param array $args options from the form fields array
	*
	* @return void
	*/
	public function text_field( $args, $type = 'text' )
	{
		printf(	'<input type="%s" size=40 id="%s" name="doorknob_options[%s]" value="%s" placeholder="%s" />', $type, $args['name'], $args['name'], $args['value'], $args['placeholder'] );
	}

	/**
	* Helper function for echoing an HTML password text field
	*
	* @param array $args options from the form fields array
	*
	* @return void
	*/
	public function password_field( array $args )
	{
		$this->text_field( $args, 'password' );
	}

	/**
	* Helper function for echoing an HTML select menu
	*
	* @param array $args select menu options from the form fields array
	*
	* @return void
	*/
	public function select_menu( array $args )
	{
		$name    = $args['name'];
		$value   = $args['value'];
		$options = $args['options'];

		$html = "<select name='doorknob_options[$name]' id='$name'>";

		foreach ( $options as $option ) {
			$html .= "<option value='$option'";
			$html .= strtolower( $option ) == $value ? ' selected="selected"' : '';
			$html .= ">$option</option>";
		}

		$html .= '</select>';

		echo $html;
	}

	/**
	* Renders the HTML form on the admin page
	*
	* @return void
	*/
	public function options_page()
	{
		if ( current_user_can( 'manage_options' ) )	{
			?>
				<div class="wrap">
					<form method="post" action="options.php">
						<?php
							settings_fields( $this->page );
							do_settings_sections( $this->page );
							submit_button();
						?>
					</form>
				</div>
			<?php
		}
	}

	/**
	* Sanitize the submitted fields to make sure there is nothing malicous
	*
	* @param array $input the array of form values submitted by the user
	*
	* @return array sanitied values from the input
	*/
	public function sanitize( $input )
	{
		$new_input = array();

		foreach ( $input as $name => $value ) {
			if ( isset( $input[$name] ) ) {
				$new_input[$name] = sanitize_text_field( $input[$name] );
			}
		}

		// Hard coded to the Environment select menu
		if ( isset( $new_input['environment'] ) ) {
			$valid_options = array( 'Development', 'Edge', 'Staging', 'Production');

			if ( !in_array( $new_input['environment'], $valid_options ) ) {
				$new_input['environment'] = null;
			} else {
				$new_input['environment'] = strtolower( $new_input['environment'] );
			}
		}

		if ( isset( $new_input['timeout'] ) && is_int( (int) $new_input['timeout'] ) ) {
			$new_input['timeout'] = (int) $new_input['timeout'];
		} else {
			$new_input['timeout'] = null;
		}

		return $new_input;
	}

	/**
	* Hard coded values which represent each form field
	*
	* @return array form fields to be rendered on the admin page
	*/
	private function admin_fields()
	{
		return array(
			array(
				'name'        => 'environment',
				'type'        => 'select_menu',
				'value'       => esc_attr( $this->options['environment'] ),
				'placeholder' => 'Environment',
				'options'     => array( 'Development', 'Edge', 'Staging', 'Production')
			),
			array(
				'name'        => 'development_token_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['development_token_url'] ),
				'placeholder' => 'Development Token URL'
			),
			array(
				'name'        => 'development_me_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['development_me_url'] ),
				'placeholder' => 'Development Me URL'
			),
			array(
				'name'        => 'edge_token_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['edge_token_url'] ),
				'placeholder' => 'Edge Token URL'
			),
			array(
				'name'        => 'edge_me_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['edge_me_url'] ),
				'placeholder' => 'Edge Me URL'
			),
			array(
				'name'        => 'staging_token_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['staging_token_url'] ),
				'placeholder' => 'Staging Token URL'
			),
			array(
				'name'        => 'staging_me_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['staging_me_url'] ),
				'placeholder' => 'Staging Me URL'
			),
			array(
				'name'        => 'production_token_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['production_token_url'] ),
				'placeholder' => 'Production Token URL'
			),
			array(
				'name'        => 'production_me_url',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['production_me_url'] ),
				'placeholder' => 'Production Me URL'
			),
			array(
				'name'        => 'timeout',
				'type'        => 'text_field',
				'value'       => esc_attr( $this->options['timeout'] ),
				'placeholder' => 'Timeout in Seconds'
			)
		);
	}

}
