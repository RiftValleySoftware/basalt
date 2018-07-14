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

define('__BASALT_VERSION__', '1.0.0.2001');

if (!defined('LGV_ACCESS_CATCHER')) {
    define('LGV_ACCESS_CATCHER', 1);
}

if ( !defined('LGV_ANDISOL_CATCHER') ) {
    define('LGV_ANDISOL_CATCHER', 1);
}

require_once(CO_Config::andisol_main_class_dir().'/co_andisol.class.php');
require_once(CO_Config::main_class_dir().'/a_co_basalt_plugin.class.php');

if ( !defined('LGV_LANG_CATCHER') ) {
    define('LGV_LANG_CATCHER', 1);
}

require_once(CO_Config::lang_class_dir().'/common.inc.php');

define('_PLUGIN_NAME_', 'baseline');

/****************************************************************************************************************************/
/**
 */
class CO_Basalt extends A_CO_Basalt_Plugin {
    protected   $_andisol_instance; ///< This contains the instance of ANDISOL used by this instance.
    protected   $_path;             ///< This array will contain any path components that are received via GET, PUT, POST or DELETE.
    protected   $_vars;             ///< This associative array will contain any query variables that are received via GET, PUT, POST or DELETE.
    protected   $_request_type;     /**< This will contain the HTTP Request Type, in uppercase.
                                            It will be one of:
                                                - 'GET'
                                                - 'POST'
                                                - 'PUT'
                                                - 'DELETE'
                                    */
    protected   $_response_type;    ///< This is the reponse type. It is 'json', 'xml' or 'xsd'.
    protected   $_plugin_selector;  ///< This will be a lowercase string, denoting the plugin selected for the operation.
    
    var         $version;           ///< The version indicator.
    var         $error;             ///< Any errors that occured are kept here.

