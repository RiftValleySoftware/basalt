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

if ( !defined('LGV_LANG_CATCHER') ) {
    define('LGV_LANG_CATCHER', 1);
}

require_once(CO_Config::lang_class_dir().'/common.inc.php');

/****************************************************************************************************************************/
/**
 */
class CO_Basalt {
    protected   $_andisol_instance; ///< This contains the instance of ANDISOL used by this instance.
    protected   $_vars;             ///< This will contain any vars that are received via GET, PUT, POST or DELETE.
    protected   $_request_type;     /**< This will contain the HTTP Request Type, in uppercase.
                                            It will be one of:
                                                - 'GET'
                                                - 'POST'
                                                - 'PUT'
                                                - 'DELETE'
                                    */
    
    var         $version;           ///< The version indicator.
    var         $error;             ///< Any errors that occured are kept here.

    /************************************************************************************************************************/    
    /*#################################################### INTERNAL METHODS ################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
    This method goes through the passed-in REST query parameters and request paths, and sets up our local instance property with the decoded versions.
    At the end of this method, the internal $_vars property will be an array, containing path components.
    The final array component may (if provided) be the query (after the question mark), parsed by '&', and '='.
    If provided, the query array will be an associative array, with the key being the query element key, and the value being its value.
    If a query element is provided only as a key, then its value will be set to true.
     */
    protected function _process_parameters() {
        $vars = isset($_SERVER['PATH_INFO']) ? explode("/", substr(@$_SERVER['PATH_INFO'], 1)) : [];
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : NULL;
        $vars_final = [];
        
        // First, we simply look for the path components.
        foreach ($vars as $path_var) {
            $path_var = trim($path_var);
            
            if ($path_var) {
                $vars_final[] = $path_var;  // Simple push.
            }
        }
        
        $query = explode('&', $query);
        
        if (isset($query) && is_array($query) && count($query)) {
            $part_array = [];
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
                    $part_array[$key] = $value;
                }
            }
    
            // Add the parsed part to our accumulator.
            if (count($part_array)) {
                $vars_final[] = $part_array;  // Simple push.
            }
        }
        
        $this->_vars = $vars_final;
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
            if (session_start()) {
                $login_id_string ='';
                $cleartext_password = '';
                $hashed_password = '';
            
                // First thing we do, is see if we have a saved session. If so, we extract the hashed credentials from there.
                if (isset($_SESSION['RVP-BASALT']) && isset($_SESSION['RVP-BASALT']['last_access_time'])) {
                    // Are we still open for business?
                    if (CO_Config::$session_timeout_in_seconds < (time() - intval($_SESSION['RVP-BASALT']['last_access_time']))) {
                        $_SESSION['RVP-BASALT']['last_access_time'] = time();   // Give the flywheel another spin.
                
                        if (isset($_SESSION['RVP-BASALT']['hashed_password'])) {
                            $hashed_password = $_SESSION['RVP-BASALT']['hashed_password'];
                        }
            
                        if (isset($_SESSION['RVP-BASALT']['login_id_string'])) {
                            $login_id_string = $_SESSION['RVP-BASALT']['login_id_string'];
                        }
            
                        if ($login_id_string && $hashed_password) {
                            $this->_andisol_instance = new CO_Andisol($login_id_string, $hashed_password);
                        } elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && (!CO_Config::$require_ssl_for_authentication || (CO_Config::$require_ssl_for_authentication && $https))) {
                            $this->_andisol_instance = new CO_Andisol($_SERVER['PHP_AUTH_USER'], '', $_SERVER['PHP_AUTH_PW']);
                            // Assuming all went well, we set the session to our crypted password.
                            if ($this->_andisol_instance->logged_in()) {
                                $_SESSION['RVP-BASALT'] = [];
                                $_SESSION['RVP-BASALT']['last_access_time'] = time();
                                $_SESSION['RVP-BASALT']['login_id_string'] = $this->_andisol_instance->get_chameleon_instance()->get_login_item()->login_id;
                                $_SESSION['RVP-BASALT']['hashed_password'] = $this->_andisol_instance->get_chameleon_instance()->get_login_item()->get_crypted_password();
                            } else {
                                header('HTTP/1.1 401 Unauthorized');
                                exit();
                            }
                        } else {    // We don't have a login, so we simply create an open ANDISOL instance.
                            $this->_andisol_instance = new CO_Andisol();
                        }
                    } else {
                        header('HTTP/1.1 408 Request Timeout');
                        exit();
                    }
                } else {    // If no session login, then we look for the standard HTTP login.
                    $login_id_string = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL;
                    $cleartext_password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : NULL;
                    
                    if ($login_id_string && $cleartext_password) {
                        if (!CO_Config::$require_ssl_for_authentication || (CO_Config::$require_ssl_for_authentication && $https)) {
                            $this->_andisol_instance = new CO_Andisol($login_id_string, '', $cleartext_password);
                            // Assuming all went well, we set the session to our crypted password.
                            if ($this->_andisol_instance->logged_in()) {
                                $_SESSION['RVP-BASALT'] = [];
                                $_SESSION['RVP-BASALT']['last_access_time'] = time();
                                $_SESSION['RVP-BASALT']['login_id_string'] = $this->_andisol_instance->get_chameleon_instance()->get_login_item()->login_id;
                                $_SESSION['RVP-BASALT']['hashed_password'] = $this->_andisol_instance->get_chameleon_instance()->get_login_item()->get_crypted_password();
                            } else {
                                header('HTTP/1.1 401 Unauthorized');
                                exit();
                            }
                        } else {
                            header('HTTP/1.1 401 Unauthorized');
                            exit();
                        }
                    } else {    // We don't have a login, so we simply create an open ANDISOL instance.
                        $this->_andisol_instance = new CO_Andisol();
                        if (isset($_SESSION['RVP-BASALT'])) {
                            unset($_SESSION['RVP-BASALT']);
                        }
                    }
                }
            } else {    // Best guess at a response.
                header('HTTP/1.1 412 Precondition Failed');
                exit();
            }
        } else {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        
        // OK. By the time we get here, we are either logged in, or not logged in, and have a valid ANDISOL instance.
        // The next thing that we do, is decipher our marching orders for GET/POST/PUT/DELETE.
        $this->_request_type = strtoupper(trim($_SERVER['REQUEST_METHOD']));
        // Break up the request parameters.
        $this->_process_parameters();
        // OK. We're ready to go. Put on our shades. We're on a mission for Glod.
    }
    
    /***********************/
    /**
    \returns true, if we have an ANDISOL instance up and going.
     */
    public function valid() {
        return isset($this->_andisol_instance) ? $this->_andisol_instance->valid() : FALSE;
    }
    
    /***********************/
    /**
    \returns true, if the current user is successfully logged into the system.
     */
    public function logged_in() {
        return isset($this->_andisol_instance) ? $this->_andisol_instance->logged_in() : FALSE;
    }
};
