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

basalt_run_tests(135, 'ADVANCED JSON PLACES TESTS PART 2', 'Verify POST and DELETE.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0135() {
    basalt_run_single_direct_test(135, 'PASS: Create A New Location', 'Log in, and create a new simple location with no data at all.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0135($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=MDAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Create a new empty place:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/places/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0136() {
    basalt_run_single_direct_test(136, 'PASS: Create A New Location At the Lincoln Memorial', 'Log in, and create a new location, loaded up as the Lincoln Memorial.', 'dc_area_tests', 'DCAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0136($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the DC Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=MDAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Create our nice place:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/places/?read_token=1&write_token=1&name=The+Lincoln+Memorial&lang=en&longitude=-77.0502&latitude=38.8893&fuzz_factor=5&address_venue=Lincoln+Memorial&address_street_address=2+Lincoln+Memorial+Circle+NW&address_extra_information=Go+Up+The+Steps+And+Say+Hi+To+Abe&address_town=Washington+DC&address_county=DC&address_state=DC&address_postal_code=20037&address_nation=USA&tag8=HI&tag9=HOWAYA&child_ids=3,4,5,1470,1480,1490', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}
?>
