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

basalt_run_tests(57, 'ADVANCED JSON PEOPLE TESTS', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0057() {
    basalt_run_single_direct_test(57, 'PASS: Add an Image Payload to an Existing User', 'We add a picture to \'norm\', and change the user name to \'Lena\' (Guess which picture we\'re uploading).', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0057($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?name=Lena&surname=Söderberg&given_name=Lena&middle_name=Whoah+Nellie!&nickname=Lenna+Sjööblom&prefix=Ms.&suffix=Scanner+Test+Model', Array('filepath' => dirname(dirname(__FILE__)).'/images/lena.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg'), $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>AFTER:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Woah! Norm, have you been taking hormones?" alt="Lena, not Norm" style="width:256px;border-radius:2em;border:none" /></div>');
    }
}

// --------------------

function basalt_test_define_0058() {
    basalt_run_single_direct_test(58, 'PASS: Replace the Image Payload to an Existing User', 'We change the picture for \'norm\', and change the name again.', '', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0058($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Woah! Norm, have you been taking hormones?" alt="Lena, not Norm" style="width:256px;border-radius:2em;border:none" /></div>');
    }
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?name=Marvin&surname=Martian&given_name=Marvin&middle_name=D&nickname=Angry+Little+Fella&prefix=&suffix=', Array('filepath' => dirname(dirname(__FILE__)).'/images/Marvin.svg', 'type' => 'image/svg+xml', 'name' => 'Marvin.svg'), $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>AFTER:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="I\'m Very, Very Angry, Right Now!" alt="Marvin, not Lena" style="width:256px" /></div>');
    }
}

// --------------------

function basalt_test_define_0059() {
    basalt_run_single_direct_test(59, 'PASS: Delete the Image Payload, and a Lot of Information, from an Existing User', 'We remove the picture for \'norm\', change the name again, and delete a lot of the name information we previously set.', '', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0059($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people[0]->payload;
        $type = $json_object->people->people[0]->payload_type;
        $json_object->people->people[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="I\'m Very, Very Angry, Right Now!" alt="Marvin, not Lena" style="width:256px" /></div>');
    }
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?name=Norm&surname=&given_name=&middle_name=&nickname=&prefix=&suffix=&remove_payload', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>AFTER:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0060() {
    basalt_run_single_direct_test(60, 'PASS: Delete a User', 'We attempt to remove the user for \'krait\'; However, we are not cleared to remove \'krait\' (We only have read access), so it won\'t happen.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0060($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Note that there are no users deleted:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3', NULL, $api_result, $result_code);
    echo('<h3>AFTER. Like a Bad Penny, It\'s Still Here:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0061() {
    basalt_run_single_direct_test(61, 'PASS: Delete a User', 'We remove an unaffiliated user, for which we have edit rights (It\'s a \'1\', which means that anyone can write/delete it).', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0061($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/6?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE. Note that \'writeable\' is Set. That Means We Are Allowed to Delete It:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>We will see a record of us deleting the user:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/6', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/6', NULL, $api_result, $result_code);
    echo('<h3>AFTER. It\'s Gone:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0062() {
    basalt_run_single_direct_test(62, 'PASS: Create a Generic User (No Login Associated)', 'We log in as a manager, and create a new user, without any extra frills (or login instance).', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0062($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Welcome to our new user:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0063() {
    basalt_run_single_direct_test(63, 'PASS: Create a Generic User and Login Pair', 'We log in as a manager, and create a new user and login, without any extra frills.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0063($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Welcome to our new user:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/?login_id=ColonelSanders', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

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
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Has anyone seen my Immodium Q35 Space Modulator?" alt="Marvin" style="width:256px" /></div>');
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
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Has anyone seen my Immodium Q35 Space Modulator?" alt="Marvin" style="width:256px" /></div>');
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
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>First, we create a new user and login pair:</h3>');
    $new_image = Array('filepath' => dirname(dirname(__FILE__)).'/images/lena.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/?login_id=Lenna&lang=sv&name=Lenna&surname=Söderberg&given_name=Lena&middle_name=Whoah+Nellie!&nickname=Lenna+Sjööblom&prefix=Ms.&suffix=Scanner+Test+Model&tokens=3,4,5&child_ids=2,3', $new_image, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->people->people->new_user->payload;
        $new_login_id = $json_object->people->people->new_user->associated_login->login_id;
        $new_password = $json_object->people->people->new_user->associated_login->password;
        $type = $json_object->people->people->new_user->payload_type;
        $json_object->people->people->new_user->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Lenna" alt="Lenna" style="width:256px;border-radius:2em;border:none" /></div>');
    }
    echo('<h3>At this point, we have a new login. Let\'s first login with that ID:</h3>');
    $lenas_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$new_login_id.'&password='.$new_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($lenas_api_key, true)).'</code></h3>');
    }
}
?>
