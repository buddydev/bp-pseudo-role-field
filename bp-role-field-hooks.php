<?php
/**
 * Helps conneting with BuddyPess Profile Search
 * // bridge code for BP Profile Search 4.3 or later
 */
class BD_Pseudo_Role_Field_BPS_Helper {
	
	private static $instance = null ;

	private function __construct() {
		
		$this->setup();

	}
	
	public static function get_instance() {
		
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	
	private function setup() {
		
		add_filter ( 'bps_field_validation', array( $this,  'allow_validation' ), 10, 2 );
		add_filter ('bps_field_data_for_search_form', array( $this, 'add_field_data' ) );
		add_filter ('bps_field_data_for_filters', array( $this, 'add_field_data' ) );
		
		add_filter( 'bps_field_query', array( $this, 'field_query' ), 10, 4 );
		
		add_filter( 'bp_after_has_profile_parse_args', array( $this, 'hide_role_fields' ) );
	}
	
	public function allow_validation ( $settings, $field ) {

		list ($value, $description, $range) = $settings;

		if ($field->type == 'role') {
			$range = false;
		}

		return array ( $value, $description, $range );
	}

	public function add_field_data ( $field ) {
		
		if ( $field->type == 'role' ) {

			$field->display = 'selectbox';
			$field->values = isset ($_REQUEST[$field->code])? (array)$_REQUEST[$field->code]: array ();

			$field->options = bd_pseudo_role_field_get_available_roles();

		}
		return $field;
	}
	

	public function field_query( $results, $field, $key, $value ) {

		if( $field->type == 'role' ) {

			$results = bd_pseudo_get_users_by_role( $value );
		}

		return $results;
	}

	public function hide_role_fields( $args ) {
		global $pagenow;
		$hide = false;
		
		if( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'bp-profile-edit'   && $pagenow == 'users.php' ) {
			$hide = true;
		}

		if( ! $hide && ! bp_is_user() && !  bp_is_register_page() ) {
			return $args;
		} 
		
		$excluded_fields =  $args['exclude_fields'];

		if( empty( $excluded_fields ) ) {
			$excluded_fields = array();
		}

		$role_field_ids = bd_pseudo_get_role_fields();

		$excluded_fields = array_merge( $excluded_fields, $role_field_ids );

		$args['exclude_fields'] = $excluded_fields;

		return $args;
	}
}


BD_Pseudo_Role_Field_BPS_Helper::get_instance();