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

basalt_run_tests(194, 'ADVANCED (1) PERSONAL TOKEN XML TESTS', 'In these tests, we add and remove personal tokens to other IDs.');

// --------------------------------- DEFINITIONS ---------------------------------------

function basalt_test_define_0194() {
    basalt_run_single_direct_test(194, 'PASS-Assign Single Token To Single User', 'Log in as \'Tertiary Login\', and assign one of our tokens (10) to \'Admin Login 4\'.', 'personal_id_test', 'tertiary', '', 'CoreysGoryStory');
}

function basalt_test_define_0195() {
    basalt_run_single_direct_test(195, 'PASS-Assign Multiple Tokens To Multiple Users', 'Log in as \'Tertiary Login\', and assign both of our tokens (10,11) to \'Admin Login 4\'.', 'personal_id_test', 'tertiary', '', 'CoreysGoryStory');
}

function basalt_test_define_0196() {
    basalt_run_single_direct_test(196, 'PASS-Discrete Token Removal From Multiple Users', 'Log in as \'Tertiary Login\', and remove a previously assigned token (11) from \'Secondary Login\' and \'Admin Login 4\'.', 'personal_id_test', 'tertiary', '', 'CoreysGoryStory');
}

function basalt_test_define_0197() {
    basalt_run_single_direct_test(197, 'PASS-Bulk Token Removal From Multiple Users', 'Log in as \'Tertiary Login\', and remove two previously assigned tokens (10,11) from \'Secondary Login\' and \'Admin Login 4\'.', 'personal_id_test', 'tertiary', '', 'CoreysGoryStory');
}

// ----------------------------------- TESTS -------------------------------------------

function basalt_test_0194($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>The first thing we do, is log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'10\' is not one of them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Next, we log in as \'Tertiary User\', and observe the list of personal tokens. Note that \'10\' is not assigned to ID 5 (\'Admin Login 4\'):</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, we assign personal ID 10 to user ID 5 (\'Admin Login 4\'):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/10?assign_tokens_to_user=5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We observe our list of personal tokens again. Note that \'10\' is now assigned to ID 5 (\'Admin Login 4\'):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Finally, log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'10\' is now one of them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

function basalt_test_0195($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>The first thing we do, is log in as \'Secondary Login\', and observe the list of security IDs. Note that \'10\' and \'11\' are already assigned:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=secondary&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>The next thing we do, is log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'10\' is not one of them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Next, we log in as \'Tertiary User\', and observe the list of personal tokens. Note that \'10\' is not assigned to ID 5 (\'Admin Login 4\'):</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, we assign personal ID 10 and 11 to user ID 3 and user ID 5 (\'Secondary Login\' and \'Admin Login 4\').<br />Note that \'Secondary Login\' reports successful assignment, even though it was not changed,<br />and \'Admin Login 4\' reports that \'11\' was added; even though it was already there:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/10,11?assign_tokens_to_user=3,5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We observe our list of personal tokens again. Note that \'10\' is now assigned to ID 5 (\'Admin Login 4\'). This is the only actual change:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Now, log in as \'Secondary Login\', and observe the list of security IDs. Note no changes:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=secondary&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Finally, log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'10\' is now one of them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

function basalt_test_0196($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>The first thing we do, is log in as \'Secondary Login\', and observe the list of security IDs. Note that \'11\' is one of them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=secondary&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Next, we log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'11\' is one of them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Next, we log in as \'Tertiary User\', and observe the list of personal tokens. Note that \'11\' is assigned to IDs 3 and 5 (\'Secondary Login\' and \'Admin Login 4\'):</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, we remove personal ID 11 from user ID 3 (\'Secondary Login\'):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/11?remove_tokens_from_user=3', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, we remove personal ID 11 from user ID 5 (\'Admin Login 4\'):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/11?remove_tokens_from_user=5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We observe our list of personal tokens again. Note that \'11\' is now no longer assigned to ID 3 (\'Secondary Login\') or ID 5 (\'Admin Login 4\'):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }

    echo('<h3>Next, we log in as \'Secondary Login\', and observe the list of security IDs. Note that \'11\' is no longer amongst them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=secondary&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Finally, log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'11\' is now gone:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

function basalt_test_0197($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>The first thing we do, is log in as \'Secondary Login\', and observe the list of security IDs. Note that \'10\' and \'11\' are among them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=secondary&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Next, we log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'11\' is one of them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Next, we log in as \'Tertiary User\', and observe the list of personal tokens. Note that \'10\' is assigned to ID 3 (\'Secondary Login\'), and that \'11\' is assigned to IDs 3 and 5 (\'Secondary Login\' and \'Admin Login 4\'):</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, we remove both IDs from their users:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/10,11?remove_all_these_tokens_from_all_users', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We observe our list of personal tokens again. Note that neither ID is assigned to ID 3 (\'Secondary Login\') or ID 5 (\'Admin Login 4\'):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }

    echo('<h3>Next, we log in as \'Secondary Login\', and observe the list of security IDs. Note that \'10\' and \'11\' are no longer amongst them:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=secondary&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h3>Finally, log in as \'Admin Login 4\', and observe the list of security IDs. Note that \'11\' is now gone:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}
?>
