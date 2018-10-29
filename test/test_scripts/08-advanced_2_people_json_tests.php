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

basalt_run_tests(64, 'ADVANCED JSON PEOPLE TESTS (PART 2)', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0064() {
    basalt_run_single_direct_test(64, 'PASS: Create a Generic User (No Login Associated) with child objects', 'We log in as a manager, and create a new user. This time, we associate a couple of child objects.', 'user_tests', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0064($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Welcome to our new user:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/?child_ids=2,3,4', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
}

// --------------------

function basalt_test_define_0065() {
    basalt_run_single_direct_test(65, 'PASS: Edit the Generic User, Changing Children', 'We log in as a manager, and edit the user we just created. We add a child object, and remove one child object.', '', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0065($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>BEFORE:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>DURING:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=-3,5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
}

// --------------------

function basalt_test_define_0066() {
    basalt_run_single_direct_test(66, 'PASS: Edit the Generic User, Removing All Children', 'We log in as a manager, and edit the user we just created. This time, we delete all the children.', '', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0066($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>BEFORE:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>DURING:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=-2,-4,-5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
}

// --------------------

function basalt_test_define_0067() {
    basalt_run_single_direct_test(67, 'PASS: Edit the Generic User, Removing Nonexistent Children', 'We log in as a manager, and edit the user we just created. This time, we try deleting children that don\'t exist. There should be no errors, but nothing happens.', '', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0067($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>BEFORE:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>DURING:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=-2,-4,-5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
}

// --------------------

function basalt_test_define_0068() {
    basalt_run_single_direct_test(68, 'PASS: Edit the Generic User, and Add Children From Scratch', 'We log in as a manager, and edit the user we just created. Now, we add some new children, including one that we don\'t have read rights to (1).', '', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0068($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>BEFORE:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>DURING:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=1,2,3,4,5,6', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Now, Remove #3, #4 and #5 (For the next test):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=-3,-4,-5', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>AFTER (Redux):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0069() {
    basalt_run_single_direct_test(69, 'PASS: Edit the Generic User, and Add Mixed Children', 'We log in as a manager, and edit the user we just created. Now, we add some new children, including a couple that we don\'t have read rights to, and a couple that don\'t exist (We cannot read 2, but we can read all the others. 7 and 8 don\'t exist).', 'user_tests', 'drama-queen', '', 'CoreysGoryStory');
}

function basalt_test_0069($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In \''.$in_login.'\':</h3>');
    $drama_queen_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($drama_queen_api_key, true)).'</code></h3>');
    }
    
    echo('<h3>Log In \'asp\':</h3>');
    $asp_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=asp&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($asp_api_key, true)).'</code></h3>');
    }
    
    echo('<h3>We first create a new user with \'asp\'::</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/?child_ids=2,3,4', NULL, $asp_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>BEFORE (Note that this ID can\'t see #2):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $drama_queen_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>DURING:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=2,5,6,7,8', NULL, $drama_queen_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $drama_queen_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h4 style="font-style:italic">Now, we log in with \'asp\', and make sure that 2 is still there.</h4>');
    echo('<h3>AS ASP SEES IT:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $asp_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h4 style="font-style:italic">Now, we log back in with \'drama-queen\', and try to delete #2, which we can\'t see.</h4>');
    echo('<h3>DURING:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=-2', NULL, $drama_queen_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h4 style="font-style:italic">Now, we log in with \'asp\' again, and make sure that 2 is still there.</h4>');
    echo('<h3>AS ASP SEES IT:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $asp_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h4 style="font-style:italic">Now, we delete #2 (as \'asp\'). It should work, this time.</h4>');
    echo('<h3>DURING:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?child_ids=-2', NULL, $asp_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $asp_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0070() {
    basalt_run_single_direct_test(70, 'PASS: Create a User/Login Pair With Child Objects, Names and A Picture', 'We log in as a manager, and create a new user and login pair. We give the user the \'Full Monty\'.', 'user_tests', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0070($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Welcome to our new user:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/?login_id=Marvin&name=Marvin&surname=Martian&given_name=Marvin&middle_name=D&nickname=Angry+Little+Fella&prefix=Mar.Tian&suffix=Esq.&tokens=6,7,8&child_ids=2,5,6,7,8&read_token=5&write_token=5', Array('filepath' => dirname(dirname(__FILE__)).'/images/Marvin.svg', 'type' => 'image/svg+xml', 'name' => 'Marvin.svg'), $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people->new_user->payload;
        $type = $json_object->people->people->new_user->payload_type;
        $json_object->people->people->new_user->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Has anyone seen my Immodium Q35 Explosive Space Modulator?" alt="Marvin" style="width:256px" /></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
}

// --------------------

function basalt_test_define_0071() {
    basalt_run_single_direct_test(71, 'FAIL: Modify the New User Object Completely', 'We log in as a manager, and run a \'Full Monty\' change to the user we just created.', '', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0071($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>BEFORE:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Has anyone seen my Immodium Q35 Explosive Space Modulator?" alt="Marvin" style="width:256px" /></div>');
    }
    echo('<h3>This Should Fail (No Login User Described, and We Are Setting Tokens):</h3>');
    $new_image = Array('filepath' => dirname(dirname(__FILE__)).'/images/lena.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?name=Lenna&surname=Söderberg&given_name=Lena&middle_name=Whoah+Nellie!&nickname=Lenna+Sjööblom&prefix=Ms.&suffix=Scanner+Test+Model&tokens=3,4,5&child_ids=-2,-5,-6,7,-8&read_token=1&write_token=11', $new_image, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $json_object->people->people->changed_users[0]->before->payload = '[LARGE PAYLOAD]';
        $json_object->people->people->changed_users[0]->after->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
    }
    echo('<h3>Now, This Will Work (We Added the \'login_user\' Parameter). Note That the Only Things Changed in the Login Are the Tokens:</h3>');
    $new_image = Array('filepath' => dirname(dirname(__FILE__)).'/images/lena.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?login_user&name=Lenna&surname=Söderberg&given_name=Lena&middle_name=Whoah+Nellie!&nickname=Lenna+Sjööblom&prefix=Ms.&suffix=Scanner+Test+Model&tokens=3,4,5&child_ids=-2,-5,-6,7,-8&read_token=1&write_token=11', $new_image, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $json_object->people->people->changed_users[0]->before->payload = '[LARGE PAYLOAD]';
        $json_object->people->people->changed_users[0]->after->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
    }
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="That\'s Better!" alt="Lena" style="width:256px;border-radius:2em;border:none" /></div>');
    }
}

// --------------------

function basalt_test_define_0072() {
    basalt_run_single_direct_test(72, 'FAIL: Test Accessing Login Properties When Changing A User', 'We log in as a manager, and then try to do a few things that should fail.', 'user_tests', 'aspie', '', 'CoreysGoryStory');
}

function basalt_test_0072($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($aspie_api_key, true)).'</code></h3>');
    }
    echo('<h3>First, we create a new user and login pair:</h3>');
    $new_image = Array('filepath' => dirname(dirname(__FILE__)).'/images/lena.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/?login_id=Lenna&lang=sv&name=Lenna&surname=Söderberg&given_name=Lena&middle_name=Whoah+Nellie!&nickname=Lenna+Sjööblom&prefix=Ms.&suffix=Scanner+Test+Model&tokens=3,4,5&child_ids=2,3', $new_image, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people->new_user->payload;
        $new_login_id = $json_object->people->people->new_user->associated_login->login_id;
        $password = $json_object->people->people->new_user->associated_login->password;
        $type = $json_object->people->people->new_user->payload_type;
        $json_object->people->people->new_user->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Lenna" alt="Lenna" style="width:256px;border-radius:2em;border:none" /></div>');
    }
    echo('<h4>At this point, we have a new login. Let\'s first login with that ID:</h4>');
    $lenas_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$new_login_id.'&password='.$password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($lenas_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Start Simple. Let\'s begin by changing our name:</h4>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?name=Lena', NULL, $lenas_api_key, $result_code);
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?login_user&show_details', NULL, $lenas_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Lenna" alt="Lenna" style="width:256px;border-radius:2em;border:none" /></div>');
    }
    
    echo('<h4>Now, let\'s try something we\'re not allowed to do. Change our login string:</h4>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?login_string=Lena', NULL, $lenas_api_key, $result_code);
    echo('<h3>AFTER:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?login_user&show_details', NULL, $lenas_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Lenna" alt="Lenna" style="width:256px;border-radius:2em;border:none" /></div>');
    }
    
    echo('<h4>This time, we will go in as \'God,\' and change the login string. It should work now:</h4>');
    $god_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($god_api_key, true)).'</code></h3>');
    }
    
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?login_string=Lena', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $json_object->people->people->changed_users[0]->before->payload = '[LARGE PAYLOAD]';
        $json_object->people->people->changed_users[0]->after->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
    }
    
    echo('<h3>By the way, having the God ID go in there and change the login caused the old API key to invalidate, so if Lena tries to look with her current login, she gets nothing:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?login_user&show_details', NULL, $lenas_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Lenna" alt="Lenna" style="width:256px;border-radius:2em;border:none" /></div>');
    }

    echo('<h3>Let\'s log in again, and try over:</h3>');
    $lenas_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$new_login_id.'&password='.$password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($lenas_api_key, true)).'</code></h3>');
    }
    
    echo('<h3>Oops. Wrong login. Let\'s try \'Lena\', instead:</h3>');
    $lenas_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=Lena&password='.$password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($lenas_api_key, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?login_user&show_details', NULL, $lenas_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Lenna" alt="Lenna" style="width:256px;border-radius:2em;border:none" /></div>');
    }
}

