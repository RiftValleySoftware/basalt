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

basalt_run_tests(74, 'ADVANCED XML PEOPLE TESTS (PART 1)', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0074() {
    basalt_run_single_direct_test(74, 'PASS: Add an Image Payload to an Existing User', 'We add a picture to \'norm\', and change the user name to \'Lena\' (Guess which picture we\'re uploading).', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0074($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?name=Lena&surname=Söderberg&given_name=Lena&middle_name=Whoah+Nellie!&nickname=Lenna+Sjööblom&prefix=Ms.&suffix=Scanner+Test+Model', Array('filepath' => dirname(dirname(__FILE__)).'/images/lena.jpg', 'type' => 'image/jpeg', 'name' => 'lena.jpg'), $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>AFTER:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $payload = (string)$xml_object->people->value->payload;
        $type = (string)$xml_object->people->value->payload_type;
        $xml_object->people->value->payload = '[LARGE PAYLOAD]';
        $result = $xml_object->asXML();
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Woah! Norm, have you been taking hormones?" alt="Lena, not Norm" style="width:256px;border-radius:2em;border:none" /></div>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Logged out!</pre>');
    }
}

// --------------------

function basalt_test_define_0075() {
    basalt_run_single_direct_test(75, 'PASS: Replace the Image Payload of an Existing User', 'We change the picture for \'norm\', and change the name again.', '', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0075($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $payload = (string)$xml_object->people->value->payload;
        $type = (string)$xml_object->people->value->payload_type;
        $xml_object->people->value->payload = '[LARGE PAYLOAD]';
        $result = $xml_object->asXML();
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Woah! Norm, have you been taking hormones?" alt="Lena, not Norm" style="width:256px;border-radius:2em;border:none" /></div>');
    }
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?name=Marvin&surname=Martian&given_name=Marvin&middle_name=D&nickname=Angry+Little+Fella&prefix=&suffix=', Array('filepath' => dirname(dirname(__FILE__)).'/images/Marvin.svg', 'type' => 'image/svg+xml', 'name' => 'Marvin.svg'), $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>AFTER:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $payload = (string)$xml_object->people->value->payload;
        $type = (string)$xml_object->people->value->payload_type;
        $xml_object->people->value->payload = '[LARGE PAYLOAD]';
        $result = $xml_object->asXML();
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="I\'m Very, Very Angry, Right Now!" alt="Marvin, not Lena" style="width:256px" /></div>');
    }
}

// --------------------

function basalt_test_define_0076() {
    basalt_run_single_direct_test(76, 'PASS: Delete the Image Payload, and a Lot of Information, from an Existing User', 'We remove the picture for \'norm\', change the name again, and delete a lot of the name information we previously set.', '', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0076($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $payload = (string)$xml_object->people->value->payload;
        $type = (string)$xml_object->people->value->payload_type;
        $xml_object->people->value->payload = '[LARGE PAYLOAD]';
        $result = $xml_object->asXML();
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="I\'m Very, Very Angry, Right Now!" alt="Marvin, not Lena" style="width:256px" /></div>');
    }
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?name=Norm&surname=&given_name=&middle_name=&nickname=&prefix=&suffix=&remove_payload', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Success!</h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/2?show_details', NULL, $api_result, $result_code);
    echo('<h3>AFTER:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0077() {
    basalt_run_single_direct_test(77, 'PASS: Delete a User (Failure)', 'We attempt to remove the user for \'krait\'; However, we are not cleared to remove \'krait\' (We only have read access), so it won\'t happen.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0077($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/3?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Note that there are no users deleted:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/3', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/3', NULL, $api_result, $result_code);
    echo('<h3>AFTER. Like a Bad Penny, It\'s Still Here:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0078() {
    basalt_run_single_direct_test(78, 'PASS: Delete a User (Success)', 'We remove an unaffiliated user, for which we have edit rights (It\'s a \'1\', which means that anyone can write/delete it).', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0078($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/6?show_details', NULL, $api_result, $result_code);
    echo('<h3>BEFORE. Note that \'writeable\' is Set. That Means We Are Allowed to Delete It:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>We will see a record of us deleting the user:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/6', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/6', NULL, $api_result, $result_code);
    echo('<h3>AFTER. It\'s Gone:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0079() {
    basalt_run_single_direct_test(79, 'PASS: Create a Generic User (No Login Associated)', 'We log in as a manager, and create a new user, without any extra frills (or login instance).', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0079($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Welcome to our new user:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0080() {
    basalt_run_single_direct_test(80, 'PASS: Create a Generic User and Login Pair', 'We log in as a manager, and create a new user and login, without any extra frills.', 'user_tests', 'asp', '', 'CoreysGoryStory');
}

function basalt_test_0080($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    echo('<h3>Welcome to our new user:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/xml/people/people/?login_id=ColonelSanders', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

?>
