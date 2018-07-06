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
// ------------------------------ MAIN CODE -------------------------------------------

require_once(dirname(dirname(__FILE__)).'/run_basalt_tests.php');

basalt_run_tests(182, 'ADVANCED XML BASELINE TESTS', 'NOTE: These tests may give you problems in MySQL! The big data items seem to stress MySQL. It may be necessary to run them with Postgres.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0182() {
    basalt_run_single_direct_test(182, 'PASS: Do A Radius Search (With Login)', 'Log in with the "God" login, and search for resources (any kind) within 10Km of the Lincoln Memorial', 'things_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0182($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline/search/?search_radius=10&search_longitude=-77.0502&search_latitude=38.8893', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $people = isset($xml_object->people->value) ? count($xml_object->people->value) : 0;
        $places = isset($xml_object->places->value) ? count($xml_object->places->value) : 0;
        $things = isset($xml_object->things->value) ? count($xml_object->things->value) : 0;
        
        if ($people) {
            echo("<p>There were $people people objects returned.</p>");
        }
        
        if ($places) {
            echo("<p>There were $places places objects returned.</p>");
        }
        
        if ($things) {
            echo("<p>There were $things things objects returned.</p>");
        }
        
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/logout', NULL, $api_result, $result_code);
}

function basalt_test_define_0183() {
    basalt_run_single_direct_test(183, 'PASS: Do A Tag Search (With Login)', 'Log in with the "God" login, and search for resources (any kind) with a "t" in tag 0 (Should be a bunch).', 'things_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0183($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = NULL;
    
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline/search/?search_name='.urlencode('%w%'), NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $people = isset($xml_object->people->value) ? count($xml_object->people->value) : 0;
        $places = isset($xml_object->places->value) ? count($xml_object->places->value) : 0;
        $things = isset($xml_object->things->value) ? count($xml_object->things->value) : 0;
        
        if ($people) {
            echo("<p>There were $people people objects returned.</p>");
        }
        
        if ($places) {
            echo("<p>There were $places places objects returned.</p>");
        }
        
        if ($things) {
            echo("<p>There were $things things objects returned.</p>");
        }
        
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}
?>
