<?php
/**
 * Implementing member type as select field
 * 
 */
class BD_Pseudo_Role_Field_Type extends BP_XProfile_Field_Type_Selectbox {
	

	public function __construct() {
		
		parent::__construct();

		$this->category = _x( 'Single Fields', 'xprofile field type category', 'bp-pseudo-role-field' );
		$this->name     = _x( 'Psudo Role', 'xprofile field type', 'bp-pseudo-role-field' );

		$this->set_format( '', 'replace' );
		
		$this->supports_multiple_defaults = false;
		$this->accepts_null_value         = true;
		$this->supports_options           = false;

		do_action( 'bd_pseudo_role_field', $this );
	}

	/**
	 * Is it a valid member type?
	 * 
	 * @param type $val
	 * @return boolean
	 */
	public function is_valid( $val ) {
		
		//if a registered member type,
		$role = get_role( $val );
		
		
		if( is_multisite() && empty( $val) ||  $role ) {
			return true;
		}
		
		return false;
		
	}
	
	public function edit_field_html( array $raw_properties = array() ) {
		
		
	}

	/**
	 * Output the edit field options HTML for this field type.
	 *
	 * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
	 * These are stored separately in the database, and their templating is handled separately.
	 *
	 * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
	 * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
	 *
	 * Must be used inside the {@link bp_profile_fields()} template loop.
	 *
	 * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
	 * 
	 */
	public function edit_field_options_html( array $args = array() ) {
		
		$original_option_values = maybe_unserialize( BP_XProfile_ProfileData::get_value_byid( $this->field_obj->id, $args['user_id'] ) );

		if( ! empty( $_POST['field_' . $this->field_obj->id] ) ) {
			
			$option_values =  (array) $_POST['field_' . $this->field_obj->id] ;
			$option_values = array_map( 'sanitize_text_field', $option_values );
			
		}else {
			
			$option_values = (array)$original_option_values;
			
		}
		 //member types list as array
                
		$options = self::get_roles();
		$selected = '';
		//$option_values = (array) $original_option_values;	
		
		if( empty( $option_values ) || in_array( 'none', $option_values ) ) {
			$selected = ' selected="selected"';
		}
		
		$html     = '<option value="" ' . $selected .' >----' . /* translators: no option picked in select box */  '</option>';
		
		echo $html;
	
		foreach (  $options  as $role => $label ) {

			$selected = '';
			// Run the allowed option name through the before_save filter, so we'll be sure to get a match
			$allowed_options = xprofile_sanitize_data_value_before_save( $role, false, false );

			// First, check to see whether the user-entered value matches
			if ( in_array( $allowed_options, (array) $option_values ) ) {
					$selected = ' selected="selected"';
			}

			echo  apply_filters( 'bp_get_the_profile_field_options_roles', '<option' . $selected . ' value="' . esc_attr( stripslashes( $role ) ) . '">' . $label . '</option>', $role, $this->field_obj->id, $selected );

		}
				
	}

	public function admin_field_html( array $raw_properties = array() ) {
		
		$this->edit_field_html();

	}
	
	public function admin_new_field_html( BP_XProfile_Field $current_field, $control_type = '' ) {
		
	}
	
	/**
	 * Format Role for display, 
	 *
	 * @param string $field_value The member type name(key) value, as saved in the database.
	 * @return string the member type label
	 */
	public static function display_filter( $field_value, $field_id ='' ) {
		
		if( empty( $field_value ) ) {
			return $field_value;
		}
		
		$roles = self::get_roles();
		
		if( isset( $roles[ $field_value ] ) ){
			return $roles[ $field_value ];
		}
		
		return '';
		
	}
	
	/**
	 * Get member types as associative array
	 * 
	 * @staticvar array $roles array( 'role'=> Label'
	 * @return array
	 */
	private static function get_roles() {
		
		static $roles = null;
		
		if( isset( $roles ) ){
			return $roles;
		}
		
		$roles = bd_pseudo_role_field_get_available_roles();
		
		
		return $roles;
	}
}