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
 */
abstract class A_CO_Basalt_Plugin {
    /***********************/
    /**
    \returns the server base URI, including any custom port and/or SSL prefix.
    */
    protected static function _server_url() {
        $port = intval ( $_SERVER['SERVER_PORT'] );
    
        // IIS puts "off" in the HTTPS field, so we need to test for that.
        $https = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'] && (($_SERVER['HTTPS'] !== 'off') || ($port == 443)))) ? true : false;
    
        $url_path = $_SERVER['SERVER_NAME'];
    
        // See if we need to add an explicit port to the URI.
        if (!$https && ($port != 80)) {
            $url_path .= ":$port";
        } elseif ($https && ($port != 443)) {
            $url_path .= ":$port";
        }
        
        return 'http'.($https ? 's' : '').'://'.$url_path.$_SERVER['SCRIPT_NAME'];
    }

    /***********************/
    /**
    \returns the input array, converted to XML.
     */
    protected static function _array2xml(	$in_array   ///< REQUIRED: The input associative array
                                        ) {
        $output = '';
        $index = (1 < count($in_array)) ? 0 : NULL;
        
        foreach ($in_array as $name => $value) {
            $name = is_int($name) ? 'value' : htmlspecialchars(trim($name));
                
            if ($value) {
                if (NULL !== $index) {
                    $output .= '<'.$name.' sequence_index="'.strval ( $index++ ).'">';
                } else {
                    $output .= '<'.$name.'>';
                }
                
                if (is_array($value)) {
                    $output .= self::_array2xml($value);
                } else {
                    $output .= htmlspecialchars(strval($value));
                }
                
                $output .= '</'.htmlspecialchars($name).'>';
            }
        }
    
        return $output;
    }
    
    /***********************/
    /**
    This returns the appropriate XML header for our response.
    
    \returns a string, with the entire XML header (including the preamble).
     */
    protected function _get_xml_header() {
        $ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xsd_uri = self::_server_url().'/xsd/'.$this->plugin_name();
        $ret .= '<'.$this->plugin_name()." xsi:schemaLocation=\"".self::_server_url()." $xsd_uri\" xmlns=\"".self::_server_url()."\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">";
        
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
    abstract public function plugin_name();
    
    /***********************/
    /**
    This runs our plugin command.
    
    \returns the HTTP response string, as either JSON or XML.
     */
    abstract public function process_command(   $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases.
                                                $in_response_type,      ///< REQUIRED: 'json', 'xml' or 'xsd' -the response type.
                                                $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings. For the baseline, this should be exactly one element.
                                                $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                            );
    
    /***********************/
    /**
    This conditions our response.
    
    \returns the HTTP response string, as either JSON or XML.
     */
    protected function _condition_response( $in_response_type,                          ///< REQUIRED: 'json', 'xml' or 'xsd' -the response type.
                                            $in_response_as_associative_array = NULL    ///< OPTIONAL (but required fon non-XSD): The response to be converted to JSON or XML, as an associative array. If XSD, then this can be an empty array.
                                            ) {
        $ret = '';
        
        if ('xml' == $in_response_type) {
            $ret = $this->_get_xml_header().self::_array2xml($in_response_as_associative_array).'</'.$this->plugin_name().'>';
        } elseif ('xsd' == $in_response_type) {
            $ret = $this->_get_xsd();
        } else {
            $ret = json_encode(Array($this->plugin_name() => $in_response_as_associative_array));
        }
        
        return $ret;
    }
}