<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC/The Great Rift Valley Software Company
    
    LICENSE:
    
    MIT License
    
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
    modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
    CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


    Little Green Viper Software Development: https://littlegreenviper.com
*/
// ------------------------------ MAIN CODE -------------------------------------------

require_once(dirname(dirname(__FILE__)).'/run_basalt_tests.php');

set_time_limit(3600);

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

function basalt_tests_display_things_result_xml($in_result) {
    $xml_object = simplexml_load_string($in_result);
    $things = $xml_object->value;
    foreach ($things as $thing) {
        $payload = (string)$thing->payload;
        $type = (string)$thing->payload_type;
        $thing->payload = '[LARGE PAYLOAD]';
        echo('<pre style="color:green">'.prettify_xml($thing->asXML()).'</pre>');
        $type_header = explode('/', $type);
        $type_trailer = explode(';', $type_header[1]);
        switch ($type_header[0]) {
            case 'image':
                if ('tiff' != $type_trailer[0]) {
                    echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="'.$thing->name.'" alt="'.$thing->name.'" style="width:256px" /></div>');
                } else {
                    echo('<h4 style="color:green">'.$thing->name.' ('.$type.')</h3>');
                }
                break;
        
            default:
                echo('<h4 style="color:green">'.$thing->name.' ('.$type.')</h3>');
                break;
        }
    }
}

basalt_run_tests(174, 'BASIC XML THING TESTS', 'NOTE: These tests may give you problems in MySQL! The big data items seem to stress MySQL. It may be necessary to run them with Postgres.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0174() {
    basalt_run_single_direct_test(174, 'LOAD UP', 'Log in as "God," and create a set of "things" to be tested.', 'dc_area_tests');
}

function basalt_test_0174($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    
    $result_code = '';
    $things = basalt_tests_read_things();
    
    foreach ($things as $thing) {
        echo('<h3>Create a new thing for '.$thing['name'].'.</h3>');
        $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
        }
        
        $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?name='.urlencode($thing['name']).'&key='.$thing['key'], $thing, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
        }
        
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    }
}

// --------------------

function basalt_test_define_0175() {
    basalt_run_single_direct_test(175, 'BIG HONKERS', 'We retrieve items individually.', 'things_tests');
}

function basalt_test_0175($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $things = basalt_tests_read_things();
    
    echo('<h3>First, we get them as individual resource IDs:</h3>');
    foreach ($things as $thing) {
        $st1 = microtime(true);
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$thing['db_index'].'?show_details', NULL, NULL, $result_code);
        $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            basalt_tests_display_things_result_xml($result);
        }
    }
    
    echo('<h3>Next, we get them as individual resource keys:</h3>');
    foreach ($things as $thing) {
        $st1 = microtime(true);
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$thing['key'].'?show_details', NULL, NULL, $result_code);
        $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            basalt_tests_display_things_result_xml($result);
        }
    }
    
    echo('<h3>Finally, we get them as individual resource keys, but ask only for raw data:</h3>');
    foreach ($things as $thing) {
        $st1 = microtime(true);
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$thing['key'].'?data_only', NULL, NULL, $result_code);
        $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            } else {
                echo("<h4>The test took $fetchTime seconds to complete.</h4>");
                basalt_tests_display_things_result_raw($result);
            }
        }
    }
}

// --------------------

function basalt_test_define_0176() {
    basalt_run_single_direct_test(176, 'BIG HONKERS -THE SEQUEL', 'We retrieve items as groups.', 'things_tests');
}

function basalt_test_0176($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    
    $things = basalt_tests_read_things();
    
    $things_id_list = [array_reduce($things, function ($prev, $current) { return $prev ? $prev.','.$current['db_index'] : $current['db_index']; })];
    $things_id_list[] = array_reduce($things, function ($prev, $current) { return $prev ? $prev.','.$current['key'] : $current['key']; });
    
    echo('<h3>First, we get them as ID-selected and key-selected resources:</h3>');
    
    foreach ($things_id_list as $things_list) {
        $st1 = microtime(true);
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$things_list.'?show_details', NULL, NULL, $result_code);
        $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            basalt_tests_display_things_result_xml($result);
        }
    }
    
    echo('<h3>Now, we try it again, but this time, as data-only, so only the first item will be returned:</h3>');
    
    foreach ($things_id_list as $things_list) {
        $st1 = microtime(true);
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$things_list.'?data_only', NULL, NULL, $result_code);
        $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            basalt_tests_display_things_result_raw($result);
        }
    }
}
?>
