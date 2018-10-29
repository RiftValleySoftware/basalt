<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC/The Great Rift Valley Software Company
    
    LICENSE:
    
    FOR OPEN-SOURCE (COMMERCIAL OR FREE):
    This code is released as open source under the GNU Plublic License (GPL), Version 3.
    You may use, modify or republish this code, as long as you do so under the terms of the GPL, which requires that you also
    publish all modificanions, derivative products and license notices, along with this code.
    
    UNDER SPECIAL LICENSE, DIRECTLY FROM LITTLE GREEN VIPER OR THE GREAT RIFT VALLEY SOFTWARE COMPANY:
    It is NOT to be reused or combined into any application, nor is it to be redistributed, republished or sublicensed,
    unless done so, specifically WITH SPECIFIC, WRITTEN PERMISSION from Little Green Viper Software Development LLC,
    or The Great Rift Valley Software Company.

    Little Green Viper Software Development: https://littlegreenviper.com
    The Great Rift Valley Software Company: https://riftvalleysoftware.com

    Little Green Viper Software Development: https://littlegreenviper.com
*/
// ------------------------------ MAIN CODE -------------------------------------------

require_once(dirname(dirname(__FILE__)).'/run_basalt_tests.php');

basalt_run_tests(1, 'GENERIC BASELINE', 'Try the basic list plugins command, and access each plugin for its XML schema.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0001() {
    basalt_run_single_direct_test(1, 'FAIL: List Plugins (JSON)', 'We first try with no plugin selector. We expect that to fail, then we try with \'baseline\' as the selector.', 'user_tests');
}

function basalt_test_0001($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we try directly:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Next, we add \'/baseline\':</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0002() {
    basalt_run_single_direct_test(2, 'FAIL: List Plugins (XML)', 'We first try with no plugin selector. We expect that to fail, then we try with \'baseline\' as the selector.', 'user_tests');
}

function basalt_test_0002($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we try directly:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we add \'/baseline\':</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0003() {
    basalt_run_single_direct_test(3, 'FAIL: Get All Plugin Schemas (XSD)', 'We determine what plugins we have, then get the XML schema for each', 'user_tests');
}

function basalt_test_0003($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, try a direct call (should fail):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xsd/', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, get all of our plugins:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
        $plugin_list = json_decode($result)->baseline->plugins;
        foreach ($plugin_list as $plugin_name) {
            echo('<h3 style="margin-top:0.25em">This is the schema for '.$plugin_name.':</h3>');
            $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xsd/'.$plugin_name, NULL, NULL, $result_code);
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            } else {
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            }
        }
    }
}
?>
