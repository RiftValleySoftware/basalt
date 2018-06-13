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

/****************************************************************************************************************************/
/**
This is a plugin that returns information about people and logins.
 */
class CO_people_Basalt_Plugin extends A_CO_Basalt_Plugin {
    /***********************/
    /**
    This returns a fairly short summary of the user or login.
    
    \returns an associative array of strings and integers.
     */
    protected function _get_short_object_description( $in_object    ///< REQUIRED: The user or login object to extract information from.
                                                    ) {
        $ret = Array('id' => $in_object->id(), 'name' => $in_object->name, 'lang' => $in_object->get_lang());
        
        if ($in_object instanceof CO_Security_Login) {
            $ret['login_id'] = $in_object->login_id;
        }
        
        return $ret;
    }

    /***********************/
    /**
    This returns a more comprehensive description of the login.
    
    \returns an associative array of strings and integers.
     */
    protected function _get_long_login_description( $in_login_object    ///< REQUIRED: The login object to extract information from.
                                                ) {
        $ret = $this->_get_short_object_description($in_login_object);
        
        $user_item = $in_login_object->get_user_object();
        
        if ($in_login_object->id() == $in_login_object->get_access_object()->get_login_id()) {
            $ret['current_login'] = true;
        }
        
        if (isset($user_item) && ($user_item instanceof CO_User_Collection)) {
            $ret['user_object_id'] = $user_item->id();
        }
        
        $ret['login_id'] = $in_login_object->login_id;
        $ret['security_tokens'] = $in_login_object->ids();
        $ret['last_login'] = date('Y-m-d H:i:s', $in_login_object->last_access);
        
        if ($in_login_object->user_can_write()) {
            $ret['writeable'] = true;
        }
        
        $api_key = $in_login_object->get_api_key();
        $key_age = $in_login_object->get_api_key_age_in_seconds();

        if ($api_key) {
            // Most people can see whether or not the user has a current API key.
            $ret['current_api_key'] = true;
            // God can see the key, itself.
            if ($in_login_object->get_access_object()->god_mode()) {
                $ret['api_key'] = $api_key;
                //...and how old it is.
                if ( 0 <= $key_age) {
                    $ret['api_key_age_in_seconds'] = $key_age;
                }
            }
        }
        
        return $ret;
    }

