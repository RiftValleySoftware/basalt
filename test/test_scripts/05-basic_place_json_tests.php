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
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0036() {
    basalt_run_single_direct_test(36, 'PASS: List Specific Places (No Login)', 'Do not log in, and see what places are returned.', 'dc_area_tests');
}

function basalt_test_0036($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/13,880,20424,21200', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}
?>
