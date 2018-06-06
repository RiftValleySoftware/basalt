<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
defined( 'LGV_BASALT_CATCHER' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.

require_once(CO_Config::main_class_dir().'/a_co_basalt_plugin.class.php');

/****************************************************************************************************************************/
/**
 */
class CO_users_Basalt_Plugin extends A_CO_Basalt_Plugin {
    /***********************/
    /**
     */
    protected function _get_short_user_description($in_user_object) {
        $ret = Array('id' => $in_user_object->id(), 'name' => $in_user_object->name);
        
        $login_instance = $in_user_object->get_login_instance();
        
        if (isset($login_instance) && ($login_instance instanceof CO_Security_Login)) {
            $ret['login_id'] = $login_instance->id();
        }
        
        return $ret;
    }

    /***********************/
    /**
     */
    protected function _get_long_user_description($in_user_object) {
        $ret = Array('id' => $in_user_object->id(), 'name' => $in_user_object->name);
        
        $login_instance = $in_user_object->get_login_instance();
        
        if (isset($login_instance) && ($login_instance instanceof CO_Security_Login)) {
            $ret['login_id'] = $login_instance->id();
        }
        
        return $ret;
    }

    /***********************/
    /**
    This returns the appropriate XML header for our response.
    
    \returns a string, with the entire XML header (including the preamble).
     */
    protected function _get_xml_header() {
        return '';
    }
    
    /***********************/
    /**
    This returns the schema for this plugin as XML XSD.
    
    \returns XML, containing the schema for this plugin's responses. The schema needs to be comprehensive.
     */
    protected function _get_xsd() {
        return '';
    }
        
    /***********************/
    /**
    This runs our plugin name.
    
    \returns a string, with our plugin name.
     */
    public function plugin_name() {
        return 'users';
    }
    
    /***********************/
    /**
    This runs our plugin command.
    
    \returns the HTTP response string, as either JSON or XML.
     */
    public function process_command(    $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                        $in_response_type,      ///< REQUIRED: Either 'json' or 'xml' -the response type.
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = [];
        
        // For the default (no user ID), we simply return a list of users, in "short" format.
        if (0 == count($in_path)) {
            $userlist = $in_andisol_instance->get_all_users();
            
            if (0 < count($userlist)) {
                foreach ($userlist as $user) {
                    $ret[] = $this->_get_short_user_description($user);
                }
            }
        } else {
            $main_command = $in_path[0];    // Get the main command.
            
            // The first thing that we'll do, is look for a list of user IDs. If that is the case, we split them into an array of int.
            
            $user_list = explode(',', $main_command);
            
            // If we do, indeed, have a list, we will force them to be ints, and cycle through them.
            if (1 < count($user_list)) {
                $user_list = array_map('intval', $user_list);
                
                foreach ($user_list as $id) {
                    if (0 < $id) {
                        $user = $in_andisol_instance->get_single_data_record_by_id($id);
                        if (isset($user) && ($user instanceof CO_User_Collection)) {
                            $ret[] = $this->_get_long_user_description($user);
                        }
                    }
                }
            } else {
            }
        }
        
        return $this->_condition_response($in_response_type, $ret);
    }
}