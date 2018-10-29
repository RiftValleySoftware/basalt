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

basalt_run_tests(35, 'BASIC JSON PLACES TESTS', 'In which our intrepid hero does some basic REST Logins, and asks for information about places in JSON.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0035() {
    basalt_run_single_direct_test(35, 'PASS: List Places (No Login)', 'Do not log in, and see what places are returned.', 'dc_area_tests');
}

function basalt_test_0035($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0036() {
    basalt_run_single_direct_test(36, 'PASS: List Specific Places (No Login)', 'Do not log in, and see what places are returned.', 'dc_area_tests');
}

function basalt_test_0036($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/13,780,424,212,501', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0037() {
    basalt_run_single_direct_test(37, 'PASS: List Places (No Login), With Detail', 'Do not log in, and see what places are returned. This time ask for more details.', 'dc_area_tests');
}

function basalt_test_0037($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0038() {
    basalt_run_single_direct_test(38, 'PASS: List Specific Places (No Login), With Detail', 'Do not log in, and see what places are returned. This time ask for more details.', 'dc_area_tests');
}

function basalt_test_0038($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/13,780,424,212,501?show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0039() {
    basalt_run_single_direct_test(39, 'PASS: List Places (With Login)', 'Log in, and see what places are returned.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0039($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0040() {
    basalt_run_single_direct_test(40, 'PASS: List Places (With Login), With Detail', 'Log in, and see what places are returned. This time ask for more details.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0040($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0041() {
    basalt_run_single_direct_test(41, 'PASS: List Specific Places (With Login), With Detail', 'Log in, and see what places are returned. This time ask for more details.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0041($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/13,780,424,212,501?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0042() {
    basalt_run_single_direct_test(42, 'PASS: Do A Radius Search (No Login)', 'Do not log in, and search for meetings within 5Km of the Lincoln Memorial', 'dc_area_tests');
}

function basalt_test_0042($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=5&search_longitude=-77.0502&search_latitude=38.8893', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0043() {
    basalt_run_single_direct_test(43, 'PASS: Do A Radius Search (No Login), With Detail', 'Same thing, but ask for more details.', 'dc_area_tests');
}

function basalt_test_0043($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=5&search_longitude=-77.0502&search_latitude=38.8893&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0044() {
    basalt_run_single_direct_test(44, 'PASS: Do A Radius Search (With Login)', 'Log in, and search for meetings within 5Km of the Lincoln Memorial', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0044($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=5&search_longitude=-77.0502&search_latitude=38.8893', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0045() {
    basalt_run_single_direct_test(45, 'PASS: Do A Radius Search (With Login), With Detail', 'Same thing, but this time, log in, and ask for more details.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0045($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=5&search_longitude=-77.0502&search_latitude=38.8893&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<p>There were '.count(json_decode($result)->places->results).' places returned.</p>');
        
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}
?>
