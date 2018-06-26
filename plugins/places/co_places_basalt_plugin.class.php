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
This is a REST plugin that allows access to places (locations).
 */
class CO_places_Basalt_Plugin extends A_CO_Basalt_Plugin {
    /***********************/
    /**
    This returns a fairly short summary of the place.
    
    \returns an associative array of strings and integers.
     */
    protected function _get_short_description(  $in_object  ///< REQUIRED: The user or login object to extract information from.
                                            ) {
        $ret = parent::_get_short_description($in_object);
        $longitude = $in_object->longitude();
        $latitude = $in_object->latitude();
        
        if (isset($longitude) && is_float($longitude) && isset($latitude) && is_float($latitude)) {
            $ret['coords'] = sprintf("%f,%f", $latitude, $longitude);
        }
        
        $address = trim($in_object->get_readable_address());
        
        if (isset($address) && $address) {
            $ret['address'] = $address;
        }
        
        if (isset($in_object->distance)) {
            $ret['distance_in_km'] = $in_object->distance;
        }
        
        return $ret;
    }

    /***********************/
    /**
    This returns a more comprehensive description of the place.
    
    \returns an associative array of strings and integers.
     */
    protected function _get_long_place_description( $in_place_object
                                                    ) {
        $ret = parent::_get_long_description($in_place_object);
        $longitude = $in_place_object->longitude();
        $latitude = $in_place_object->latitude();

        if (isset($longitude) && is_float($longitude) && isset($latitude) && is_float($latitude)) {
            $ret['latitude'] = floatval($latitude);
            $ret['longitude'] = floatval($longitude);
        }
        
        if ($in_place_object->is_fuzzy()) {
            $ret['fuzzy'] = true;
            
            // If this is a fuzzy location, but the logged-in user can see "the real," we show it to them.
            if ($in_place_object->i_can_see_clearly_now()) {
                $ret['raw_latitude'] = floatval($in_place_object->raw_latitude());
                $ret['raw_longitude'] = floatval($in_place_object->raw_longitude());
                $ret['fuzz_factor'] = $in_place_object->fuzz_factor();
            }
        }
        
        $address_elements = $in_place_object->get_address_elements();
        
        if (0 < count($address_elements)) {
            $ret['address_elements'] = $address_elements;
        }

        $child_objects = $this->_get_child_ids($in_place_object);
        if (0 < count($child_objects)) {
            $ret['child_ids'] = $child_objects;
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
    Parses the query parameters and cleans them for the database.
    
    \returns an associative array of the parameters, parsed for submission to the database.
     */
    protected function _process_parameters( $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                            $in_query               ///< REQUIRED: The query string to be parsed.
                                        ) {
        $ret = [];
        if (isset($in_query) && is_array($in_query)) {
            // See if they want to add new child data items to each user, or remove existing ones.
            // We indicate adding ones via positive integers (the item IDs), and removing via negative integers (minus the item ID).
            if (isset($in_query['child_ids'])) {
                $ret['child_ids'] = Array('add' => [], 'remove' => []);
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
            
            if (isset($in_query['name'])) {
                $ret['name'] = floatval($in_query['name']);
            }
            
            if (isset($in_query['longitude'])) {
                $ret['longitude'] = floatval($in_query['longitude']);
            }
            
            if (isset($in_query['latitude'])) {
                $ret['latitude'] = floatval($in_query['latitude']);
            }
            
            if (isset($in_query['fuzz_factor'])) {
                $ret['fuzz_factor'] = floatval($in_query['fuzz_factor']);
            }
            
            if (isset($in_query['address_venue'])) {
                $ret['address_venue'] = floatval($in_query['address_venue']);
            }
            
            if (isset($in_query['address_street_address'])) {
                $ret['address_street_address'] = floatval($in_query['address_street_address']);
            }
            
            if (isset($in_query['address_extra_information'])) {
                $ret['address_extra_information'] = floatval($in_query['address_extra_information']);
            }
            
            if (isset($in_query['address_town'])) {
                $ret['address_town'] = floatval($in_query['address_town']);
            }
            
            if (isset($in_query['address_county'])) {
                $ret['address_county'] = floatval($in_query['address_county']);
            }
            
            if (isset($in_query['address_state'])) {
                $ret['address_state'] = floatval($in_query['address_state']);
            }
            
            if (isset($in_query['address_postal_code'])) {
                $ret['address_postal_code'] = floatval($in_query['address_postal_code']);
            }
            
            if (isset($in_query['address_nation'])) {
                $ret['address_nation'] = floatval($in_query['address_nation']);
            }
            
            // Next, we see if we want to change/set the "owner" object asociated with this. You can remove an associated owner object by passing in NULL or 0, here.
            if (isset($in_query['owner_id'])) {
                $ret['owner_id'] = abs(intval(trim($in_query['owner_id'])));
            }
        
            // Next, look for the language.
            if (isset($in_query['lang'])) {
                $ret['lang'] = trim(strval($in_query['lang']));
            }
            
            // Next, look for the last tag (the only one we're allowed to change).
            if (isset($in_query['tag9'])) {
                $ret['tag9'] = trim(strval($in_query['tag9']));
            }
        
            // Next, we see if we the user is supplying a payload to be stored, or removing the existing one.
            if (isset($in_query['remove_payload'])) { // If they did not specify a payload, maybe they want one removed?
                $ret['remove_payload'] = true;
            } elseif (isset($in_query['payload'])) {
                $ret['payload'] = $in_query['payload'];
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an associative array, with the "raw" response.
     */
    protected function _process_place_put(  $in_andisol_instance,       ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                            $in_object_list = [],       ///< OPTIONAL: This function is worthless without at least one object. This will be an array of place objects, holding the places to examine.
                                            $in_path = [],              ///< OPTIONAL: The REST path, as an array of strings.
                                            $in_query = []              ///< OPTIONAL: The query parameters, as an associative array.
                                        ) {
        $ret = ['changed_places' => []];
        
        $fuzz_factor = isset($in_query) && is_array($in_query) && isset($in_query['fuzz_factor']) ? floatval($in_query['fuzz_factor']) : 0; // Set any fuzz factor.
        
        $parameters = $this->_process_parameters($in_andisol_instance, $in_query);
            
        if (isset($parameters) && is_array($parameters) && count($parameters) && isset($in_object_list) && is_array($in_object_list) && count($in_object_list)) {
            foreach ($in_object_list as $place) {
                if ($place->user_can_write()) { // Belt and suspenders. Make sure we can write.
                    $changed_place = ['before' => $this->_get_long_place_description($place)];
                    $result = true;
                    
                    if ($result && isset($parameters['name'])) {
                        $result = $place->set_name($parameters['name']);
                    }
                    
                    if ($result && isset($parameters['lang'])) {
                        $result = $place->set_name($parameters['lang']);
                    }
                    
                    if ($result && isset($parameters['longitude'])) {
                        $result = $place->set_longitude($parameters['longitude']);
                    }
                    
                    if ($result && isset($parameters['latitude'])) {
                        $result = $place->set_latitude($parameters['latitude']);
                    }
                    
                    if ($result && isset($parameters['fuzz_factor'])) {
                        $result = $place->set_fuzz_factor($parameters['fuzz_factor']);
                    }
                    
                    if ($result && isset($parameters['address_venue'])) {
                        $result = $place->set_address_element(0, $parameters['address_venue']);
                    }
                    
                    if ($result && isset($parameters['address_street_address'])) {
                        $result = $place->set_address_element(1, $parameters['address_street_address']);
                    }
                    
                    if ($result && isset($parameters['address_extra_information'])) {
                        $result = $place->set_address_element(2, $parameters['address_extra_information']);
                    }
                    
                    if ($result && isset($parameters['address_town'])) {
                        $result = $place->set_address_element(3, $parameters['address_town']);
                    }
                    
                    if ($result && isset($parameters['address_county'])) {
                        $result = $place->set_address_element(4, $parameters['address_county']);
                    }
                    
                    if ($result && isset($parameters['address_state'])) {
                        $result = $place->set_address_element(5, $parameters['address_state']);
                    }
                    
                    if ($result && isset($parameters['address_postal_code'])) {
                        $result = $place->set_address_element(6, $parameters['address_postal_code']);
                    }
                    
                    if ($result && isset($parameters['address_nation'])) {
                        $result = $place->set_address_element(7, $parameters['address_nation']);
                    }
                    
                    if ($result && isset($parameters['tag8'])) {
                        $result = $place->set_tag(8, $parameters['tag8']);
                    }
                    
                    if ($result && isset($parameters['tag9'])) {
                        $result = $place->set_tag(9, $parameters['tag9']);
                    }
                    
                    if ($result && isset($parameters['remove_payload'])) {
                        $result = $place->set_payload(NULL);
                    } elseif ($result && isset($parameters['payload'])) {
                        $result = $place->set_payload($parameters['payload']);
                    }
                    
                    if ($result && isset($parameters['child_ids'])) {
                        $add = $parameters['child_ids']['add'];
                        $remove = $parameters['child_ids']['remove'];
                    
                        foreach ($remove as $id) {
                            if ($id != $place->id()) {
                                $child = $in_andisol_instance->get_single_data_record_by_id($id);
                                if (isset($child)) {
                                    $result = $place->deleteThisElement($child);
                                }
                        
                                if (!$result) {
                                    break;
                                }
                            }
                        }
                        
                        if ($result) {
                            foreach ($add as $id) {
                                if ($id != $place->id()) {
                                    $child = $in_andisol_instance->get_single_data_record_by_id($id);
                                    if (isset($child)) {
                                        $result = $place->appendElement($child);
                                        
                                        if (!$result) {
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($result) {
                        $changed_place['after'] = $this->_get_long_place_description($place);
                        $ret['changed_places'][] = $changed_place;
                    }
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an associative array, with the "raw" response.
     */
    protected function _process_place_get(  $in_andisol_instance,       ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                            $in_object_list = [],       ///< OPTIONAL: This function is worthless without at least one object. This will be an array of place objects, holding the places to examine.
                                            $in_show_details = false,   ///< OPTIONAL: If true (default is false), then the resulting record will be returned in "detailed" format.
                                            $in_path = [],              ///< OPTIONAL: The REST path, as an array of strings.
                                            $in_query = []              ///< OPTIONAL: The query parameters, as an associative array.
                                        ) {
        $ret = [];
        
        if (isset($in_object_list) && is_array($in_object_list) && (0 < count($in_object_list))) {
            foreach ($in_object_list as $place) {
                if ($in_show_details) {
                    $ret[] = $this->_get_long_place_description($place);
                } else {
                    $ret[] = $this->_get_short_description($place);
                }
            }
        }
        
        return $ret;
    }
        
    /***********************/
    /**
    \returns a string, with our plugin name.
     */
    public function plugin_name() {
        return 'places';
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
        $show_details = isset($in_query) && is_array($in_query) && isset($in_query['show_details']);    // Show all places in detail (applies only to GET).
        $writeable = isset($in_query) && is_array($in_query) && isset($in_query['writeable']);          // Show/list only places this user can modify.

        // For the default (no place ID), we simply act on a list of all available places (or ones selected by a radius/long lat search).
        if (0 == count($in_path)) {
            $radius = isset($in_query) && is_array($in_query) && isset($in_query['radius']) && (0.0 < floatval($in_query['radius'])) ? floatval($in_query['radius']) : NULL;
            $longitude = isset($in_query) && is_array($in_query) && isset($in_query['longitude']) ? floatval($in_query['longitude']) : NULL;
            $latitude = isset($in_query) && is_array($in_query) && isset($in_query['latitude']) ? floatval($in_query['latitude']) : NULL;
            
            $location_search = NULL;
            
            if (isset($radius) && isset($longitude) && isset($latitude)) {
                $location_search = Array('radius' => $radius, 'longitude' => $longitude, 'latitude' => $latitude);
            }
            
            $class_search = Array('%_Place_Collection', 'use_like' => 1);
            
            $search_array['access_class'] = $class_search;
            
            if (isset($location_search)) {
                $search_array['location'] = $location_search;
            }

            $placelist = $in_andisol_instance->generic_search($search_array, false, 0, 0, $writeable);
            
            if ('GET' == $in_http_method) {
                $ret = $this->_process_place_get($in_andisol_instance, $placelist, $show_details, $in_path, $in_query);
            } elseif ('PUT' == $in_http_method) {
                $ret = $this->_process_place_put($in_andisol_instance, $placelist, $in_path, $in_query);
            }
        } else {
            $main_command = $in_path[0];    // Get the main command.
        
            // This tests to see if we only got one single digit as our "command."
            $single_place_id = (ctype_digit($main_command) && (1 < intval($main_command))) ? intval($main_command) : NULL;    // This will be for if we are looking only one single place.
        
            // The first thing that we'll do, is look for a list of place IDs. If that is the case, we split them into an array of int.
        
            $place_id_list = explode(',', $main_command);
        
            // If we do, indeed, have a list, we will force them to be ints, and cycle through them.
            if ($single_place_id || (1 < count($place_id_list))) {
                $place_id_list = ($single_place_id ? [$single_place_id] : array_unique(array_map('intval', $place_id_list)));
                $placelist = [];
                
                foreach ($place_id_list as $id) {
                    if (0 < $id) {
                        $place = $in_andisol_instance->get_single_data_record_by_id($id);
                        if (isset($place) && ($place instanceof CO_Place)) {
                            $placelist[] = $place;
                        }
                    }
                }
                
                if ('GET' == $in_http_method) {
                    $ret = $this->_process_place_get($in_andisol_instance, $placelist, $show_details, $in_path, $in_query);
                } elseif ('PUT' == $in_http_method) {
                    $ret = $this->_process_place_put($in_andisol_instance, $placelist, $in_path, $in_query);
                }
            } else {    // Otherwise, let's see what they want to do...
                switch ($main_command) {
                }
            }
        }
        
        return $this->_condition_response($in_response_type, $ret);
    }
}