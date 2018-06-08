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
class CO_places_Basalt_Plugin extends A_CO_Basalt_Plugin {
    /***********************/
    /**
     */
    protected function _get_short_place_description($in_place_object) {
        $ret = Array('id' => $in_place_object->id(), 'name' => $in_place_object->name);
        
        return $ret;
    }

    /***********************/
    /**
     */
    protected function _get_long_place_description($in_place_object) {
        $longitude = $in_place_object->longitude();
        $latitude = $in_place_object->latitude();
        $address = $in_place_object->get_readable_address();
        $name = $in_place_object->name;
        
        $ret = Array('id' => $in_place_object->id());
        
        if (isset($name) && $name) {
            $ret['name'] = $name;
        }
        
        if (isset($address) && $address) {
            $ret['address'] = $address;
        }
        
        if (isset($longitude) && is_float($longitude) && isset($latitude) && is_float($latitude)) {
            $ret['lat_lng'] = sprintf("%f,%f", $latitude, $longitude);
        }

        return $ret;
    }
    
    /***********************/
    /**
    This returns the schema for this plugin as XML XSD.
    
    \returns XML, containing the schema for this plugin's responses. The schema needs to be comprehensive.
     */
    protected function _get_xsd() {
        $ret = '';
        
        $replacement_token = '%%%%%SERVER#URI%%%%%';
        
        $schema_file_path = dirname(__FILE__).'/schema.xsd';
        
        $schema_file = file_get_contents($schema_file_path);
        
        if ($schema_file) {
            $ret = str_replace($replacement_token, self::_server_url(), $schema_file);
        }
        
        return $ret;
    }
        
    /***********************/
    /**
    This runs our plugin name.
    
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
                                        $in_response_type,      ///< REQUIRED: Either 'json' or 'xml' -the response type.
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = [];
        
        // For the default (no place ID), we simply return a list of places, in "short" format.
        if (0 == count($in_path)) {
            $placelist = $in_andisol_instance->generic_search(Array('access_class' => Array('%_Place', '%_Place_Collection', 'use_like' => 1)));
            
            if (isset($placelist) && is_array($placelist) && (0 < count($placelist))) {
                foreach ($placelist as $place) {
                    $ret[] = $this->_get_short_place_description($place);
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
                            $ret[] = $this->_get_long_place_description($place);
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