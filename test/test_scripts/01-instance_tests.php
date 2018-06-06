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

basalt_run_tests(1, 'TESTING 1', 'This is the first set of tests.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0001() {
    basalt_run_single_direct_test(1, 'List Users (No Login)', 'Do not log in, and see what users are returned.', 'user_tests');
}

function basalt_test_0001($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    if (session_start()) {
        session_destroy();
    }
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users', NULL, $in_login, $in_password, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre>'.htmlspecialchars(print_r($result, true)).'</pre>');
    }
}

// --------------------

function basalt_test_define_0002() {
    basalt_run_single_direct_test(2, 'List Users (Normal Login)', 'Log in with a standard login, and see what users are returned.', 'user_tests', 'norm', '', 'CoreysGoryStory');
}

function basalt_test_0002($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    if (session_start()) {
        session_destroy();
    }
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users', NULL, $in_login, $in_password, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre>'.htmlspecialchars(print_r($result, true)).'</pre>');
    }
}

// --------------------

function basalt_test_define_0003() {
    basalt_run_single_direct_test(3, 'List Users (Manager Login)', 'Log in with a manager login, and see what users are returned.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0003($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    if (session_start()) {
        session_destroy();
    }
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users', NULL, $in_login, $in_password, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre>'.htmlspecialchars(print_r($result, true)).'</pre>');
    }
}

// --------------------

function basalt_test_define_0004() {
    basalt_run_single_direct_test(4, 'List Users With Session (Manager Login)', 'Log in with a manager login, and see what users are returned. We then go in again with no login, and make sure the session works.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0004($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    if (session_start()) {
        session_destroy();
    }
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users', NULL, $in_login, $in_password, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre>'.htmlspecialchars(print_r($result, true)).'</pre>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users', NULL, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre>'.htmlspecialchars(print_r($result, true)).'</pre>');
    }
}
?>
