<?php
namespace PawsPlus\Doorknob;

defined( 'ABSPATH' ) or die( "It's a trap!" );

class Admin_Settings
{

	public function __construct( $page, $section )
	{
		$this->page    = $page;
		$this->section = $section;
		$this->options = get_option( 'doorknob_options' );
		$this->fields  = $this->admin_fields();

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_init()
	{
		add_settings_section( $this->section, 'Doorknob Settings', array( $this, 'general_options_callback' ), $this->page );
		register_setting( $this->page, 'doorknob_options', array( $this, 'sanitize' ) );

		foreach( $this->fields as $field ) {
			add_settings_field( $field['name'], $field['placeholder'], array( $this, $field['type'] ), $this->page, $this->section, $field );
		}
	}

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

	/*
	* -------------------------
	* Section Callbacks
	* -------------------------
	*/
	public function general_options_callback()
	{
		echo '<p>These settings will take an immediate effect on the behavior of the oauth2 client.</p>';
	}

	public function text_field( $args, $type = 'text' )
	{
		printf(	'<input type="%s" size=40 id="%s" name="doorknob_options[%s]" value="%s" placeholder="%s" />', $type, $args['name'], $args['name'], $args['value'], $args['placeholder'] );
	}

	public function password_field( $args )
	{
		$this->text_field( $args, 'password' );
	}

	public function select_menu( $args )
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

		return $new_input;
	}

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
			)
		);
	}

}
