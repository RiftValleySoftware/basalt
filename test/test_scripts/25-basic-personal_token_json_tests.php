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
// ---------------------------------- MAIN CODE ----------------------------------------

require_once(dirname(dirname(__FILE__)).'/run_basalt_tests.php');

basalt_run_tests(184, 'BASIC PERSONAL TOKEN JSON TESTS', 'We simply make sure that we can read (GET) information about personal tokens.');

// --------------------------------- DEFINITIONS ---------------------------------------

function basalt_test_define_0184() {
    basalt_run_single_direct_test(184, 'PASS-God Admin-Check All Tokens And Listing', 'We log in as the \'God\' admin, make sure the listing is correct, make sure that we can see all the tokens, and that we don\'t have any.', 'personal_id_test', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_define_0185() {
    basalt_run_single_direct_test(185, 'FAIL-Non-God Admin-Check All Tokens And Listing', 'We log in as the \'Secondary\' admin (non-manager), make sure the listing is correct, that we can\'t see all the tokens, and that we list the ones we have.', 'personal_id_test', 'secondary', '', 'CoreysGoryStory');
}

function basalt_test_define_0186() {
    basalt_run_single_direct_test(186, 'FAIL-Non-God Admin-Check All Tokens And Listing', 'We log in as the \'Tertiary\' admin (manager), make sure the listing is correct, that we can\'t see all the tokens, and that we list the ones we have.', 'personal_id_test', 'tertiary', '', 'CoreysGoryStory');
}

// ----------------------------------- TESTS -------------------------------------------

function basalt_test_0184($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>First, try just \'/people\', and we want to make sure that \'personal_tokens\' is listed:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Next, we add \'/people/personal_tokens\'. Since this is the \'God\' admin, the call will work, and return all personal tokens in the system:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/personal_tokens', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Next, try just \'/people/logins/my_info\', and make sure that we don\'t list any personal tokens:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Finally, we run \'/people/personal_tokens/my_info\'. Again, we make sure that we don\'t have any tokens listed:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

function basalt_test_0185($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>First, try just \'/people\', and we want to make sure that \'personal_tokens\' is listed:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Next, we add \'/people/personal_tokens\'. This should fail, as we are not a \'God\' Admin:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/personal_tokens', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Next, try just \'/people/logins/my_info\', and make sure that we list our personal tokens:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Finally, we run \'/people/personal_tokens/my_info\'. Again, we make sure that we have tokens listed:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

function basalt_test_0186($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0185($in_login, $in_hashed_password, $in_password);
}
?>
