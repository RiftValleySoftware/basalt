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
    protected function _get_short_description(  $in_object,                 ///< REQUIRED: The user or login object to extract information from.
                                                $in_additional_info = false ///< OPTIONAL: If true (default is false), then some extra information will be added to the basic ID and name.
                                            ) {
        $ret = parent::_get_short_description($in_object, $in_additional_info);
        $ret = Array('id' => $in_object->id());
        $longitude = $in_object->longitude();
        $latitude = $in_object->latitude();
        $name = $in_object->name;
        
        if (isset($longitude) && is_float($longitude) && isset($latitude) && is_float($latitude)) {
            $ret['coords'] = sprintf("%f,%f", $latitude, $longitude);
        }
        
        if (isset($in_object->distance)) {
            $ret['distance'] = $in_object->distance;
        }
        
        if ($in_object->is_fuzzy()) {
            $ret['fuzzy'] = true;
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
        $ret = $this->_get_short_description($in_place_object, true);
        
        $longitude = $in_place_object->longitude();
        $latitude = $in_place_object->latitude();

        if (isset($longitude) && is_float($longitude) && isset($latitude) && is_float($latitude)) {
            $ret['latitude'] = floatval($latitude);
            $ret['longitude'] = floatval($longitude);
        }
        
        $address = $in_place_object->get_readable_address();
        
        if (isset($address) && $address) {
            $ret['address'] = $address;
        }
        
        $address_elements = $in_place_object->get_address_elements();
        
        if (0 < count($address_elements)) {
            $ret['address_elements'] = $address_elements;
        }
        
        if ($in_place_object->is_fuzzy()) {
            $ret['fuzz_factor'] = $in_place_object->fuzz_factor();
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
        $show_details = isset($in_query) && is_array($in_query) && isset($in_query['show_details']);    // Flag that applies only for lists, forcing all people to be shown in detail.
        $radius = isset($in_query) && is_array($in_query) && isset($in_query['radius']) && (0.0 < floatval($in_query['radius'])) ? floatval($in_query['radius']) : NULL;
        $longitude = isset($in_query) && is_array($in_query) && isset($in_query['longitude']) ? floatval($in_query['longitude']) : NULL;
        $latitude = isset($in_query) && is_array($in_query) && isset($in_query['latitude']) ? floatval($in_query['latitude']) : NULL;

        // For the default (no place ID), we simply return a list of places.
        if (0 == count($in_path)) {
            $location_search = NULL;
            
            if (isset($radius) && isset($longitude) && isset($latitude)) {
                $location_search = Array('radius' => $radius, 'longitude' => $longitude, 'latitude' => $latitude);
            }
            
            $class_search = Array('%_Place_Collection', 'use_like' => 1);
            
            $search_array['access_class'] = $class_search;
            
            if (isset($location_search)) {
                $search_array['location'] = $location_search;
            }
            
            $placelist = $in_andisol_instance->generic_search($search_array);
        
            if (isset($placelist) && is_array($placelist) && (0 < count($placelist))) {
                foreach ($placelist as $place) {
                    if ($show_details) {
                        $ret[] = $this->_get_long_place_description($place);
                    } else {
                        $ret[] = $this->_get_short_description($place);
                    }
                }
            }
        } else {
            $main_command = $in_path[0];    // Get the main command.
        
            // This tests to see if we only got one single digit as our "command."
            $single_place_id = (ctype_digit($main_command) && (1 < intval($main_command))) ? intval($main_command) : NULL;    // This will be for if we are looking only one single place.
        
            // The first thing that we'll do, is look for a list of place IDs. If that is the case, we split them into an array of int.
        
            $place_id_list = explode(',', $main_command);
        
            // If we do, indeed, have a list, we will force them to be ints, and cycle through them.
            if ($single_place_id || (1 < count($place_id_list))) {
                $place_id_list = ($single_place_id ? [$single_place_id] : array_map('intval', $place_id_list));
            
                foreach ($place_id_list as $id) {
                    if (0 < $id) {
                        $place = $in_andisol_instance->get_single_data_record_by_id($id);
                        if (isset($place) && ($place instanceof CO_Place)) {
                            if ($show_details) {
                                $ret[] = $this->_get_long_place_description($place);
                            } else {
                                $ret[] = $this->_get_short_description($place);
                            }
                        }
                    }
                }
            } else {    // Otherwise, let's see what they want to do...
                switch ($main_command) {
                }
            }
        }
        
        return $this->_condition_response($in_response_type, $ret);
    }
}