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

basalt_run_tests(105, 'ADVANCED BASELINE', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0105() {
    basalt_run_single_direct_test(105, 'PASS: List Tokens (Simple User)', 'We log in as a simple user with no extra tokens, and ask for a list of tokens. We should get only that user ID as a result.', 'user_tests', 'norm', '', 'CoreysGoryStory');
}

function basalt_test_0105($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_key = NULL;
    
    if ($in_login && $in_password) {
        echo('<h4>Log in \''.$in_login.'\':</h4>');
        $api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password.'', NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_key, true)).'</code></h3>');
        }
    }

    echo('<h3>List Our Tokens:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline/tokens', NULL, $api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0106() {
    basalt_run_single_direct_test(106, 'PASS: List Tokens (Powerful User)', 'We log in as a more powerful user, with a lot of extra tokens, and list them.', 'user_tests', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0106($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0105($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0107() {
    basalt_run_single_direct_test(107, 'FAIL: List Tokens (No Login)', 'We try to list tokens with no login. We should get a 403 error.', 'user_tests');
}

function basalt_test_0107($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0105($in_login, $in_hashed_password, $in_password);
}
?>
