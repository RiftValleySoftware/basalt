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
        
            if (0 < $in_user_object->owner_id()) {
                $ret['owner_id'] = $in_user_object->owner_id();
            }
        
            if (isset($login_instance) && ($login_instance instanceof CO_Security_Login)) {
                if ($login_instance->id() == $in_user_object->get_access_object()->get_login_id()) {
                    $ret['current_login'] = true;
                }
        
                $ret['login'] = $this->_get_long_login_description($login_instance);
            }
        }
        
        $payload = $in_user_object->get_payload();
        if ($payload) {
            $ret['payload'] = base64_encode($payload);
            $temp_file = tempnam(sys_get_temp_dir(), 'RVP');  
            file_put_contents($temp_file , $payload);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);  
            $content_type = finfo_file($finfo, $temp_file);
            $ret['payload_type'] = $content_type;
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
     */
    protected function _handle_edit_people( $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                            $in_http_method,        ///< REQUIRED: 'GET', 'POST', 'PUT' or 'DELETE'
                                            $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings.
                                            $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                        ) {
        $ret = NULL;
        
        $login_user = isset($in_query) && is_array($in_query) && isset($in_query['login_user']);    // Flag saying they are only looking for login people.
        
        if (0 && ('POST' == $in_http_method) && $in_andisol_instance->manager()) {    // First, see if they want to create a new user. This one is fairly easy. We have to be a manager to create users and logins.
        } else {
            // Otherwise, we build up a userlist.
            $user_object_list = [];
            
            // See if the users are being referenced by login ID.
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
                        if ($user->user_can_write() && $user->has_login()) {
                            $user_object_list[] = $user;
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
                                if (!$login_user || ($login_user && $user->has_login()) && $user->user_can_write()) {
                                    $user_object_list[] = $user;
                                }
                            }
                        }
                    }
                }
            } else {
                $userlist = $in_andisol_instance->get_all_users();
                if (0 < count($userlist)) {
                    foreach ($userlist as $user) {
                        if (isset($user) && ($user instanceof CO_User_Collection)) {
                            if (!$login_user || ($login_user && $user->has_login()) && $user->user_can_write()) {
                                $user_object_list[] = $user;
                            }
                        }
                    }
                }
            }
        
            // At this point, we have a list of writable user objects.
            // Now, if we are not a manager, then the only object we have the right to alter is our own.
            if (!$in_andisol_instance->manager()) {
                $temp = NULL;
                foreach ($user_object_list as $user) {
                    if ($user == $in_andisol_instance->current_user()) {
                        $temp = $user;
                        break;
                    }
                }
                
                $user_object_list = [];
                
                if (isset($temp)) {
                    $user_object_list = [$temp];
                }
            }
            
            // At this point, we have a fully-vetted list of users for modification, or none. If none, we react badly.
            if (0 == count($user_object_list)) {
                header('HTTP/1.1 403 No Editable Records'); // I don't think so. Homey don't play that game.
                exit();
            } elseif (('DELETE' == $in_http_method) && $in_andisol_instance->manager()) {   // DELETE is fairly straightforward, but we have to be a manager.
                // We also can't delete ourselves, so we will remove any items that are us.
                $temp = [];
                foreach ($user_object_list as $user) {
                    if ($user != $in_andisol_instance->current_user()) {
                        $temp[] = $user;
                    }
                }
                
                $user_object_list = $temp;
                
                // We now have a list of items to delete. However, we also need to see if we have full rights to logins, if logins were also indicated.
                if ($login_user) {  // We can only delete user/login pairs for which we have write permissions on both.
                    $temp = [];
                    foreach ($user_object_list as $user) {
                        $login_item = $user->get_login_instance();
                        if ($login_item->user_can_write()) {
                            $temp[] = $user;
                        }
                    }
                
                    $user_object_list = $temp;
                }
                
                // Review what we have. If nothing, throw a hissy fit.
                if (0 == count($user_object_list)) {
                    header('HTTP/1.1 403 No Editable Records'); // I don't think so. Homey don't play that game.
                    exit();
                }
                
                // Now, we have a full list of users that we have permission to delete.
                foreach ($user_object_list as $user) {
                    $user_dump = $this->_get_long_user_description($user);
                    $login_dump = NULL;
                    
                    $ok = true;
                    
                    if ($login_user) {
                        $login_item = $user->get_login_instance();
                        $login_dump = $this->_get_long_login_description($login_item);
                        $ok = $login_item->delete_from_db();
                    }
                    
                    if ($ok) {
                        $ok = $user->delete_from_db();
                    }
                    
                    // We return a record of the deleted IDs.
                    if ($ok) {
                        if (!isset($ret) || !is_array($ret)) {
                            $ret = ['deleted_users' => []];
                            if ($login_user) {
                                $ret['deleted_logins'] = [];
                            }
                        }
                        
                        $ret['deleted_users'][] = $user_dump;
                        
                        if ($login_dump) {
                            $ret['deleted_logins'][] = $login_dump;
                        }
                    }
                }
                // OK. We have successfully deleted the users (and maybe the logins, as well). We will return the dumps of the users and logins in the function return as associative arrays.
            } elseif (1 || 'PUT' == $in_http_method) {   // We want to modify existing users.
                $mod_list = $this->_build_mod_list($in_andisol_instance, $in_query);
                $ret = [];
                
                foreach ($user_object_list as $user) {
                    $user_report = Array('before' => $this->_get_long_user_description($user));
                    foreach ($mod_list as $key => $value) {
                        switch ($key) {
                            case 'payload':
                                $result = $user->set_payload($value);
                                break;
                                
                            case 'name':
                                $result = $user->set_name($value);
                                break;
                        }
                    }
                    
                    $user_report['after'] = $this->_get_long_user_description($user);
                    $ret['changed_users'][] = $user_report;
                }
            } else {
                header('HTTP/1.1 400 Incorrect HTTP Request Method');   // Ah-Ah-Aaaahh! You didn't say the magic word!
                exit();
            }
        }
        
        return $ret;
    }
            
    /***********************/
    /**
     */
    protected function _build_mod_list( $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array. If left empty, this method is worthless.
                                        ) {
        // <rubs hands/> Now, let's get to work...
        // First, build up a list of the items that we want to change.
    
        $ret = [];   // We will build up an associative array of changes we want to make.
    
        // See if they want to add new child data items to each user, or remove existing ones.
        // We indicate adding ones via positive integers (the item IDs), and removing via negative integers (minus the item ID).
        if (isset($in_query['child_ids'])) {
            $child_item_list = [];          // If we are adding new child items, their IDs go in this list.
            $delete_child_item_list = [];   // If we are removing items, we indicate that with negative IDs, and put those in a different list (absvaled).
            
            $child_id_list = array_map('intval', explode(',', $in_query['child_ids']));
        
            // Child IDs are a CSV list of integers, with IDs of data records.
            if (isset($child_id_list) && is_array($child_id_list) && count($child_id_list)) {
                // Check for ones we can't see (we don't need write permission, but we do need read permission).
                foreach ($child_id_list as $id) {
                    if (0 < $id) {  // See if we are adding to the list
                        $item = $in_andisol_instance->get_single_data_record_by_id($id);
                        // If we got the item, then it exists, and we can see it. Add its ID to our list.
                        $child_item_list[] = $id;
                    } else {    // If we are removing it, we still need read permission, but it goes in a different list.
                        $item = $in_andisol_instance->get_single_data_record_by_id(-$id);
                        $delete_child_item_list[] = -$id;
                    }
                }
            
                // Make sure there's no repeats.
                $child_item_list = array_unique($child_item_list);
                $delete_child_item_list = array_unique($delete_child_item_list);
                
                // Because we're anal.
                sort($child_item_list);
                sort($delete_child_item_list);
                
                // At this point, we have a list of IDs that we want to add, and IDs that we want to remove, from the various (or single) users.
            }
            
            // If we have items we want to add, we add them to our TO DO list.
            if (isset($child_item_list) && is_array($child_item_list) && count($child_item_list)) {
                $ret['child_ids']['add'] = $child_item_list;
            }
            
            // If we have items we want to remove, we add those to our TO DO list.
            if (isset($delete_child_item_list) && is_array($delete_child_item_list) && count($delete_child_item_list)) {
                $ret['child_ids']['remove'] = $delete_child_item_list;
            }
        }
        
        // Next, we see if we want to change the name.
        if (isset($in_query['name'])) {
            $ret['name'] = trim(strval($in_query['name']));
        }
        
        // Next, we see if we want to change/set the login object asociated with this. You can remove an associated login object by passing in NULL or 0, here.
        if (isset($in_query['login_id'])) {
            $ret['login_id'] = abs(intval(trim($in_query['login_id'])));
        }
        
        // Next, we see if we want to change/set the "owner" object asociated with this. You can remove an associated owner object by passing in NULL or 0, here.
        if (isset($in_query['owner_id'])) {
            $ret['owner_id'] = abs(intval(trim($in_query['owner_id'])));
        }
        
        // Next, we see if we the user is supplying a payload to be stored.
        if (isset($in_query['payload'])) {
            $ret['payload'] = $in_query['payload'];
        } elseif (isset($in_query['remove_payload'])) { // If they did not specify a payload, maybe they want one removed?
            $ret['remove_payload'] = true;
        }
        
        // See if they want to modify any tags.
        for ($tag = 0; $tag < 10; $tag++) {
            $tag_name = "tag".strval($tag);
            
            // Next, we see if we want to change the tag value. You can set a tag value to an empty string (specify "tag[0-9]=" in the URI).
            if (isset($in_query['tag_name'])) {
                $ret['tag_name'] = trim(strval($in_query['tag_name']));
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
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = [];
        $login_user = isset($in_query) && is_array($in_query) && isset($in_query['login_user']);    // Flag saying they are only looking for login people.
        $show_details = isset($in_query) && is_array($in_query) && isset($in_query['show_details']);    // Flag that indicates that all people be shown in detail.
        
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
                        if ('GET' == $in_http_method) {
                            $ret['people'] = $this->_handle_people($in_andisol_instance, $in_path, $in_query);
                        } elseif ($in_andisol_instance->logged_in()) {  // Must be logged in to be non-GET.
                            $ret['people'] = $this->_handle_edit_people($in_andisol_instance, $in_http_method, $in_path, $in_query);
                        } else {
                            header('HTTP/1.1 400 Incorrect HTTP Request Method');
                            exit();
                        }
                        break;
                    case 'logins':
                        if ('GET' == $in_http_method) {
                            $ret['logins'] = $this->_handle_logins($in_andisol_instance, $in_path, $in_query);
                        } elseif ($in_andisol_instance->logged_in()) {  // Must be logged in to be non-GET.
                        } else {
                            header('HTTP/1.1 400 Incorrect HTTP Request Method');
                            exit();
                        }
                        break;
                }
            }
        
        return $this->_condition_response($in_response_type, $ret);
    }
}