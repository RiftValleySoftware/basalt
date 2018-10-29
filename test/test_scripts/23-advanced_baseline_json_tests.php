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

basalt_run_tests(180, 'ADVANCED JSON BASELINE TESTS', 'NOTE: These tests may give you problems in MySQL! The big data items seem to stress MySQL. It may be necessary to run them with Postgres.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0180() {
    basalt_run_single_direct_test(180, 'PASS: Do A Radius Search (With Login)', 'Log in with the "God" login, and search for resources (any kind) within 10Km of the Lincoln Memorial', 'things_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0180($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline/search/?search_radius=10&search_longitude=-77.0502&search_latitude=38.8893', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result)->baseline;
        $people = isset($json_object->people) ? count($json_object->people) : 0;
        $places = isset($json_object->places) ? count($json_object->places) : 0;
        $things = isset($json_object->things) ? count($json_object->things) : 0;
        
        if ($people) {
            echo("<p>There were $people people objects returned.</p>");
        }
        
        if ($places) {
            echo("<p>There were $places places objects returned.</p>");
        }
        
        if ($things) {
            echo("<p>There were $things things objects returned.</p>");
        }
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/logout', NULL, $api_result, $result_code);
}

function basalt_test_define_0181() {
    basalt_run_single_direct_test(181, 'PASS: Do A Tag Search (With Login)', 'Log in with the "God" login, and search for resources (any kind) with a "t" in tag 0 (Should be a bunch).', 'things_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0181($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = NULL;
    
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline/search/?search_name='.urlencode('%w%'), NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result)->baseline;
        $people = isset($json_object->people) ? count($json_object->people) : 0;
        $places = isset($json_object->places) ? count($json_object->places) : 0;
        $things = isset($json_object->things) ? count($json_object->things) : 0;
        
        if ($people) {
            echo("<p>There were $people people objects returned.</p>");
        }
        
        if ($places) {
            echo("<p>There were $places places objects returned.</p>");
        }
        
        if ($things) {
            echo("<p>There were $things things objects returned.</p>");
        }
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}
?>
