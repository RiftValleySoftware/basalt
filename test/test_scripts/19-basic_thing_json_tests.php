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

basalt_run_tests(171, 'BASIC JSON THING TESTS', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0171() {
//     basalt_run_single_direct_test(171, 'LOAD UP', 'Log in as "God," and create a set of "things" to be tested.', 'dc_area_tests');
}

function basalt_test_0171($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    function read_things() {
        $things_files = [];
        foreach (new DirectoryIterator(dirname(dirname(__FILE__)).'/things') as $fileInfo) {
            if ('.' != substr($fileInfo->getBasename(), 0, 1)) {
                $file['index'] = intval(substr($fileInfo->getBasename(), 0, 2));
                $file['filepath'] = $fileInfo->getPathname();
                $file['name'] = ucwords(str_replace('-', ' ', substr($fileInfo->getBasename(), 3, -4)));
                
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file['type'] = finfo_file($finfo, $file['filepath']);
                
                $things_files[] = $file;
            }
        }
        function sortee($a, $b){
            return ($a['index'] < $b['index']) ? -1 : (($a['index'] > $b['index']) ? 1 : 0);
        }
        usort($things_files, 'sortee');
        
        return $things_files;
    }
    
    $result_code = '';
    $things = read_things();
    
    foreach ($things as $thing) {
        unset($thing['index']);
        echo('<h3>Create a new thing for '.$thing['name'].'.</h3>');
        $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
        }
        
        $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/things/?name='.urlencode($thing['name']).'&key=basalt-test-0171:+'.urlencode($thing['name']), $thing, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
        }
        
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    }
}

// --------------------

function basalt_test_define_0172() {
    basalt_run_single_direct_test(172, 'BIG HONKERS', '', 'things_tests', 'MainAdmin', 'CoreysGoryStory');
}

function basalt_test_0172($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $api_result = NULL;
    
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/1733?show_details', NULL, $api_result, $result_code);
    echo('<h3>AFTER:</h3>');
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $payload = $json_object->things[0]->payload;
        $type = $json_object->things[0]->payload_type;
        $json_object->things[0]->payload = '[LARGE PAYLOAD]';
        $json_object = json_encode($json_object);
        echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Worth Enough" alt="Worth Enough" style="width:256px" /></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">Logged out!</pre>');
    }
}

?>