    /***********************/
    /**
    This returns a more comprehensive description of the user.
    
    \returns an associative array of strings, integers and nested associative arrays.
     */
    protected function _get_long_user_description(  $in_user_object,            ///< REQUIRED: The user object to extract information from.
                                                    $in_with_login_info = false ///< OPTIONAL: Default is false. If true, then the login information is appended.
                                                ) {
        $ret = $this->_get_short_object_description($in_user_object);
        
        if ($in_with_login_info) {
            $login_instance = $in_user_object->get_login_instance();
        
            $child_objects = $this->_get_child_ids($in_user_object);
            if (0 < count($child_objects)) {
                $ret['children_ids'] = $child_objects;
            }
        
            if ($in_user_object->user_can_write()) {
                $ret['writeable'] = true;
            }
        
            if (isset($login_instance) && ($login_instance instanceof CO_Security_Login)) {
                if ($login_instance->id() == $in_user_object->get_access_object()->get_login_id()) {
                    $ret['current_login'] = true;
                }
        
                $ret['login'] = $this->_get_long_login_description($login_instance);
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns XML, containing the schema for this plugin's responses. The schema needs to be comprehensive.
     */
    protected function _get_xsd() {
        return $this->_process_xsd(dirname(__FILE__).'/schema.xsd');
    }
        
    /***********************/
    /**
    \returns a string, with our plugin name.
     */
    public function plugin_name() {
        return 'people';
    }
        
    /***********************/
    /**
    This handles logins.
    
    \returns an array, with the resulting people.
     */
    protected function _handle_logins(  $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                        $in_http_method,        ///< REQUIRED: 'GET', 'POST', 'PUT' or 'DELETE'
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = [];
        $show_details = isset($in_query) && is_array($in_query) && isset($in_query['show_details']);    // Flag that applies only for lists, forcing all people to be shown in detail.
        
        // See if they want the list of logins for people with logins, or particular people
        if (isset($in_path) && is_array($in_path) && (0 < count($in_path))) {
        
            // Now, we see if they are a list of integer IDs or strings (login string IDs).
            $login_id_list = array_map('trim', explode(',', $in_path[0]));
            
            $is_numeric = array_reduce($login_id_list, function($carry, $item){ return $carry && ctype_digit($item); }, true);
            
            $login_id_list = $is_numeric ? array_map('intval', $login_id_list) : $login_id_list;
            
            foreach ($login_id_list as $id) {
                if (($is_numeric && (0 < $id)) || !$is_numeric) {
                    $login_instance = $is_numeric ? $in_andisol_instance->get_login_item($id) : $in_andisol_instance->get_login_item_by_login_string($id);
                    if (isset($login_instance) && ($login_instance instanceof CO_Security_Login)) {
                        if ($show_details) {
                            $ret[] = $this->_get_long_login_description($login_instance, true);
                        } else {
                            $ret[] = $this->_get_short_object_description($login_instance);
                        }
                    }
                }
            }
        } else {    // They want the list of all of them.
            $login_id_list = $in_andisol_instance->get_all_login_users();
            $login_id_list = $in_andisol_instance->get_cobra_instance()->get_all_logins();
            if (0 < count($login_id_list)) {
                foreach ($login_id_list as $login_instance) {
                    if (isset($login_instance) && ($login_instance instanceof CO_Security_Login)) {
                        if ($show_details) {
                            $ret[] = $this->_get_long_login_description($login_instance, true);
                        } else {
                            $ret[] = $this->_get_short_object_description($login_instance);
                        }
                    }
                }
            }
        }
        
        return $ret;
    }
        
    /***********************/
    /**
    This handles logins.
    
    \returns an array, with the resulting people.
     */
    protected function _handle_people(  $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                        $in_http_method,        ///< REQUIRED: 'GET', 'POST', 'PUT' or 'DELETE'
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = [];
        $login_ids = NULL;
        $login_user = isset($in_query) && is_array($in_query) && isset($in_query['login_user']);    // Flag saying they are only looking for login people.
        $show_details = isset($in_query) && is_array($in_query) && isset($in_query['show_details']);    // Flag that applies only for lists, forcing all people to be shown in detail.
        
        if (isset($in_path) && is_array($in_path) && (1 < count($in_path) && ('login_ids' == $in_path[0]))) {    // See if they are looking for people associated with string login IDs.
            // Now, we see if they are a list of integer IDs or strings (login string IDs).
            $login_id_list = array_map('trim', explode(',', $in_path[1]));
            
            $is_numeric = array_reduce($login_id_list, function($carry, $item){ return $carry && ctype_digit($item); }, true);
            
            $login_id_list = $is_numeric ? array_map('intval', $login_id_list) : $login_id_list;
            
            foreach ($login_id_list as $login_id) {
                $login_instance = $is_numeric ? $in_andisol_instance->get_login_item($login_id) : $in_andisol_instance->get_login_item_by_login_string($login_id);
                
                if (isset($login_instance) && ($login_instance instanceof CO_Security_Login)) {
                    $id_string = $login_instance->login_id;
                    $user = $in_andisol_instance->get_user_from_login_string($id_string);
                    if (isset($user) && ($user instanceof CO_User_Collection)) {
                        if ($show_details) {
                            $ret[] = $this->_get_long_user_description($user, true);
                        } else {
                            $ret[] = $this->_get_short_object_description($user);
                        }
                    }
                }
            }
        } elseif (isset($in_path) && is_array($in_path) && (0 < count($in_path))) { // See if they are looking for a list of individual discrete integer IDs.
            $user_nums = strtolower($in_path[0]);
            
            $single_user_id = (ctype_digit($user_nums) && (1 < intval($user_nums))) ? intval($user_nums) : NULL;    // This will be for if we are looking only one single user.
            // The first thing that we'll do, is look for a list of user IDs. If that is the case, we split them into an array of int.
            $user_list = explode(',', $user_nums);
            
            // If we do, indeed, have a list, we will force them to be ints, and cycle through them.
            if ($single_user_id || (1 < count($user_list))) {
                $user_list = ($single_user_id ? [$single_user_id] : array_map('intval', $user_list));
                
                foreach ($user_list as $id) {
                    if (0 < $id) {
                        $user = $in_andisol_instance->get_single_data_record_by_id($id);
                        if (isset($user) && ($user instanceof CO_User_Collection)) {
                            if (!$login_user || ($login_user && $user->has_login())) {
                                if ($show_details) {
                                    $ret[] = $this->_get_long_user_description($user, true);
                                } else {
                                    $ret[] = $this->_get_short_object_description($user);
                                }
                            }
                        }
                    }
                }
            }
        } else {    // They want the list of all of them.
            $userlist = $in_andisol_instance->get_all_users();
            if (0 < count($userlist)) {
                foreach ($userlist as $user) {
                    if (isset($user) && ($user instanceof CO_User_Collection)) {
                        if (!$login_user || ($login_user && $user->has_login())) {
                            if ($show_details) {
                                $ret[] = $this->_get_long_user_description($user, true);
                            } else {
                                $ret[] = $this->_get_short_object_description($user);
                            }
                        }
                    }
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This runs our plugin command.
    
    \returns the HTTP response string, as either JSON or XML.
     */
    public function process_command(    $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                        $in_http_method,        ///< REQUIRED: 'GET', 'POST', 'PUT' or 'DELETE'
                                        $in_response_type,      ///< REQUIRED: Either 'json' or 'xml' -the response type.
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = [];
        
            // For the default (no user ID), we simply return a list of commands. We also only allow GET to do this.
            if (0 == count($in_path)) {
                if ('GET' == $in_http_method) {
                    $ret = ['people', 'logins'];
                } else {
                    header('HTTP/1.1 400 Incorrect HTTP Request Method');
                    exit();
                }
            } else {
                $main_command = $in_path[0];    // Get the main command.
                array_shift($in_path);
                switch (strtolower($main_command)) {
                    case 'people':
                        $ret['people'] = $this->_handle_people($in_andisol_instance, $in_http_method, $in_path, $in_query);
                        break;
                    case 'logins':
                        $ret['logins'] = $this->_handle_logins($in_andisol_instance, $in_http_method, $in_path, $in_query);
                        break;
                }
            }
        
        return $this->_condition_response($in_response_type, $ret);
    }
}