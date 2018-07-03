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
This is a basic REST plugin to handle storage and retrieval general data.
 */
class CO_things_Basalt_Plugin extends A_CO_Basalt_Plugin {
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
        return 'things';
    }
    
    /***********************/
    /**
    This returns an array of classnames, handled by this plugin.
    
    \returns an array of string, with the names of the classes handled by this plugin.
     */
    static public function classes_managed() {
        return ['CO_Collection', 'CO_KeyValue_CO_Collection'];
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
        
        if ('POST' == $in_http_method) {    // We handle POST directly.
            $ret = $this->_process_place_post($in_andisol_instance, $in_path, $in_query);
        } else {
            $show_parents = isset($in_query) && is_array($in_query) && isset($in_query['show_parents']);    // Show all places in detail, as well as the parents (applies only to GET).
            $show_details = $show_parents || (isset($in_query) && is_array($in_query) && isset($in_query['show_details']));    // Show all places in detail (applies only to GET).
            $writeable = isset($in_query) && is_array($in_query) && isset($in_query['writeable']);          // Show/list only places this user can modify.
            $search_count_only = isset($in_query) && is_array($in_query) && isset($in_query['search_count_only']);  // Ignored for discrete IDs. If true, then a simple "count" result is returned as an integer.
            $search_ids_only = isset($in_query) && is_array($in_query) && isset($in_query['search_ids_only']);      // Ignored for discrete IDs. If true, then the response will be an array of integers, denoting resource IDs.
            $search_page_size = isset($in_query) && is_array($in_query) && isset($in_query['search_page_size']) ? abs(intval($in_query['search_page_size'])) : 0;           // Ignored for discrete IDs. This is the size of a page of results (1-based result count. 0 is no page size).
            $search_page_number = isset($in_query) && is_array($in_query) && isset($in_query['search_page_number']) ? abs(intval($in_query['search_page_number'])) : 0;  // Ignored for discrete IDs, or if search_page_size is 0. The page we are interested in (0-based. 0 is the first page).
            
            // For the default (no thing ID), we simply act on a list of all available things (or filtered by some search criteria).
            if (0 == count($in_path)) {
                $radius = isset($in_query) && is_array($in_query) && isset($in_query['search_radius']) && (0.0 < floatval($in_query['search_radius'])) ? floatval($in_query['search_radius']) : NULL;
                $longitude = isset($in_query) && is_array($in_query) && isset($in_query['search_longitude']) ? floatval($in_query['search_longitude']) : NULL;
                $latitude = isset($in_query) && is_array($in_query) && isset($in_query['search_latitude']) ? floatval($in_query['search_latitude']) : NULL;
                $search_region_bias = isset($in_query) && is_array($in_query) && isset($in_query['search_region_bias']) ? strtolower(trim($search_region_bias)) : CO_Config::$default_region_bias;  // This is a region bias for an address lookup. Ignored if search_address is not specified.
            } else {
                $first_directory = $in_path[0];    // Get the first directory.
        
                // This tests to see if we only got one single digit as our "command."
                $single_thing_id = (ctype_digit($first_directory) && (1 < intval($first_directory))) ? intval($first_directory) : NULL;    // This will be for if we are looking only one single thing.
        
                // The first thing that we'll do, is look for a list of place IDs. If that is the case, we split them into an array of int.
        
                $thing_id_list = explode(',', $first_directory);
        
                // If we do, indeed, have a list, we will force them to be ints, and cycle through them.
                if ($single_thing_id || (1 < count($thing_id_list))) {
                    $thing_id_list = ($single_thing_id ? [$single_thing_id] : array_unique(array_map('intval', $thing_id_list)));
                    $thinglist = [];
                
                    foreach ($thinglist as $id) {
                        if (0 < $id) {
                            $thing = $in_andisol_instance->get_single_data_record_by_id($id);
                            if (isset($thing) && ($thing instanceof CO_Collection)) {
                                $thinglist[] = $thing;
                            }
                        }
                    }
                }
            }
        }
                
        return $this->_condition_response($in_response_type, $ret);
    }
}