// --------------------

function basalt_test_define_0073() {
    basalt_run_single_direct_test(73, 'FAIL: Test \'My Info\' Functionality', 'We log in with different logins, and make sure the \'my_info\' functionality works.', 'user_tests');
}

function basalt_test_0073($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    
    echo('<h4>Start by getting a \'God Mode\' API Key:</h4>');
    $god_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($god_api_key, true)).'</code></h3>');
    }
    
    echo('<h3>View The Information (GET):</h3>');
    echo('<h4>User:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>User (With Login):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>As Login:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The User Information (PUT):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?name=I+AM&surname=THERE+IS+NO+GOD+BUT+GOD', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The Login Information (PUT):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?name=Yahewh', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Result:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>We Expect A Delete of the \'God\' Login to Fail (DELETE):</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?delete_user', NULL, $god_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Next, get a \'Manager\' API Key:</h4>');
    $manager_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=asp&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($manager_api_key, true)).'</code></h3>');
    }
    
    echo('<h3>View The Information (GET):</h3>');
    echo('<h4>User:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>User (With Login):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>As Login:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The User Information (PUT):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?name=I+Can&surname=Manage+This', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The Login Information (PUT):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?name=Winning!', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Result:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Remove Ourselves (DELETE):</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?delete_user', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Make Sure We\'re Gone:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $manager_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Next, get a \'Regular User\' API Key:</h4>');
    $regular_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=krait&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($regular_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>User:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>User (With Login):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>As Login:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The User Information (PUT):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?name=Munky&middle_name=See,+Munky&surname=Do', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The Login Information (PUT):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?name=Ook', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Result:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Remove Ourselves (DELETE):</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?delete_user', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Make Sure We\'re Gone:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $regular_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Lastly, get a \'Login Only\' API Key:</h4>');
    $login_only_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=bob&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($login_only_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>User (We expect nothing):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>User (With Login -We expect nothing):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>As Login:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The User Information (PUT -We expect nothing):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?name=Normal&middle_name=Boring&surname=Dull', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Modify The Login Information (PUT):</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?name=NORML', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Result (User -We expect nothing):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Result (Login):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>Remove Ourselves (DELETE):</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Make Sure We\'re Gone:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $login_only_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}
?>