    /************************************************************************************************************************/    
    /*#################################################### INTERNAL METHODS ################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
    This method goes through the passed-in REST query parameters and request paths, and sets up our local instance property with the decoded versions.
    At the end of this method, the internal $_path property will be an array, containing path components, and, if provided, the $_vars property will have any query parameters.
    If provided, the query array will be an associative array, with the key being the query element key, and the value being its value.
    If a query element is provided only as a key, then its value will be set to true.
     */
    protected function _process_basalt_parameters() {
        $paths = isset($_SERVER['PATH_INFO']) ? explode("/", substr($_SERVER['PATH_INFO'], 1)) : [];
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : NULL;
        $path_final = [];
        $vars_final = [];
        $this->_path = [];
        $this->_vars = [];
        $this->_response_type = NULL;
        $this->_plugin_selector = NULL;
        
        $this->_request_type = strtoupper(trim($_SERVER['REQUEST_METHOD']));
        
        // Look to see if we are doing a login. In that case, we only grab a couple of things.
        if ((1 < count($paths)) || (isset($paths[0]) && (('login' == $paths[0]) || ('logout' == $paths[0])))) { // We need at least the response and plugin types. Login and Logout get special handling.
            $response_type = strtolower(trim($paths[0]));
            
            if ('login' == $response_type) {
                $query = explode('&', $query);
                $this->_path = Array('login');
                if (isset($query) && is_array($query) && (2 == count($query))) {
                    $vars_final = [];
                        
                    foreach ($query as $param) {
                        // Now, see if we have a bunch of parameters.
                        $key = trim($param);
                        $value = NULL;
        
                        $parts = explode('=', $param, 2);
                        if (1 < count($parts)) {
                            $key = trim($parts[0]);
                            $value = trim($parts[1]);
                        }
        
                        if ($key) {
                            if (!isset($value) || !$value) {
                                $value = true;
                            }
        
                            // remember that if we repeat the key, the first value is overwritten by the second (or third, or so on).
                            $vars_final[$key] = $value;
                        }
                    }
                    $this->_vars = $vars_final;
                } else {
                    header('HTTP/1.1 403 Unauthorized Login');
                    exit();
                }
            } elseif ('logout' == $response_type) { // We simply ignore anything else for logout.
                $this->_path = Array('logout');
            } else { // We handle the rest
                // Get the response type.
                if (('json' == $response_type) || ('xml' == $response_type) || ('xsd' == $response_type)) {
                    array_shift($paths);
                
                    $this->_response_type = $response_type;
                
                    $plugin_selector = strtolower(trim($paths[0]));
                
                    // Make sure that we are calling a valid plugin.
                    if (in_array($plugin_selector, $this->get_plugin_names())) {
                        $vars_final = [];
                        
                        $this->_plugin_selector = $plugin_selector;
                
                        array_shift($paths);
                
                        // We now trim the strings in the remaining paths, and make sure that we don't have any empties.
                        $this->_path = array_filter(array_map('trim', $paths), function($i){return '' != $i;});
        
                        // Next, we examine any query parameters.
                        $query = explode('&', $query);
        
                        if (isset($query) && is_array($query) && count($query)) {
                            foreach ($query as $param) {
                                // Now, see if we have a bunch of parameters.
                                $key = trim($param);
                                $value = NULL;
                
                                $parts = explode('=', $param, 2);
                                if (1 < count($parts)) {
                                    $key = trim($parts[0]);
                                    $value = urldecode(trim($parts[1]));
                                }
                
                                if ($key) {
                                    if (!isset($value)) {
                                        $value = true;
                                    }
                
                                    // remember that if we repeat the key, the first value is overwritten by the second (or third, or so on).
                                    $vars_final[$key] = $value;
                                }
                            }
                        }

                        $file_data = '';
                        
                        if (!isset($vars_final['remove_payload'])) { // If they did not specify a payload, maybe they want one removed?
                            // POST is handled differently from PUT. POST gets proper background handling, while PUT needs a very raw approach.
                            if ('POST' == $this->_request_type) {
                                if (isset($_FILES['payload']) && (!isset($_FILES['payload']['error']) || is_array($_FILES['payload']['error']))) {
                                    header('HTTP/1.1 400 '.print_r($_FILES['payload']['error'], true));
                                    exit();
                                } elseif (isset($_FILES['payload'])) {
                                    $file_data = file_get_contents($_FILES['payload']['tmp_name']);
                                }
                            } elseif ('PUT' == $this->_request_type) {
                                // See if they have sent any data to us via the standard HTTP channel (PUT).
                                $put_data = fopen('php://input', 'r');
                                if (isset($put_data) && $put_data) {
                                    while ($data = fread($put_data, 2048)) {    // Read it in 2K chunks.
                                        $file_data .= $data;
                                    }
                                    fclose($put_data);
                                }
                            }
                        
                            // This can only go to payload.
                            if (isset($file_data) && $file_data) {
                                $vars_final['payload'] = base64_decode($file_data);
                            }
                        }
                        
                        $this->_vars = $vars_final;
                    } else {
                        header('HTTP/1.1 400 Unsupported or Missing Plugin');
                        exit();
                    }
                } else {
                    header('HTTP/1.1 400 Improper Return Type');
                    exit();
                }
            }
        } else {
            header('HTTP/1.1 400 Missing Path Components');
            exit();
        }
    }
    
