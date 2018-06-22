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

define('__BASALT_VERSION__', '1.0.0.1002');

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
    protected function _process_parameters() {
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
                                    while ($data = fread($put_data, 1024)) {    // Read it in 1K chunks.
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
                if ('GET' == $this->_request_type) {
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
                    $header = 'HTTP/1.1 401 Unauthorized';
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
        echo($result);
        exit();
    }
    
    /***********************/
    /**
    This runs our baseline command.
    
    \returns the HTTP response intermediate state, as an associative array.
     */
    protected function _process_baseline_command(   $in_command,    ///< REQUIRED: The command to execute.
                                                    $in_query = []  ///< OPTIONAL: The query parameters, as an associative array.
                                                ) {
        $ret = Array('plugins' => CO_Config::plugin_names());
        array_unshift($ret['plugins'], $this->plugin_name());
        
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
        if (!CO_Config::$require_ssl_for_all || $https) {
            $this->_process_parameters();

            // If this is a login, we do nothing else. We simply handle the login.
            if ((1 == count($this->_path)) && ('login' == $this->_path[0])) {
                if (isset($this->_vars) && isset($this->_vars['login_id']) && isset($this->_vars['password'])) {
                    // We have the option (default on) of requiring TLS/SSL for logging in, so we check for that now.
                    if ($https || !CO_Config::$require_ssl_for_authentication) {
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
                    header('HTTP/1.1 401 SSL Connection Required');
                }
                
                exit();
            } elseif ((1 == count($this->_path)) && ('logout' == $this->_path[0]))  {   // See if the user wants to log out a session.
                $api_key1 = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL;
                $api_key2 = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : NULL;
                // If we don't have a valid API key pair, we scrag the process.
                if(!(isset($api_key1) && isset($api_key2) && $api_key1 && ($api_key1 == $api_key2))) {
                    header('HTTP/1.1 403 Unauthorized Login');
                } else {
                    $andisol_instance = new CO_Andisol('', '', '', $api_key1);
                
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
                
                exit();
            } else {
                $api_key1 = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL;
                $api_key2 = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : NULL;
            
                // If we don't have a valid API key pair, we just forget about API keys.
                if(!(isset($api_key1) && isset($api_key2) && $api_key1 && ($api_key1 == $api_key2))) {
                    $api_key1 = NULL;
                }
                $andisol_instance = new CO_Andisol('', '', '', $api_key1);
                
                if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
                    if (method_exists('CO_Config', 'call_log_handler_function')) {
                        CO_Config::call_log_handler_function($andisol_instance, $_SERVER);
                    }
                    $this->_andisol_instance = $andisol_instance;
                } else {
                    header('HTTP/1.1 500 Internal Server Error');
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
    \returns a string, with our plugin name.
     */
    public function plugin_name() {
        return _PLUGIN_NAME_;
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
    This runs our baseline command.
    
    \returns the HTTP response string, as either JSON or XML.
     */
    public function process_command(    $in_andisol_instance,   ///< REQUIRED: The ANDISOL instance to use as the connection to the RVP databases (ignored).
                                        $in_http_method,        ///< REQUIRED: 'GET', 'POST', 'PUT' or 'DELETE'
                                        $in_response_type,      ///< REQUIRED: 'json', 'xml' or 'xsd' -the response type.
                                        $in_path = [],          ///< OPTIONAL: The REST path, as an array of strings. For the baseline, this should be exactly one element.
                                        $in_query = []          ///< OPTIONAL: The query parameters, as an associative array.
                                    ) {
        $ret = NULL;
        
        if (is_array($in_path) && (1 >= count($in_path))) {
            $command = isset($in_path[0]) ? strtolower(trim($in_path[0])) : [];
            $ret = $this->_process_baseline_command($command, $in_query);
        } else {
            header('HTTP/1.1 400 Improper Baseline Command');
            exit();
        }
        
        return $this->_condition_response($in_response_type, $ret);
    }
};
