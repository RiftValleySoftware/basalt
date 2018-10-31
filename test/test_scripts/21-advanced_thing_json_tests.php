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
                echo('<div style="text-align:left;margin:1em"><img src="data:'.$type.','.$payload.'" title="'.$in_thing->name.'" alt="'.$in_thing->name.'" style="width:256px" /></div>');
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

function basalt_test_define_0174() {
    basalt_run_single_direct_test(174, 'EDIT THING INFORMATION', 'Log in as the "God" login, and change the name, key, tags, payload, children, location and fuzziness of a thing.', 'things_tests', 'admin', '', CO_Config::god_mode_password());
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
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$things[0]['key'].'?key=WasItWorthIt&name=Was+It+Really+Worth+It&child_ids=2,3,4,5,6&latitude=39.745&longitude=-75.552&fuzz_factor=5&description=Worth+Enough,+by+Radoxist&tag2=futuristic&tag3=gleaming+city&tag4=primitive+village&tag5=deviantart&tag6=radoxist&tag7=science+fiction&tag8=3d+render&tag9=', $things[1], $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Also Add A Tag to the Next Image:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$things[1]['key'].'?description=Another+World,+by+Radoxist&tag2=dystopia&tag3=dark+city&tag4=wasteland&tag5=deviantart&tag6=radoxist&tag7=science+fiction&tag8=3d+render&tag9=', $things[1], $api_result, $result_code);
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
    
    echo("<h3>Wrong Image. Let's put the old one back, but this time, we'll use the ID:</h3>");
    echo("<h3>Log In:</h3>");
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo("<h3>Make the Changes:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/things/1732?', $things[0], $api_result, $result_code);
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
    
    echo("<h3>What's the frequency, Kenneth?:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/1732?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
}

// --------------------

function basalt_test_define_0175() {
    basalt_run_single_direct_test(175, 'DESCRIPTION SEARCH TEST', 'No need to log in. We will look for the images we just edited via the description tag.', '');
}

function basalt_test_0175($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    
    echo("<h3>First, Search for the Exact Description String:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_description=Worth+Enough,+by+Radoxist', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Now, let's try the same string, with a wildcard:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_description=Worth+Enough,%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Again, but this time, with the wildcard in front (We'll get two responses, now):</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_description=%xist', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>This time, with the wildcard in the middle (back to one response):</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_description=w%st', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
}

// --------------------

function basalt_test_define_0176() {
    basalt_run_single_direct_test(176, 'TAG SEARCH TEST', 'No need to log in. We will look for the images we just edited via the various tags.', '');
}

function basalt_test_0176($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    
    echo("<h3>First, Search for the Exact Tag 2 String:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag2=futuristic', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag2=dystopia', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Next, Use wildcards in the Tag 2 String:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag2=%t%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag2=%y%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag2=F%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag2=%a', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Try an exact string for Tag 3:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag3=city', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
    
    echo("<h3>Try wildcards for Tag 3:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/?show_details&search_tag3=%city', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_json($result);
    }
}
?>