    /***********************/
    /**
    This runs our command.
    
    \returns the HTTP response string.
     */
    protected function _process_command() {
        $header = '';
        $result = '';
        
        if (isset($this->_andisol_instance) && ($this->_andisol_instance instanceof CO_Andisol) && $this->_andisol_instance->valid()) {
            if ('baseline' == $this->_plugin_selector) {
                if (('GET' == $this->_request_type) || ('POST' == $this->_request_type)) {
                    $result = $this->process_command($this->_andisol_instance, $this->_request_type, $this->_response_type, $this->_path, $this->_vars);
                } else {
                    $header = 'HTTP/1.1 400 Incorrect HTTP Request Method';
                    exit();
                }
            } else {
                $plugin_filename = 'co_'.$this->_plugin_selector.'_basalt_plugin.class.php';
                $plugin_classname = 'CO_'.$this->_plugin_selector.'_Basalt_Plugin';
                $plugin_dirs = CO_Config::plugin_dirs();
                $plugin_file = '';
            
                foreach ($plugin_dirs as $plugin_dir) {
                    if (isset($plugin_dir) && is_dir($plugin_dir)) {
                        // Iterate through that directory, and get each plugin directory.
                        foreach (new DirectoryIterator($plugin_dir) as $fileInfo) {
                            if ($plugin_filename == $fileInfo->getBasename()) {
                                $plugin_file = $fileInfo->getPathname();
                                break;
                            }
                        }
                    }
                }
            
                if ($plugin_file) {
                    require_once($plugin_file);
                    $plugin_instance = new $plugin_classname();
                    if ($plugin_instance instanceof A_CO_Basalt_Plugin) {
                        $result = $plugin_instance->process_command($this->_andisol_instance, $this->_request_type, $this->_response_type, $this->_path, $this->_vars);
                    } else {
                        header('HTTP/1.1 400 Unsupported or Missing Plugin');
                        exit();
                    }
                } else {
                    header('HTTP/1.1 400 Unsupported or Missing Plugin');
                    exit();
                }
            }
        
            switch ($this->_response_type) {
                case 'xsd':
                    $header .= 'Content-Type: text/xml';
                    break;

                case 'xml':
                    $header .= 'Content-Type: text/xml';
                    break;
                
                case 'json':
                    $header .= 'Content-Type: application/json';
                    break;
                
                default:
                    $header = 'HTTP/1.1 400 Improper Return Type';
                    $result = '';
            }
        } else {
            if (isset($this->_andisol_instance) && ($this->_andisol_instance instanceof CO_Andisol)) {
                $this->error = $this->_andisol_instance->error;
                if (isset($this->error) && ($this->error->error_code == CO_Lang_Common::$login_error_code_api_key_mismatch) || ($this->error->error_code == CO_Lang_Common::$pdo_error_code_invalid_login)) {
                    $header = 'HTTP/1.1 401 Unauthorized API Key';
                } elseif (isset($this->error) && ($this->error->error_code == CO_Lang_Common::$login_error_code_api_key_invalid)) {
                    $header = 'HTTP/1.1 408 API Key Timeout';
                } else {
                    $header = 'HTTP/1.1 400 General Error';
                }
            } else {
                $header = 'HTTP/1.1 400 General Error';
            }
        }
        
        if ($header) {
            header($header);
        }
        
        $handler = null;
        
        if ( zlib_get_coding_type() === false )
            {
            $handler = "ob_gzhandler";
            }
        
        ob_start($handler);
        echo($result);
		ob_end_flush();
        exit();
    }
    
    /***********************/
    /**
    This runs our baseline token command.
    
    \returns the HTTP response intermediate state, as an associative array.
     */
    protected function _process_token_command(  $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases (ignored).
                                                $in_http_method,        ///< REQUIRED: 'GET' or 'POST' are the only allowed values.
                                                $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings. For the baseline, this should be exactly one element.
                                                $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                                ) {
        $ret = NULL;
        if ($in_andisol_instance->logged_in()) {    // We also have to be logged in to have any access to tokens.
            if (('GET' == $in_http_method) && (!isset($in_path) || !is_array($in_path) || !count($in_path))) {   // Do we just want a list of our tokens?
                $ret = ['tokens' => $in_andisol_instance->get_chameleon_instance()->get_available_tokens()];
            } elseif (('POST' == $in_http_method) && $in_andisol_instance->manager()) {  // If we are handling POST, then we ignore everything else, and create a new token. However, we need to be a manager to do this.
                $ret = ['tokens' => [$in_andisol_instance->make_security_token()]];
            } else {
                header('HTTP/1.1 403 Unauthorized Command');
                exit();
            }
        } else {
            header('HTTP/1.1 403 Unauthorized Command');
            exit();
        }
        return $ret;
    }
    
