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

set_time_limit ( 3600 );

basalt_run_tests(126, 'ADVANCED XML PLACES TESTS PART 1', 'Verify that the "Location Fuzzing" works, and the basics of PUT are operational.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0126() {
    basalt_run_single_direct_test(126, 'PASS: Set a "Fuzz Factor" to a percentage of the locations', 'Log in, and alter a number of places to have a "fuzz factor."', 'dc_area_tests');
}

function basalt_test_0126($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we log in as the MD admin, and make 25% of the records "fuzzy."</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=MDAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $xml_object = simplexml_load_string($result);
            $places = json_decode(json_encode($xml_object))->changed_places->value;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_first_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_first_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                echo('</div>');
            echo('</div></div>');
        }
    }
    
    echo('<h3>Next, we do the same for the DC Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=DCAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $xml_object = simplexml_load_string($result);
            $places = json_decode(json_encode($xml_object))->changed_places->value;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_MD_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_MD_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                echo('</div>');
            echo('</div></div>');
        }
    }
    
    echo('<h3>Next, we do the same for the VA Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=VAAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $xml_object = simplexml_load_string($result);
            $places = json_decode(json_encode($xml_object))->changed_places->value;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_VA_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_VA_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                echo('</div>');
            echo('</div></div>');
        }
    }
    
    echo('<h3>Next, we do the same for the WVA Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=WVAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $xml_object = simplexml_load_string($result);
            $places = json_decode(json_encode($xml_object))->changed_places->value;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_WV_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_WV_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                echo('</div>');
            echo('</div></div>');
        }
    }
    
    echo('<h3>Next, we do the same for the DE Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=DEAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $xml_object = simplexml_load_string($result);
            $places = json_decode(json_encode($xml_object))->changed_places->value;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_DE_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_DE_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                echo('</div>');
            echo('</div></div>');
        }
    }
    
    echo('<h3>Now, let\'s see what we have (no login).</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0117_result_xml_no_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0117_result_xml_no_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    echo('<h3>Now, let\'s see what we have (MD login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=MDAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0117_result_xml_MD_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0117_result_xml_MD_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    
    echo('<h3>Now, let\'s see what we have (DC login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=DCAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0117_result_xml_DC_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0117_result_xml_DC_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    
    echo('<h3>Now, let\'s see what we have (VA login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=VAAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0117_result_xml_VA_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0117_result_xml_VA_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    
    echo('<h3>Now, let\'s see what we have (WVA login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=WVAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0117_result_xml_WVA_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0117_result_xml_WVA_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    
    echo('<h3>Now, let\'s see what we have (DE login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=DEAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0117_result_xml_DE_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0117_result_xml_DE_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    
    echo('<h3>Now, let\'s see what we have (main login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0117_result_xml_main_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0117_result_xml_main_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
}

// --------------------

function basalt_test_define_0127() {
    basalt_run_single_direct_test(127, 'PASS: Verify the "Fuzz Factor" As the MD Admin', 'We log in as the MD admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0127($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $fuzzed_ids = ['MD' => [2,6,10], 'DC' => [1452,1456,1460], 'VA' => [855,859,863], 'WV' => [1575,1579,1583], 'DE' => [1609,1613,1617]];
    
    $id_key  = strtoupper(substr($in_login, 0, 2));
    
    $result_code = '';
    echo('<h3>Log Into the Server as '.$in_login.':</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }

    echo('<h3>First, make sure that we can see the "raw" long/lat from the ones that we can see:</h3>');
    
    $places_try = [];

    echo('<p style="color:green">Trying ID List for '.$id_key.'. The numbers should all be different.</p>');
    foreach ($fuzzed_ids[$id_key] as $id) {
        for ($try = 0; $try < 3; $try++) {
            $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id, NULL, $api_result, $result_code);
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
                break;
            } else {
                $xml_object = simplexml_load_string($result);
                $place = json_decode(json_encode($xml_object))->value;
                $places_try[$id][] = $place->coords;
            }
        }
    }
    
    echo('<h3>Here\'s the Result:</h3><pre style="color:green">'.htmlspecialchars(print_r($places_try, true)).'</pre>');
    
    $places_try = [];

    echo('<p style="color:green">Trying ID List for '.$id_key.'. The numbers should all be different in \'coords,\' but all the same in \'real_coords.\'.</p>');
    foreach ($fuzzed_ids[$id_key] as $id) {
        for ($try = 0; $try < 3; $try++) {
            $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id.'/?show_details', NULL, $api_result, $result_code);
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
                break;
            } else {
                $xml_object = simplexml_load_string($result);
                $place = json_decode(json_encode($xml_object))->value;
                $places_try[$id]['coords'][] = $place->coords;
                if (isset($place->raw_latitude)) {
                    $places_try[$id]['real_coords'][] = $place->raw_latitude.','.$place->raw_longitude;
                }
            }
        }
    }
    echo('<h3>Here\'s the Result:</h3><pre style="color:green">'.htmlspecialchars(print_r($places_try, true)).'</pre>');
    
    echo('<h3>Log Out, So We Can Log In Again (Resets the clock):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    
    echo('<h3>Log Into the Server again as '.$in_login.':</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    foreach ($fuzzed_ids as $key => $value_array) {
        if ($key != $id_key) {
            $places_try = [];
            echo('<p style="color:green">Trying ID List for '.$key.'. The numbers should all be different in \'coords,\' and there should be no \'real_coords.\'.</p>');
            
            foreach ($fuzzed_ids[$key] as $id) {
                for ($try = 0; $try < 3; $try++) {
                    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/'.$id.'/?show_details', NULL, $api_result, $result_code);
                    if (isset($result_code) && $result_code && (200 != $result_code)) {
                        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
                        break;
                    } else {
                        $xml_object = simplexml_load_string($result);
                        $place = json_decode(json_encode($xml_object))->value;
                        $places_try[$id]['coords'][] = $place->coords;
                        if (isset($place->raw_latitude)) {
                            $places_try[$id]['real_coords'][] = $place->raw_latitude.','.$place->raw_longitude;
                        }
                    }
                }
            }
    
            echo('<h3>Here\'s the Result:</h3><pre style="color:green">'.htmlspecialchars(print_r($places_try, true)).'</pre>');
            break;
        }
    }
}

// --------------------

function basalt_test_define_0128() {
    basalt_run_single_direct_test(128, 'PASS: Verify the "Fuzz Factor" As the DC Admin', 'We log in as the DC admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'DCAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0128($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0127($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0129() {
    basalt_run_single_direct_test(129, 'PASS: Verify the "Fuzz Factor" As the VA Admin', 'We log in as the VA admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'VAAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0129($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0127($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0130() {
    basalt_run_single_direct_test(130, 'PASS: Verify the "Fuzz Factor" As the WVA Admin', 'We log in as the WVA admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'WVAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0130($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0127($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0131() {
    basalt_run_single_direct_test(131, 'PASS: Verify the "Fuzz Factor" As the DE Admin', 'We log in as the DE admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'DEAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0131($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0127($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0132() {
    basalt_run_single_direct_test(132, 'PASS: Change A Select Few Meetings', 'We log in as the DC Admin, and change just three meetings to point to the Lincoln Memorial.', 'dc_area_tests', 'DCAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0132($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we log in as the DC admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
 
    echo('<h3>List three places we\'ll be working on.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/1452,1456,1460/?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
 
    echo('<h3>Now, we will apply the same set of settings to all three:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/1452,1456,1460/?name=Changed+To+A+New+Name&lang=sv&longitude=-77.0502&latitude=38.8893&fuzz_factor=5&address_venue=Lincoln+Memorial&address_street_address=2+Lincoln+Memorial+Circle+NW&address_extra_information=Go+Up+The+Steps+And+Say+Hi+To+Abe&address_town=Washington+DC&address_county=DC&address_state=DC&address_postal_code=20037&address_nation=USA&tag8=HI&tag9=HOWAYA&child_ids=3,4,5,1470,1480,1490', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
 
    echo('<h3>See how that worked out.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/1452,1456,1460/?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0133() {
    basalt_run_single_direct_test(133, 'PASS: Change All of the Virginia Meetings to The Pentagon', 'We log in as the VA Admin, and change all visible meetings to point to the Pentagon.', 'dc_area_tests', 'VAAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0133($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we log in as the VA admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
 
    echo('<h3>List the places we\'ll be working on.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?show_details&writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0124_result_xml_VA_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0124_result_xml_VA_login_record_div\')" style="font-weight:bold">'.count($places).' places:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
 
    echo('<h3>Now, we will apply the same set of settings to all of our places:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?writeable&name=NA+In+The+Pentagon&lang=pt&longitude=-77.0563&latitude=38.8719&fuzz_factor=5&address_venue=The+Pentagon&address_street_address=1400+Defense+Pentagon&address_extra_information=Lots+Of+Brass&address_town=Arlington&address_county=Arlington&address_state=VA&address_postal_code=22202&tag8=NA&tag9=IN+DA+HOUSE&child_ids=853,854,855', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->changed_places->value;
        echo('<div  style="text-align:left;display:table"><div id="test_0124_results_VA_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0124_results_VA_record_div\')" style="font-weight:bold">'.count($places).' places were changed:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    echo('<h3>We\'ll need to log in again.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
 
    echo('<h3>See how that worked out.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?show_details&writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0124_result_xml_VA_login_record_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0124_result_xml_VA_login_record_2_div\')" style="font-weight:bold">'.count($places).' places:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0134() {
    basalt_run_single_direct_test(134, 'PASS: Using A Radius Search, Change Some Meetings, But Not All', 'We log in as the VA Admin, and change the meetings we can edit.', 'dc_area_tests', 'VAAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0134($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we log in as the VA admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
 
    echo('<h3>This is all the places that will be included in the search:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0125_result_xml_VA_login_1_xml_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0125_result_xml_VA_login_1_xml_record_div\')" style="font-weight:bold">'.count($places).' places:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
 
    echo('<h3>However, we\'ll only be able to modify these ones.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&show_details&writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0125_result_xml_VA_login_2_xml_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0125_result_xml_VA_login_2_xml_record_div\')" style="font-weight:bold">'.count($places).' places:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
 
    echo('<h3>Now, we will apply the same set of settings to the ones we can modify:</h3>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?writeable&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&name=NA+In+The+Pentagon&lang=pt&longitude=-77.0563&latitude=38.8719&fuzz_factor=5&address_venue=The+Pentagon&address_street_address=1400+Defense+Pentagon&address_extra_information=Lots+Of+Brass&address_town=Arlington&address_county=Arlington&address_state=VA&address_postal_code=22202&tag8=NA&tag9=IN+DA+HOUSE&child_ids=853,854,855', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->changed_places->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0125_result_xml_VA_login_3_xml_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0125_result_xml_VA_login_3_xml_record_div\')" style="font-weight:bold">'.count($places).' places were changed:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
 
    echo('<h3>Which leaves us with:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->value;
        echo('<div  style="text-align:left;display:table"><div id="test_display_0125_result_xml_VA_login_4_xml_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_display_0125_result_xml_VA_login_4_xml_record_div\')" style="font-weight:bold">'.count($places).' places:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}
?>
