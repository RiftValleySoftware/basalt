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

basalt_run_tests(201, 'ADVANCED (2) PERSONAL TOKEN XML TESTS', 'Personal Token Creation and Cataloging');

// --------------------------------- DEFINITIONS ---------------------------------------

function basalt_test_define_0201() {
    basalt_run_single_direct_test(201, 'PASS-Create New User With Personal IDs', 'Log in as a manager, create a new user, and associate 5 personal tokens with it.', 'personal_id_test', 'tertiary', '', 'CoreysGoryStory');
}

function basalt_test_define_0202() {
    basalt_run_single_direct_test(202, 'PASS-Baseline-List IDs, and Ensure They are Assigned Properly.', 'Log in as the \'God\' Admin, and ask to see all the security IDs. Ensure they are the proper type.', '', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_define_0203() {
    basalt_run_single_direct_test(203, 'PASS-Assign Unassigned Personal Token to User.', 'Log in as the \'God\' Admin, and assign an unassigned token to a user as a personal token.', '', 'admin', '', CO_Config::god_mode_password());
}

// ----------------------------------- TESTS -------------------------------------------

function basalt_test_0201($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>This is the new user. Note that we do not see any personal tokens; even though we created the user:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/?login_id=test_user&password=CoreysGoryStory&number_of_personal_tokens=5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Log in as the new user, and observe the list of personal tokens:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=test_user&password=CoreysGoryStory', NULL, NULL, $result_code);
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
    echo('<h3>Log in as the \'God\' Admin, and observe the list of personal tokens:</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/test_user/?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

function basalt_test_0202($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>Log In as \'God\':</h4>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h4>List All Visible Tokens:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline/tokens?types', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h4>Log In as a Manager With Personal Tokens:</h4>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=tertiary&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h4>List All Visible Tokens:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline/tokens?types', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    echo('<h4>Log In as a User With No Personal Tokens:</h4>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=four&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h4>List All Visible Tokens:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline/tokens?types', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

function basalt_test_0203($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>Log In as \'God\':</h4>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h4>BEFORE. Note the personal tokens:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/test_user/?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>Replace 13-17 with 6-7, in the newest login:</h4>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/personal_tokens/6,7?set_user_tokens=12', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>Now, notice the tokens have been replaced with 6 and 7:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/test_user/?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>List All Visible Tokens. Note that the previous tokens are now just \'token\' types, and 6 and 7 are now assigned to ID 12:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline/tokens?types', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}
?>
