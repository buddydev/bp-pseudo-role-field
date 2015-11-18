<?php

/**
 * 
 * @global WP_Roles $wp_roles
 * @return type
 */
function bd_pseudo_role_field_get_available_roles() {
	
	global $wp_roles;
        
	if ( empty( $wp_roles ) ) {
         $wp_roles = new WP_Roles();
	}
	
	$all_roles= $wp_roles->get_names();//all roles as role=>role_name
        
    return $all_roles;
    
}

/**
 * Get all the xprofile field ids whose type is role
 * 
 * @global type $wpdb
 * @return array of field ids
 */
function bd_pseudo_get_role_fields() {
	
//to optimize, we should store it in some type of transient or options but that will need us to clear/update when new fields are added or deleted/updated
	//too much for a tiny tiny plugin like this
	global $wpdb;
	$bp = buddypress();
	$sql  = $wpdb->prepare( "SELECT id FROM {$bp->profile->table_name_fields} WHERE type = %s", 'role' );
	
	$field_ids = $wpdb->get_col( $sql );
	
	return $field_ids;
}
/**
 * Get all user Ids for a given role
 * Not recommende to use on larger sites with more than 2000 members
 * 
 * @param sring $role WordPress role 
 * @return array 
 */
function bd_pseudo_get_users_by_role($role){
    
	$user_ids = get_users( array( 'role' => $role, 'fields'=> 'ID' ) );
 
   return $user_ids;
}
