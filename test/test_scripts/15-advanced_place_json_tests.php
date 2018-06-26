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

basalt_run_tests(117, 'ADVANCED JSON PLACES TESTS', 'Work on POST, PUT and DELETE, in addition to GET.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

// --------------------

function basalt_test_define_0117() {
    basalt_run_single_direct_test(117, 'PASS: Set a "Fuzz Factor" to 25% of the places (With Login)', 'Log in, and alter a number of places to have a "fuzz factor."', 'dc_area_tests');
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
            echo('</div>');
        echo('</div></div>');
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
            echo('</div>');
        echo('</div></div>');
    }
}

?>
