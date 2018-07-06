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

function basalt_tests_read_things() {
    $things_files = [];

    foreach (new DirectoryIterator(dirname(dirname(__FILE__)).'/things') as $fileInfo) {
        if ('.' != substr($fileInfo->getBasename(), 0, 1)) {
            $file['index'] = intval(substr($fileInfo->getBasename(), 0, 2));
            $file['filepath'] = $fileInfo->getPathname();
            $file['name'] = ucwords(str_replace('-', ' ', substr($fileInfo->getBasename(), 3, -4)));
            $file['key'] = 'basalt-test-0171:+'.urlencode($file['name']);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file['type'] = finfo_file($finfo, $file['filepath']);
            
            $things_files[] = $file;
        }
    }
    
    usort($things_files, function ($a, $b){ return ($a['index'] < $b['index']) ? -1 : (($a['index'] > $b['index']) ? 1 : 0); });
    
    for ($i = 0; $i < count($things_files); $i++) {
        $things_files[$i]['db_index'] = 1732 + $i;
    }
    
    return $things_files;
}

function basalt_tests_display_things_result_raw($in_result) {
    $payload_pair = explode(',', $in_result);
    $type = $payload_pair[0];
    $payload = $payload_pair[1];

    $type_header = explode('/', $type);
    $type_trailer = explode(';', $type_header[1]);
    switch ($type_header[0]) {
        case 'image':
            if ('tiff' != $type_trailer[0]) {
                echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="Some Image" alt="Some Image" style="width:256px" /></div>');
            } else {
                echo('<h4 style="color:green">'.$type.'</h3>');
            }
            break;
        
        default:
            echo('<h4 style="color:green">'.$type.'</h3>');
            break;
    }
}

function basalt_tests_display_thing_result_json($in_thing) {
    $payload = $in_thing->payload;
    $type = $in_thing->payload_type;
    $in_thing->payload = '[LARGE PAYLOAD]';
    $json_object2 = json_encode($in_thing);
    echo('<pre style="color:green">'.prettify_json($json_object2).'</pre>');
    $type_header = explode('/', $type);
    $type_trailer = explode(';', $type_header[1]);
    switch ($type_header[0]) {
        case 'image':
            if ('tiff' != $type_trailer[0]) {
                echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="'.$in_thing->name.'" alt="'.$in_thing->name.'" style="width:256px" /></div>');
            } else {
                echo('<h4 style="color:green">'.$in_thing->name.' ('.$type.')</h3>');
            }
            break;

        default:
            echo('<h4 style="color:green">'.$in_thing->name.' ('.$type.')</h3>');
            break;
    }
}

function basalt_tests_display_things_result_json($in_result) {
    $json_object = json_decode($in_result);
    
    if (isset($json_object->things->changed_things)) {
        foreach ($json_object->things->changed_things as $changed_thing) {
            echo('<h4 style="color:green">BEFORE:</h3>');
            basalt_tests_display_thing_result_json($changed_thing->before);
            echo('<h4 style="color:green">AFTER:</h3>');
            basalt_tests_display_thing_result_json($changed_thing->after);
        }
    } else {
        foreach ($json_object->things as $thing) {
            basalt_tests_display_thing_result_json($thing);
        }
    }
}

basalt_run_tests(174, 'ADVANCED JSON THING TESTS', 'NOTE: These tests may give you problems in MySQL! The big data items seem to stress MySQL. It may be necessary to run them with Postgres.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

// --------------------

function basalt_test_define_0174() {
    basalt_run_single_direct_test(174, 'EDIT THING INFORMATION', 'Log in as the "God" login, and change the name, key, payload, children, location and fuzziness of a thing.', 'things_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0174($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $things = basalt_tests_read_things();
    
    echo("<h3>This Is What We're Starting With:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$things[0]['key'].'?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Log In:</h3>");
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo("<h3>Make the Changes:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$things[0]['key'].'?key=WasItWorthIt&name=Was+It+Really+Worth+It&child_ids=2,3,4,5,6&latitude=39.745&longitude=-75.552&fuzz_factor=5', $things[1], $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Log Out:</h3>");
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    }
    
    echo("<h3>Let's See What We Have Now:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$things[0]['key'].'?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Whoops. We Changed the Key (Note that we won't see the raw_latitude/longitude, as we are logged out):</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/WasItWorthIt?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Call again, just to make sure that we're fuzzy:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/WasItWorthIt?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
}
?>
