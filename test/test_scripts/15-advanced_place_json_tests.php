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

basalt_run_tests(117, 'ADVANCED JSON PLACES TESTS PART 1', 'Verify that the "Location Fuzzing" works, and the basics of PUT are operational.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0117() {
    basalt_run_single_direct_test(117, 'PASS: Set a "Fuzz Factor" to a percentage of the locations', 'Log in, and alter a number of places to have a "fuzz factor."', 'dc_area_tests');
}

function basalt_test_0117($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we log in as the MD admin, and make 25% of the records "fuzzy."</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=MDAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $places = json_decode($result)->places->changed_places;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_first_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_first_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $places = json_decode($result)->places->changed_places;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_MD_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_MD_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $places = json_decode($result)->places->changed_places;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_VA_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_VA_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $places = json_decode($result)->places->changed_places;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_WV_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_WV_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?writeable', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        
        $modify_these = [];
        
        echo('<p style="color:green; font-weight:bold;font-size:large">There were '.count($places).' places returned.</p>');
        
        $fuzz_factor = 10.0;
        
        for ($counter = 0; $counter < count($places); $counter += 4) {
            $place = $places[$counter];
            $modify_these[] = $place->id;
        }
        
        $id_list = implode(',', $modify_these);
        
        $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id_list.'?fuzz_factor=10', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $places = json_decode($result)->places->changed_places;
            echo('<div  style="text-align:left;display:table"><div id="test_0117_DE_change_record_div" class="inner_closed">');
                echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_DE_change_record_div\')" style="font-weight:bold">'.count($places).' places were modified:</a></h3>');
                echo('<div class="main_div inner_container">');
                    echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                echo('</div>');
            echo('</div></div>');
        }
    }
    
    echo('<h3>Now, let\'s see what we have (no login).</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        echo('<div  style="text-align:left;display:table"><div id="test_0117_result_no_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_result_no_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        echo('<div  style="text-align:left;display:table"><div id="test_0117_result_MD_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_result_MD_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
    
    echo('<h3>Now, let\'s see what we have (DC login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=DCAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        echo('<div  style="text-align:left;display:table"><div id="test_0117_result_DC_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_result_DC_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
    
    echo('<h3>Now, let\'s see what we have (VA login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=VAAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        echo('<div  style="text-align:left;display:table"><div id="test_0117_result_VA_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_result_VA_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
    
    echo('<h3>Now, let\'s see what we have (WVA login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=WVAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        echo('<div  style="text-align:left;display:table"><div id="test_0117_result_WVA_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_result_WVA_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
    
    echo('<h3>Now, let\'s see what we have (DE login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=DEAdmin&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        echo('<div  style="text-align:left;display:table"><div id="test_0117_result_DE_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_result_DE_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    echo('<h3>Log Out, So We Can Log In Again:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Successful Logout!</pre>');
    }
    
    echo('<h3>Now, let\'s see what we have (main login).</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places?show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places;
        echo('<div  style="text-align:left;display:table"><div id="test_0117_result_main_login_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0117_result_main_login_record_div\')" style="font-weight:bold">'.count($places).' places are available:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
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

function basalt_test_define_0118() {
    basalt_run_single_direct_test(118, 'PASS: Verify the "Fuzz Factor" As the MD Admin', 'We log in as the MD admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0118($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $fuzzed_ids = ['MD' => [2,6,10,14,18], 'DC' => [1452,1456,1460,1464,1468], 'VA' => [855,859,863,867,871], 'WV' => [1575,1579,1583,1587,1591], 'DE' => [1609,1613,1617,1621,1625]];
    
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
            $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id, NULL, $api_result, $result_code);
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
                break;
            } else {
                $place = json_decode($result)->places[0];
                $places_try[$id][] = $place->coords;
            }
        }
    }
    
    echo('<h3>Here\'s the Result:</h3><pre style="color:green">'.htmlspecialchars(print_r($places_try, true)).'</pre>');
    
    $places_try = [];

    echo('<p style="color:green">Trying ID List for '.$id_key.'. The numbers should all be different in \'coords,\' but all the same in \'real_coords.\'.</p>');
    foreach ($fuzzed_ids[$id_key] as $id) {
        for ($try = 0; $try < 3; $try++) {
            $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id.'/?show_details', NULL, $api_result, $result_code);
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
                break;
            } else {
                $place = json_decode($result)->places[0];
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
                    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.$id.'/?show_details', NULL, $api_result, $result_code);
                    if (isset($result_code) && $result_code && (200 != $result_code)) {
                        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
                        break;
                    } else {
                        $place = json_decode($result)->places[0];
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

function basalt_test_define_0119() {
    basalt_run_single_direct_test(119, 'PASS: Verify the "Fuzz Factor" As the DC Admin', 'We log in as the DC admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'DCAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0119($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0118($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0120() {
    basalt_run_single_direct_test(120, 'PASS: Verify the "Fuzz Factor" As the VA Admin', 'We log in as the VA admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'VAAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0120($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0118($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0121() {
    basalt_run_single_direct_test(121, 'PASS: Verify the "Fuzz Factor" As the WVA Admin', 'We log in as the WVA admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'WVAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0121($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0118($in_login, $in_hashed_password, $in_password);
}

// --------------------

function basalt_test_define_0122() {
    basalt_run_single_direct_test(122, 'PASS: Verify the "Fuzz Factor" As the DE Admin', 'We log in as the DE admin, and make sure that the IDs we expect to be fuzzed, are, and that they behave like fuzzed IDs.', '', 'DEAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0122($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    basalt_test_0118($in_login, $in_hashed_password, $in_password);
}
?>
