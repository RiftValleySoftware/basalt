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

basalt_run_tests(17, 'BASIC XML PEOPLE GET TESTS', 'In which our intrepid hero does some basic REST Logins, and asks for information about people in JSON.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0017() {
    basalt_run_single_direct_test(17, 'FAIL: List Users Commands (No Login)', 'Do not log in, and see what commands are returned. GET should work, POST, PUT and DELETE should not', 'user_tests');
}

function basalt_test_0017($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people', NULL, NULL, $result_code);
    echo('<h3>GET:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>POST:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/xml/people', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>PUT:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>DELETE:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/xml/people', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0018() {
    basalt_run_single_direct_test(18, 'PASS: List Users (No Login)', 'Do not log in, and see what people are returned.', 'user_tests');
}

function basalt_test_0018($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0019() {
    basalt_run_single_direct_test(19, 'PASS: List Users (Normal Login)', 'Log in with a standard login, and see what people are returned.', 'user_tests', 'norm', '', 'CoreysGoryStory');
}

function basalt_test_0019($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h4>What users can we see?</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>See if we can see any currently logged-in users (We expect the "logged_in" query to be ignored, returning all users)</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people?logged_in', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0020() {
    basalt_run_single_direct_test(20, 'PASS: List Users (Manager Login)', 'Log in with a manager login, and see what people are returned.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0020($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>And last, but not least, our very own info (without login):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/my_info', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>With login:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/my_info?login_user', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>See if we can see any currently logged-in users (We expect this to work, and only show our user)</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people?logged_in', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0021() {
    basalt_run_single_direct_test(21, 'PASS: List Logins (Manager Login)', 'Log in with a manager login, and see what logins are returned.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0021($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Get all people we can see, that have logins:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, get them by discrete IDs:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/3,4', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, get just one of them by discrete ID:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/4', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Get another one discrete ID:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/7', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>See if we can see any currently logged-in users</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins?logged_in', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0022() {
    basalt_run_single_direct_test(22, 'PASS: List Users With Session (Manager Login)', 'Log in with a manager login, look for a couple of specific people. We then go in again with no login, and make sure the session works by looking for another single user.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0022($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2,3', NULL, $api_result, $result_code);
    echo('<h3>We\'re going in again, using the API key:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We\'re going in again, using the API key:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/4', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0023() {
    basalt_run_single_direct_test(23, 'FAIL: List Users With Session (Manager Login), But Running Out the Clock', 'Log in with a manager login, and see what people are returned. We then wait for three seconds (the timeout is two) and go in again with no login, and make sure the session works.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0023($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    echo('<h3>We\'re going in again, using the API key:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    sleep(2);
    echo('<h3>We\'ve waited two seconds, and we\'re going in again, using the API key:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now we log back in, and try again:</h3>');
    $result_code = 200;
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    echo('<h3>We\'re going in again, using the API key:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0024() {
    basalt_run_single_direct_test(24, 'FAIL: List Users (God Login)', 'Log in with a God login, and see what people are returned.', 'user_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0024($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h4>Log in a few users to test the list of logins.</h4>');
    $ignored_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=norm&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($ignored_result, true)).'</code></h3>');
    }
    $ignored_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=bob&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($ignored_result, true)).'</code></h3>');
    }
    $ignored_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=krait&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($ignored_result, true)).'</code></h3>');
    }
    $ignored_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=asp&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($ignored_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We\'re going in again, looking at individual people:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2,3,5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>And again, but a single user (us):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    sleep(1);
    echo('<h3>We just let ourselves time out. Let\'s try again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/5', NULL, $api_result, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now we log back in, and try again:</h3>');
    $result_code = 200;
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>See if we can see any currently logged-in users (as users)</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people?logged_in', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h4>See if we can see any currently logged-in users (as logins)</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins?logged_in', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0025() {
    basalt_run_single_direct_test(25, 'FAIL: List Logins (God Login)', 'Log in with a God login, and see what logins are returned.', 'user_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0025($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We\'re going in again, looking at individual logins (asking for extra detail):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/3,4,5?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>And again, but a single login (us):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/2', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    sleep(1);
    echo('<h3>We just let ourselves time out. Let\'s try again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/2', NULL, $api_result, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now we log back in, and try again (but ask for extra detail):</h3>');
    $result_code = 200;
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/2?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0026() {
    basalt_run_single_direct_test(26, 'PASS: List Users that Have Logins (God Login)', 'Log in with a God login, and see what people are returned. We will not see the "Unaffiliated User Item" user.', 'user_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0026($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>First, we see all the people:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we ask only for the login people:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people?login_user', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0027() {
    basalt_run_single_direct_test(27, 'PASS: List Users With Extra Detail (God Login)', 'Log in with a God login, and see what people are returned. We will ask for a detailed listing, as well as a normal listing.', 'user_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0027($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>First, we see all the people:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we ask for listings with extra details:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0028() {
    basalt_run_single_direct_test(28, 'PASS: List Users From Login ID Strings and IDs -and Details (God Login)', 'Log in with a God login, and see what people are returned. We will ask for a detailed listing, as well as a normal listing.', 'user_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0028($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>First, we see all the people:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we ask for listings for specific logins (including one that doesn\'t exist for us -\'bob\' exists, but is invisible, because there is no corresponding user):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/login_ids/asp,norm,bob', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Same thing, but this time, ask for extra detail:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/login_ids/asp,norm,bob?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Do it again. Only this time, we use the numeric IDs:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/login_ids/7,3,4', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Same thing, but this time, ask for extra detail:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/login_ids/7,3,4?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Now, Just Ask for \'norm\':</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/login_ids/norm', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>...and with extra detail:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/login_ids/norm?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0029() {
    basalt_run_single_direct_test(29, 'PASS: List Logins From Login ID Strings -and Details (God Login)', 'Log in with a God login, and see what logins are returned. We will ask for a detailed listing, as well as a normal listing.', 'user_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0029($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>First, we see all the logins:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we call with a list of numerical IDs:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/3,4,5,6', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we call with a single numerical ID (us), and more details:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/2?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we call with a list of string IDs:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/norm,bob,cobra,krait', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we call with a single string ID (us), and more details:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/logins/admin?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}
?>
