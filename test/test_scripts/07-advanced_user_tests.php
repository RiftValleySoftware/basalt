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
    basalt_run_single_direct_test(57, 'PASS: Add an Image Payload to an Existing User (PUT)', 'We add a picture to \'norm\', and change the user name to \'Lena\' (Guess which picture we\'re uploading).', 'user_tests', 'asp', '', 'CoreysGoryStory');
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
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?name=Lena', Array('filepath' => dirname(dirname(__FILE__)).'/images/lena.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg'), $api_result, $result_code);
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
        echo('<img src="data:'.$type.';base64,'.$payload.'" title="Woah! Norm, have you been taking hormones?" alt="Lena, not Norm" />');
    }
}

// --------------------

function basalt_test_define_0058() {
    basalt_run_single_direct_test(58, 'PASS: Replace the Image Payload to an Existing User (POST)', 'We change the picture for \'norm\'; this time, using POST.', '', 'asp', '', 'CoreysGoryStory');
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
        echo('<img src="data:'.$type.';base64,'.$payload.'" title="Woah! Norm, have you been taking hormones?" alt="Lena, not Norm" />');
    }
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/2?name=Marvin', Array('filepath' => dirname(dirname(__FILE__)).'/images/Marvin.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg'), $api_result, $result_code);
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
        echo('<img src="data:'.$type.';base64,'.$payload.'" title="I\'m Very, Very Angry, Right Now!" alt="Marvin, not Lena" />');
    }
}
?>
