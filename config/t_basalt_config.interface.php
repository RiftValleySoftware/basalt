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
require_once(dirname(dirname(__FILE__)).'/andisol/cobra/chameleon/badger/install-assets/t_config.interface.php');

trait tCO_Basalt_Config {
    use tCO_Config; // These are the built-in config methods.
    
    /***********************/
    /**
    \returns an array of strings, with each being the absolute POSIX path to a plugin directory.
     */
    static function plugin_dirs() {
        $baseline_plugins = dirname(dirname(__FILE__)).'/plugins';  // We at least have the built-in plugins.
        
        $ret = Array();
        
        // First, scan the baseline directory.
        // Iterate through that directory, and get each plugin directory.
        foreach (new DirectoryIterator($baseline_plugins) as $fileInfo) {
            if (!$fileInfo->isDot() && $fileInfo->isDir()) {
                $ret[] = strtolower(trim($fileInfo->getPathname()));
            }
        }
        
        // Next, see if we have any user-defined plugin directories.
        $plugin_dir = CO_Config::extension_dir();
        
        if (isset($plugin_dir) && is_dir($plugin_dir)) {
            // Iterate through that directory, and get each plugin directory.
            foreach (new DirectoryIterator($plugin_dir) as $fileInfo) {
                if (!$fileInfo->isDot() && $fileInfo->isDir()) {
                    $ret[] = strtolower(trim($fileInfo->getPathname()));
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of strings, with each being the absolute POSIX path to a plugin directory.
     */
    static function plugin_names() {
        $plugin_dirs = self::plugin_dirs();
        
        return array_map(function($i){ return strtolower(trim(basename($i))); }, $plugin_dirs);
    }
    
    /***********************/
    /**
    \returns either the POSIX path to the ANDISOL Extension dir, or an array, which will include any extensions defined by plugins.
     */
    static function db_classes_extension_class_dir() {
        $ret = self::andisol_db_classes_extension_class_dir();
        
        $plugin_dirs = self::plugin_dirs();
        
        // Next, we check our plugin directories for extension classes.
        if (isset($plugin_dirs) && is_array($plugin_dirs) && count($plugin_dirs)) {
            $temp_array = is_array($ret) ? $ret : Array($ret);  // Start with whatever we already have.
            
            foreach ($plugin_dirs as $dir) {
                $extension_dir = "$dir/badger_extension_classes";
                
                if (file_exists($extension_dir) && is_dir($extension_dir)) {
                    $temp_array[] = $extension_dir;
                }
            }
            
            // If we only have one, then we don't need to return an array.
            if (1 == count($temp_array)) {
                $ret = $temp_array[0];
            } else {
                $ret = $temp_array;
            }
        }
        
        return $ret;
    }
}
