<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, The Great Rift Valley Software Company
    
    LICENSE:
    
    MIT License
    
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
    modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
    CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


    The Great Rift Valley Software Company: https://riftvalleysoftware.com
*/
// ------------------------------ MAIN CODE -------------------------------------------

require_once(dirname(dirname(__FILE__)).'/run_basalt_tests.php');

basalt_run_tests(105, 'ADVANCED BASELINE (JSON)', '');

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

// --------------------

function basalt_test_define_0108() {
    basalt_run_single_direct_test(108, 'FAIL: Create A Token (No Login)', 'We do not log in, and try to create a token. We should get a 403.', 'user_tests');
}

function basalt_test_0108($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
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
    
    echo('<h3>List Our Tokens At First:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline/tokens', NULL, $api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h3>Create A New Token:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/baseline/tokens', NULL, $api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
        echo('<h3>List Our Tokens Now:</h3>');
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline/tokens', NULL, $api_key, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
        }
    }
}

// --------------------

function basalt_test_define_0109() {
    basalt_run_single_direct_test(109, 'FAIL: Create Token (Regular User)', 'We log in as a Regular user, and try to create a token. We expect this to fail with a 403.', 'user_tests', 'norm', '', 'CoreysGoryStory');
}

function basalt_test_0109($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0108($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0110() {
    basalt_run_single_direct_test(110, 'PASS: Create Token (Manager User)', 'We log in as a Manager user, and try to create a token.', 'user_tests', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0110($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0108($in_login, $in_hashed_password, $in_password);
}

?>