    /***********************/
    /**
    This runs our baseline serverinfo command.
    
    \returns the HTTP response intermediate state, as an associative array.
     */
    protected function _process_serverinfo_command(  $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases (ignored).
                                                $in_http_method,        ///< REQUIRED: 'GET' or 'POST' are the only allowed values.
                                                $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings. For the baseline, this should be exactly one element.
                                                $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                                ) {
        $ret = NULL;
        if ($in_andisol_instance->god()) {    // We also have to be logged in as God to have any access to serverinfo.
            $ret = ['serverinfo' => []];
            $ret['serverinfo']['basalt_version'] = __BASALT_VERSION__;
            $ret['serverinfo']['andisol_version'] = __ANDISOL_VERSION__;
            $ret['serverinfo']['cobra_version'] = __COBRA_VERSION__;
            $ret['serverinfo']['chameleon_version'] = __CHAMELEON_VERSION__;
            $ret['serverinfo']['badger_version'] = __BADGER_VERSION__;
            $ret['serverinfo']['security_db_type'] = CO_Config::$sec_db_type;
            $ret['serverinfo']['data_db_type'] = CO_Config::$data_db_type;
            $ret['serverinfo']['lang'] = CO_Config::$lang;
            $ret['serverinfo']['min_pw_length'] = intval(CO_Config::$min_pw_len);
            $ret['serverinfo']['regular_timeout_in_seconds'] = intval(CO_Config::$session_timeout_in_seconds);
            $ret['serverinfo']['god_timeout_in_seconds'] = intval(CO_Config::$god_session_timeout_in_seconds);
            $ret['serverinfo']['block_repeated_logins'] = CO_Config::$block_logins_for_valid_api_key ? true : false;
            $ret['serverinfo']['block_differing_ip_address'] = CO_Config::$api_key_includes_ip_address ? true : false;
            $ret['serverinfo']['ssl_requirement_level'] = intval(CO_Config::$ssl_requirement_level);
            $ret['serverinfo']['google_api_key'] = CO_Config::$google_api_key;
            $ret['serverinfo']['allow_address_lookup'] = CO_Config::$allow_address_lookup ? true : false;
            $ret['serverinfo']['allow_general_address_lookup'] = CO_Config::$allow_general_address_lookup ? true : false;
            $ret['serverinfo']['default_region_bias'] = CO_Config::$default_region_bias;
        } else {
            header('HTTP/1.1 403 Unauthorized Command');
            exit();
        }
        return $ret;
    }
    
