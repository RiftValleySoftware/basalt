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
        
        $address = $in_object->get_readable_address();
        
        if (isset($address) && $address) {
            $ret['address'] = $address;
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
        $ret = parent::_get_long_description($in_place_object);
        $longitude = $in_place_object->longitude();
        $latitude = $in_place_object->latitude();

        if (isset($longitude) && is_float($longitude) && isset($latitude) && is_float($latitude)) {
            $ret['latitude'] = floatval($latitude);
            $ret['longitude'] = floatval($longitude);
        }
        
        if ($in_place_object->is_fuzzy()) {
            $ret['fuzz_factor'] = $in_place_object->fuzz_factor();
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
    \returns a string, with our plugin name.
     */
    public function plugin_name() {
        return 'places';
    }
    
    /***********************/
    /**
    \returns an associative array, with the "raw" response.
     */
    public function process_place_put(  $in_andisol_instance,       ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                        $in_object_list = [],       ///< OPTIONAL: This function is worthless without at least one object. This will be an array of place objects, holding the places to examine.
                                        $in_path = [],              ///< OPTIONAL: The REST path, as an array of strings.
                                        $in_query = []              ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = ['changed_places' => []];
        
        $fuzz_factor = isset($in_query) && is_array($in_query) && isset($in_query['fuzz_factor']) ? floatval($in_query['fuzz_factor']) : 0; // Set any fuzz factor.
        if (isset($in_object_list) && is_array($in_object_list) && (0 < count($in_object_list))) {
            foreach ($in_object_list as $place) {
                if ($place->user_can_write()) { // Belt and suspenders. Make sure we can write.
                    $changed_place = ['before' => $this->_get_long_place_description($place)];
                    $result = $place->set_fuzz_factor($fuzz_factor);
                    if ($result) {
                        $changed_place['after'] = $this->_get_long_place_description($place);
                        $changed_place['after']['last_access'] = date('Y-m-d H:i:s');
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
    public function process_place_get(  $in_andisol_instance,       ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
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
                $ret = $this->process_place_get($in_andisol_instance, $placelist, $show_details, $in_path, $in_query);
            } elseif ('PUT' == $in_http_method) {
                $ret = $this->process_place_put($in_andisol_instance, $placelist, $in_path, $in_query);
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
                    $ret = $this->process_place_get($in_andisol_instance, $placelist, $show_details, $in_path, $in_query);
                } elseif ('PUT' == $in_http_method) {
                    $ret = $this->process_place_put($in_andisol_instance, $placelist, $in_path, $in_query);
                }
            } else {    // Otherwise, let's see what they want to do...
                switch ($main_command) {
                }
            }
        }
        
        return $this->_condition_response($in_response_type, $ret);
    }
}