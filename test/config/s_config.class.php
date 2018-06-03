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
defined( 'LGV_CONFIG_CATCHER' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.

/***************************************************************************************************************************/
/**
This file contains the implementation-dependent configuration settings.
 */
define('_MAIN_DB_TYPE_', 'mysql');
define('_SECURITY_DB_TYPE_', 'mysql');

require_once(dirname(dirname(dirname(__FILE__))).'/cobra/chameleon/badger/install-assets/t_config.interface.php');

class CO_Config {
    use tCO_Config; // These are the built-in config methods.

    static private $_god_mode_id = 2;   ///< God Login Security DB ID. This is private, so it can't be programmatically changed.
    static private $_god_mode_password = 'BWU-HA-HAAAA-HA!'; ///< Plaintext password for the God Mode ID login. This overrides anything in the ID row.
    
    static $lang = 'en';                ///< The default language for the server.
    static $min_pw_len = 8;             ///< The minimum password length.
    
    static $data_db_name = 'littlegr_badger_data';
    static $data_db_host = 'localhost';
    static $data_db_type = _MAIN_DB_TYPE_;
    static $data_db_login = 'littlegr_badg';
    static $data_db_password = 'pnpbxI1aU0L(';

    static $sec_db_name = 'littlegr_badger_security';
    static $sec_db_host = 'localhost';
    static $sec_db_type = _SECURITY_DB_TYPE_;
    static $sec_db_login = 'littlegr_badg';
    static $sec_db_password = 'pnpbxI1aU0L(';

    /**
    This is the Google API key. It's required for CHAMELEON to do address lookups and other geocoding tasks.
    CHAMELEON requires this to have at least the Google Geocoding API enabled.
    */
    static $google_api_key = 'AIzaSyAPCtPBLI24J6qSpkpjngXAJtp8bhzKzK8';
    
    /***********************/
    /**
    \returns the POSIX path to the main ANDISOL directory.
     */
    static function base_dir() {
        return dirname(dirname(dirname(__FILE__)));
    }
    
}