    /***********************/
    /**
    This runs our baseline command.
    
    \returns the HTTP response intermediate state, as an associative array.
     */
    protected function _process_baseline_command(   $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases (ignored).
                                                    $in_http_method,        ///< REQUIRED: 'GET' or 'POST' are the only allowed values.
                                                    $in_command,            ///< REQUIRED: The command to execute.
                                                    $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings. For the baseline, this should be exactly one element.
                                                    $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                                ) {
        $ret = [];
        
        // No command simply means list the plugins.
        if (('GET' == $in_http_method) && (!isset($in_command) || !$in_command)) {
            $ret = Array('plugins' => CO_Config::plugin_names());
            array_unshift($ret['plugins'], $this->plugin_name());
        } elseif ('tokens' == $in_command) {   // If we are viewing or editing the tokens, then we deal with that here.
            $ret = $this->_process_token_command($in_andisol_instance, $in_http_method, $in_path, $in_query);
        } elseif (('serverinfo' == $in_command) && $in_andisol_instance->god()) {   // God can ask for information about the server.
            $ret = $this->_process_serverinfo_command($in_andisol_instance, $in_http_method, $in_path, $in_query);
        } elseif ('search' == $in_command) {
            // For a location search, all three of these need to be specified, and radius needs to be nonzero.
            $radius = isset($in_query) && is_array($in_query) && isset($in_query['search_radius']) && (0.0 < floatval($in_query['search_radius'])) ? floatval($in_query['search_radius']) : NULL;
            $longitude = isset($in_query) && is_array($in_query) && isset($in_query['search_longitude']) ? floatval($in_query['search_longitude']) : NULL;
            $latitude = isset($in_query) && is_array($in_query) && isset($in_query['search_latitude']) ? floatval($in_query['search_latitude']) : NULL;
            
            $search_page_size = isset($in_query) && is_array($in_query) && isset($in_query['search_page_size']) ? abs(intval($in_query['search_page_size'])) : 0;       // This is the size of a page of results (1-based result count. 0 is no page size).
            $search_page_number = isset($in_query) && is_array($in_query) && isset($in_query['search_page_number']) ? abs(intval($in_query['search_page_number'])) : 0; // Ignored if search_page_size is 0. The page we are interested in (0-based. 0 is the first page).
            $writeable = isset($in_query) && is_array($in_query) && isset($in_query['writeable']);                                                                      // Show/list only things this user can modify.
            $search_name = isset($in_query) && is_array($in_query) && isset($in_query['search_name']) ? trim($in_query['search_name']) : NULL;                          // Search in the object name.
            $tags = [];
            
            // Search for specific tag values.
            for ($tag = 0; $tag < 10; $tag++) {
                $tag_string = 'search_tag'.$tag;
                $tag_value = isset($in_query) && is_array($in_query) && isset($in_query[$tag_string]) ? trim($in_query[$tag_string]) : '%';
                $tags[] = $tag_value;
            }
            
            $search_array = [];
            
            if (isset($radius) && (0 < $radius) && isset($longitude) && isset($latitude)) {
                $location_search = Array('radius' => $radius, 'longitude' => $longitude, 'latitude' => $latitude);
                $search_array['location'] = $location_search;
            }
            
            if (isset($search_name)) {
                $search_array['name'] = Array($search_name, 'use_like' => 1);
            }
            
            // If there were any specified tags, we search by tag. Otherwise, we don't bother.
            if (array_reduce($tags, function($prev, $current) { return $prev || ('%' != $current); }, false)) {
                $search_array['tags'] = $tags;
                $search_array['tags']['use_like'] = 1;
            }
            
            $object_list = $in_andisol_instance->generic_search($search_array, false, $search_page_size, $search_page_number, $writeable);
        
            if (isset($object_list) && is_array($object_list) && count($object_list)) {
                foreach ($object_list as $instance) {
                    $class_name = get_class($instance);
        
                    if ($class_name) {
                        $handler = self::_get_handler($class_name);
                        $ret[$handler][] = $instance->id();
                    }
                }
            }
        
            if (isset($location_search)) {
                $ret['search_location'] = $location_search;
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This returns the schema for this plugin as XML XSD.
    
    \returns XML, containing the schema for this plugin's responses. The schema needs to be comprehensive.
     */
    protected function _get_xsd() {
        return $this->_process_xsd(dirname(__FILE__).'/schema.xsd');
    }

    /************************************************************************************************************************/    
    /*##################################################### PUBLIC METHODS #################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
    Constructor
     */
    public function __construct() {
        $this->version = __BASALT_VERSION__;
        $this->error = NULL;
        $this->_andisol_instance = NULL;
        // IIS puts "off" in the HTTPS field, so we need to test for that.
        $https = ((!empty ( $_SERVER['HTTPS'] ) && (($_SERVER['HTTPS'] !== 'off') || ($port == 443)))) ? true : false;
        if ((CO_CONFIG_HTTPS_ALL > CO_Config::$ssl_requirement_level) || $https) {
            $this->_process_basalt_parameters();

            // If this is a login, we do nothing else. We simply handle the login.
            if ((1 == count($this->_path)) && ('login' == $this->_path[0])) {
                if (isset($this->_vars) && isset($this->_vars['login_id']) && isset($this->_vars['password'])) {
                    // We have the option (default on) of requiring TLS/SSL for logging in, so we check for that now.
                    if ($https || (CO_CONFIG_HTTPS_OFF == CO_Config::$ssl_requirement_level)) {
                        $login_id = $this->_vars['login_id'];
                        $password = $this->_vars['password'];
                        
                        // See if we have our validator in place.
                        if (method_exists('CO_Config', 'call_login_validator_function')) {
                            if (!CO_Config::call_login_validator_function($login_id, $password, $_SERVER)) {
                                header('HTTP/1.1 403 Unauthorized Login');
                                exit();
                            }
                        }
                        
                        // We do a simple login. This will also generate an API key, which is the only response to this command.
                        $andisol_instance = new CO_Andisol($login_id, '', $password);
                    
                        if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol) && $andisol_instance->logged_in()) {
                            if (method_exists('CO_Config', 'call_log_handler_function')) {
                                CO_Config::call_log_handler_function($andisol_instance, $_SERVER);
                            }
                            $login_item = $andisol_instance->get_login_item();
                        
                            // If we are logging in, we shortcut the process, and simply return the API key.
                            if (isset($login_item) && ($login_item instanceof CO_Security_Login)) {
                                $api_key = $login_item->get_api_key();
                                // From now on, in order to access the login resources, you'll need to include the API key in the username/password fields.
                                if (isset($api_key)) {
                                    echo($api_key);
                                } else {
                                    header('HTTP/1.1 403 Unauthorized Login');
                                }
                            } else {
                                header('HTTP/1.1 403 Unauthorized Login');
                            }
                        } else {
                            header('HTTP/1.1 403 Unauthorized Login');
                        }
                    } else {
                        header('HTTP/1.1 401 SSL Connection Required');
                    }
                } else {
                    header('HTTP/1.1 401 Credentials Required');
                }
                
                exit();
            } elseif ((1 == count($this->_path)) && ('logout' == $this->_path[0]))  {   // See if the user wants to log out a session.
                $server_secret = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL;
                $api_key = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : NULL;
                
                // See if an SSL connection is required.
                if ($https || (CO_CONFIG_HTTPS_LOGGED_IN_ONLY > CO_Config::$ssl_requirement_level)) {
                    // If we don't have a valid API key/Server Secret pair, we scrag the process.
                    if(!(isset($api_key) && $api_key && ($server_secret == Co_Config::server_secret()))) {
                        header('HTTP/1.1 403 Cannot Logout Without Valid Credentials');
                    } else {
                        $andisol_instance = new CO_Andisol('', '', '', $api_key);
                
                        if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol) && $andisol_instance->logged_in()) {
                            if (method_exists('CO_Config', 'call_log_handler_function')) {
                                CO_Config::call_log_handler_function($andisol_instance, $_SERVER);
                            }
                            
                            $login_item = $andisol_instance->get_login_item();
                    
                            // We "log out" by clearing the API key.
                            if (isset($login_item) && ($login_item instanceof CO_Security_Login)) {
                                if ($login_item->clear_api_key()) {
                                    header('HTTP/1.1 205 Logout Successful');
                                } else {    // This will probably never happen, but belt and suspenders...
                                    header('HTTP/1.1 200 Logout Unneccessary');
                                }
                            } else {    // This will probably never happen, but belt and suspenders...
                                header('HTTP/1.1 500 Internal Server Error');
                            }
                        } else {
                            header('HTTP/1.1 403 Unauthorized Login');
                        }
                    }
                } else {
                    header('HTTP/1.1 401 SSL Connection Required');
                }
                
                exit();
            } else {    // Handle the rest of the requests here.
                // Look for authentication.
                $server_secret = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL;   // Supplied to the client by the Server Admin.
                $api_key = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : NULL;             // Generated by the server for this session.
            
                // If we don't have a valid API key/Server Secret pair, we just forget about API keys.
                if(!(isset($api_key) && $api_key && ($server_secret == Co_Config::server_secret()))) {
                    $api_key = NULL;
                }
                
                $https_requirement = true;
                
                // Make sure we are HTTPS, or SSL is not required.
                switch (CO_Config::$ssl_requirement_level) {
                    case CO_CONFIG_HTTPS_ALL:
                        // Yeah, it's required.
                        break;
                    
                    case CO_CONFIG_HTTPS_LOGGED_IN_ONLY:
                        // Only if we have an authentication header.
                        $https_requirement = (NULL != $api_key);
                        break;
                        
                    default:
                        // Not necessary if we are login only or off.
                        $https_requirement = false;
                        break;
                }
                
                if ($https || !$https_requirement) {
                    $andisol_instance = new CO_Andisol('', '', '', $api_key);
                
                    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
                        if (method_exists('CO_Config', 'call_log_handler_function')) {
                            CO_Config::call_log_handler_function($andisol_instance, $_SERVER);
                        }
                        $this->_andisol_instance = $andisol_instance;
                    } else {
                        header('HTTP/1.1 500 Internal Server Error');
                        exit();
                    }
                } else {
                    header('HTTP/1.1 401 SSL Connection Required');
                    exit();
                }
            }
        } else {
            header('HTTP/1.1 401 SSL Connection Required');
            exit();
        }
        
        // OK. By the time we get here, we are either logged in, or not logged in, and have a valid ANDISOL instance. We're ready to go. Put on our shades. We're on a mission for Glod.
        $this->_process_command();
    }
    
    /***********************/
    /**
    \returns an array of strings, all lowercase, with the names of all the plugins used by BASALT.
     */
    public function get_plugin_names() {
        $ret = CO_Config::plugin_names();
        array_unshift($ret, $this->plugin_name());
        return $ret;
    }
    
    /***********************/
    /**
    \returns true, if we have an ANDISOL instance up and going.
     */
    public function valid() {
        return isset($this->_andisol_instance) ? $this->_andisol_instance->valid() : false;
    }
    
    /***********************/
    /**
    \returns true, if the current user is successfully logged into the system.
     */
    public function logged_in() {
        return isset($this->_andisol_instance) ? $this->_andisol_instance->logged_in() : false;
    }
        
    /***********************/
    /**
    \returns a string, with our plugin name.
     */
    public function plugin_name() {
        return _PLUGIN_NAME_;
    }
    
    /***********************/
    /**
    This returns an array of classnames, handled by this plugin.
    
    \returns an array of string, with the names of the classes handled by this plugin.
     */
    static public function classes_managed() {
        return [];
    }
    
    /***********************/
    /**
    This runs our baseline command.
    
    \returns the HTTP response string, as either JSON or XML.
     */
    public function process_command(    $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases (ignored).
                                        $in_http_method,        ///< REQUIRED: 'GET' or 'POST' are the only allowed values.
                                        $in_response_type,      ///< REQUIRED: 'json', 'xml' or 'xsd' -the response type.
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings. For the baseline, this should be exactly one element.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = NULL;
        
        if (is_array($in_path) && (1 >= count($in_path))) {
            $command = isset($in_path[0]) ? strtolower(trim(array_shift($in_path))) : [];
            $ret = $this->_process_baseline_command($in_andisol_instance, $in_http_method, $command, $in_path, $in_query);
        } else {
            header('HTTP/1.1 400 Improper Baseline Command');
            exit();
        }
        
        return $this->_condition_response($in_response_type, $ret);
    }
};
