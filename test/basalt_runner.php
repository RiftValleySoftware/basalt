<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC/The Great Rift Valley Software Company
    
    LICENSE:
    

    Little Green Viper Software Development: https://littlegreenviper.com
*/

$config_file_path = dirname(__FILE__).'/config/s_config.class.php';

date_default_timezone_set ( 'UTC' );

define('LGV_CONFIG_CATCHER', 1);

require_once($config_file_path);
require_once(dirname(dirname(__FILE__)).'/entrypoint.php');
