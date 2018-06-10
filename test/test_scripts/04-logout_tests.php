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

basalt_run_tests(22, 'LOGOUT TESTS', 'Simple tests of the logout command.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0022() {
    basalt_run_single_direct_test(22, 'FAIL: Log Out Non-Logged-In User', 'Do not log in, and try logging out. We expect to get a 403 error code.', 'user_tests');
}

function basalt_test_0022($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">UH-OH! This shouldn\'t be here!</pre>');
    }
}

// -------------------

function basalt_test_define_0023() {
    basalt_run_single_direct_test(23, 'FAIL: Log Out Logged-In Low-Level User', 'Log in, and try logging out. This should work the first time, but fail the second time.', 'user_tests', 'norm', '', 'CoreysGoryStory');
}

function basalt_test_0023($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Do a simple user test.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Now logout.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    echo('<h3>Try the same user test again (This time, we should get a 401 error).</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// -------------------

function basalt_test_define_0024() {
    basalt_run_single_direct_test(24, 'FAIL: Log Out Logged-In Normal User', 'Log in, and try logging out. This should work the first time, but fail the second time.', 'user_tests', 'krait', '', 'CoreysGoryStory');
}

function basalt_test_0024($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Do a simple user test.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Now logout.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    echo('<h3>Try the same user test again (This time, we should get a 401 error).</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// -------------------

function basalt_test_define_0025() {
    basalt_run_single_direct_test(25, 'FAIL: Log Out Logged-In Manager User', 'Log in, and try logging out. This should work the first time, but fail the second time.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0025($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Do a simple user test.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Now logout.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    echo('<h3>Try the same user test again (This time, we should get a 401 error).</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// -------------------

function basalt_test_define_0026() {
    basalt_run_single_direct_test(26, 'FAIL: Log Out Logged-In God User', 'Log in, and try logging out. This should work the first time, but fail the second time.', 'user_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0026($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Do a simple user test.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Now logout.</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    echo('<h3>Try the same user test again (This time, we should get a 401 error).</h3>');
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/users/users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}
?>
