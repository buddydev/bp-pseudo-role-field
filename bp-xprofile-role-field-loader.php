<?php

/**
 * Plugin Name: BP Pseudo Role Field
 * Plugin URI: http://buddydev.com/plugins/bp-pseudo-role-field/
 * Version: 1.0.0
 * Author: BuddyDev.Com
 * Author URI: http://buddydev.com
 * Description: Create Pseudo Role type field not visible to 
 */


class BD_Pseudo_Role_Field_Helper {
    
    /**
     *
     * @var BD_Pseudo_Role_Field_Helper
     */
    private static $instance;
    /**
     * Path to this plugin directory
     * @var string 
     */
    private $path = '';
    
    /**
     * The url to this plugin directory
     * @var string url 
     */
    private $url = '';
    
	
    private function __construct() {
        
        $this->path = plugin_dir_path( __FILE__ ); //with trailing slash
        $this->url  = plugin_dir_url( __FILE__ ); //with trailing slash
        
        add_action( 'bp_loaded', array( $this, 'load' ) );
        
        //add_action( 'admin_print_scripts', array( $this, 'load_admin_js' ) );
		
		add_filter( 'bp_xprofile_get_field_types', array( $this, 'add_field_types' ) );
		
		//Do not show role to user
		//add_action( 'bp_custom_profile_edit_fields_pre_visibility', array( $this, 'may_be_show_field' ) );
		
		//roles are wp assigned and we do not update it
		//add_action( 'xprofile_data_after_save', array( $this, 'update_member_type' ) );
        
    }
    
    /**
     * 
     * @return BD_Pseudo_Role_Field_Helper
     */
    public static function get_instance() {
        
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
		}	
        
        return self::$instance;
    }
    
    public function load() {
        
        
        $files = array(
			'class-role-field.php',
			'bp-role-field-functions.php',
			'bp-role-field-hooks.php',
			
        );
        
        foreach( $files as $file ) {
            require_once $this->path . $file;
		}
        
    }
    
	/**
	 * Register our custom role field type to BuddyPress 
	 * 
	 * @param array $filed_types
	 * @return string
	 */
	public function add_field_types( $filed_types ) {
		
		$filed_types['role'] = 'BD_Pseudo_Role_Field_Type';
		
		return  $filed_types;
		
	}
	
	

}
//init
bd_pseudo_role_field_helper();


/**
 * 
 * @return BD_Pseudo_Role_Field_Helper
 */
function bd_pseudo_role_field_helper() {
	
	return BD_Pseudo_Role_Field_Helper::get_instance();
}
