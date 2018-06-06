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

define('__BASALT_VERSION__', '1.0.0.0000');

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

define('_SESSION_NAME_', '___RVP_BASALT_SESSION___');

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
        $paths = isset($_SERVER['PATH_INFO']) ? explode("/", substr(@$_SERVER['PATH_INFO'], 1)) : [];
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : NULL;
        $path_final = [];
        $vars_final = [];
        $this->_path = [];
        $this->_vars = [];
        $this->_response_type = NULL;
        $this->_plugin_selector = NULL;
        
        if (1 < count($paths)) { // We need at least the response and plugin types.
            $response_type = strtolower(trim($paths[0]));
            
            // Get the response type.
            if (('json' == $response_type) || ('xml' == $response_type) || ('xsd' == $response_type)) {
                array_shift($paths);
                
                $this->_response_type = $response_type;
                
                $plugin_selector = strtolower(trim($paths[0]));
                
                // Make sure that we are calling a valid plugin.
                if (in_array($plugin_selector, $this->get_plugin_names())) {
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
        $ret = NULL;
        
        if ('baseline' == $this->_plugin_selector) {
            $ret = $this->process_command($this->_andisol_instance, $this->_response_type, $this->_path, $this->_vars);
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
                    $ret = $plugin_instance->process_command($this->_andisol_instance, $this->_response_type, $this->_path, $this->_vars);
                } else {
                    header('HTTP/1.1 400 Unsupported or Missing Plugin');
                    exit();
                }
            } else {
                header('HTTP/1.1 400 Unsupported or Missing Plugin');
                exit();
            }
        }
        
        return $ret;
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
        array_unshift($ret['plugins'], 'baseline');
        
        return $ret;
    }
    
    /***********************/
    /**
    This runs our plugin name.
    
    \returns a string, with our plugin name.
     */
    public function plugin_name() {
        return 'baseline';
    }
    
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
            if ( !isset ( $_SESSION ) ) {
                if (!session_start()) {
                    $header = 'HTTP/1.1 400 Session Failure';
                    $result = '';
                }
            }
            
            $login_id_string = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL;
            $cleartext_password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : NULL;
            
            if ($login_id_string && $cleartext_password) {
                if (!CO_Config::$require_ssl_for_authentication || (CO_Config::$require_ssl_for_authentication && $https)) {
                    $this->_andisol_instance = new CO_Andisol($login_id_string, '', $cleartext_password);
                    // Assuming all went well, we set the session to our crypted password.
                    if ($this->_andisol_instance->logged_in()) {
                        $_SESSION[_SESSION_NAME_] = [];
                        $_SESSION[_SESSION_NAME_]['last_access_time'] = time();
                        $_SESSION[_SESSION_NAME_]['login_id_string'] = $this->_andisol_instance->get_chameleon_instance()->get_login_item()->login_id;
                        $_SESSION[_SESSION_NAME_]['hashed_password'] = $this->_andisol_instance->get_chameleon_instance()->get_login_item()->get_crypted_password();
echo('<pre>'.htmlspecialchars(print_r($_SESSION, true)).'</pre>');
                    } else {
                        header('HTTP/1.1 401 Unauthorized');
                        exit();
                    }
                } else {
                    header('HTTP/1.1 401 SSSL Authorization Required');
                    exit();
                }
            } else {
echo('<pre>'.htmlspecialchars(print_r($_SESSION, true)).'</pre>');
                // See if we have a saved session. If so, we extract the hashed credentials from there.
                if (isset($_SESSION[_SESSION_NAME_])) {
                    // Are we still open for business?
                    $now = time();
                    $then = isset($_SESSION[_SESSION_NAME_]['last_access_time']) ? intval($_SESSION[_SESSION_NAME_]['last_access_time']) : $now;
                    $time_elapsed = time() - intval($_SESSION[_SESSION_NAME_]['last_access_time']);
                    if (CO_Config::$session_timeout_in_seconds >= $time_elapsed) {
                        $login_id_string ='';
                        $hashed_password = '';
                
                        if (isset($_SESSION[_SESSION_NAME_]['hashed_password'])) {
                            $hashed_password = $_SESSION[_SESSION_NAME_]['hashed_password'];
                        }
            
                        if (isset($_SESSION[_SESSION_NAME_]['login_id_string'])) {
                            $login_id_string = $_SESSION[_SESSION_NAME_]['login_id_string'];
                        }
            
                        $this->_andisol_instance = new CO_Andisol($login_id_string, $hashed_password);
                        
                        $_SESSION[_SESSION_NAME_]['last_access_time'] = time();   // Give the flywheel another spin.
                    } else {
                        header('HTTP/1.1 408 Request Timeout: More Than '.CO_Config::$session_timeout_in_seconds.' Seconds Elapsed.');
                        exit();
                    }
                } else {    // If no session login, then we look for the standard HTTP login.
                    $this->_andisol_instance = new CO_Andisol();
                }
            }
        } else {
            header('HTTP/1.1 401 SSL Connection Required');
            exit();
        }
        
        // OK. By the time we get here, we are either logged in, or not logged in, and have a valid ANDISOL instance.
        // The next thing that we do, is decipher our marching orders for GET/POST/PUT/DELETE.
        $this->_request_type = strtoupper(trim($_SERVER['REQUEST_METHOD']));
        // Break up the request parameters.
        $this->_process_parameters();
        // OK. We're ready to go. Put on our shades. We're on a mission for Glod.
        $result = $this->_process_command();
        
        $header = 'Content-Type:';
        
        switch ($this->_response_type) {
            case 'xsd':
            case 'xml':
                $header .= 'text/xml';
                break;
                
            case 'json':
                $header .= 'application/json';
                break;
                
            default:
                $header = 'HTTP/1.1 400 Improper Return Type';
                $result = '';
        }
        
        header($header);
        echo($result);
        exit();
    }
    
    /***********************/
    /**
    \returns an array of strings, all lowercase, with the names of all the plugins used by BASALT.
     */
    public function get_plugin_names() {
        $ret = CO_Config::plugin_names();
        array_unshift($ret, 'baseline');
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
