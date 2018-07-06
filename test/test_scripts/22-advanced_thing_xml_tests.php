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

function basalt_tests_display_thing_result_xml($thing) {
    if (isset($thing->payload)) {
        $payload = (string)$thing->payload;
        $type = (string)$thing->payload_type;
        $type_header = explode('/', $type);
        $type_trailer = explode(';', $type_header[1]);
        switch ($type_header[0]) {
            case 'image':
                if ('tiff' != $type_trailer[0]) {
                    echo('<div style="text-align:left;margin:1em"><img src="data:'.$type.','.$payload.'" title="'.$thing->name.'" alt="'.$thing->name.'" style="width:256px" /></div>');
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

function basalt_tests_display_things_result_xml($in_result) {
    $xml_object = simplexml_load_string($in_result);
    if (isset($xml_object)) {
        $json_version = json_decode(json_encode($xml_object));
        if (isset($json_version)) {
            $things_value = isset($json_version->value) ? $json_version->value : NULL;
            $changed_things_value = isset($json_version->changed_things) ? $json_version->changed_things : NULL;
            echo('<pre style="color:green">'.prettify_xml($in_result).'</pre>');
            if (isset($changed_things_value)) {
                foreach ($changed_things_value as $changed_thing) {
                    echo('<h4 style="color:green">BEFORE:</h3>');
                    basalt_tests_display_thing_result_xml($changed_thing->before);
                    echo('<h4 style="color:green">AFTER:</h3>');
                    basalt_tests_display_thing_result_xml($changed_thing->after);
                }
            } elseif (isset($things_value) && is_array(isset($things_value)) && count(isset($things_value))) {
                foreach ($things_value as $thing) {
                    basalt_tests_display_thing_result_xml($thing);
                }
            } else {
                basalt_tests_display_thing_result_xml($things_value);
            }
        }
    }
}

basalt_run_tests(177, 'ADVANCED XML THING TESTS', 'NOTE: These tests may give you problems in MySQL! The big data items seem to stress MySQL. It may be necessary to run them with Postgres.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0177() {
    basalt_run_single_direct_test(177, 'EDIT THING INFORMATION', 'Log in as the "God" login, and change the name, key, tags, payload, children, location and fuzziness of a thing.', 'things_tests', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0177($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $things = basalt_tests_read_things();
    
    echo("<h3>This Is What We're Starting With:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$things[0]['key'].','.$things[1]['key'].'?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
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
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$things[0]['key'].'?key=WasItWorthIt&name=Was+It+Really+Worth+It&child_ids=2,3,4,5,6&latitude=39.745&longitude=-75.552&fuzz_factor=5&description=Worth+Enough,+by+Radoxist&tag2=futuristic&tag3=gleaming+city&tag4=primitive+village&tag5=deviantart&tag6=radoxist&tag7=science+fiction&tag8=3d+render&tag9=', $things[1], $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Also Add A Tag to the Next Image:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$things[1]['key'].'?description=Another+World,+by+Radoxist&tag2=dystopia&tag3=dark+city&tag4=wasteland&tag5=deviantart&tag6=radoxist&tag7=science+fiction&tag8=3d+render&tag9=', $things[1], $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Log Out:</h3>");
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    }

    echo("<h3>Let's See What We Have Now:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/'.$things[0]['key'].'?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Whoops. We Changed the Key (Note that we won't see the raw_latitude/longitude, as we are logged out):</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/WasItWorthIt?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Call again, just to make sure that we're fuzzy:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/WasItWorthIt?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
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
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/xml/things/1732?', $things[0], $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Log Out:</h3>");
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    }
    
    echo("<h3>What's the frequency, Kenneth?:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/1732?show_details', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
}

// --------------------

function basalt_test_define_0178() {
    basalt_run_single_direct_test(178, 'DESCRIPTION SEARCH TEST', 'No need to log in. We will look for the images we just edited via the description tag.', '');
}

function basalt_test_0178($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    
    echo("<h3>First, Search for the Exact Description String:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_description=Worth+Enough,+by+Radoxsist', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Now, let's try the same string, with a wildcard:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_description=Worth+Enough,%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Again, but this time, with the wildcard in front (We'll get two responses, now):</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_description=%xist', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>This time, with the wildcard in the middle (back to one response):</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_description=w%st', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
}

// --------------------

function basalt_test_define_0179() {
    basalt_run_single_direct_test(179, 'TAG SEARCH TEST', 'No need to log in. We will look for the images we just edited via the various tags.', '');
}

function basalt_test_0179($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    
    echo("<h3>First, Search for the Exact Tag 2 String:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag2=futuristic', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag2=dystopia', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Next, Use wildcards in the Tag 2 String:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag2=%t%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag2=%y%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag2=F%', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag2=%a', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Try an exact string for Tag 3:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag3=city', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Try wildcards for Tag 3:</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag3=%city', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
    
    echo("<h3>Look specifically for an empty Tag 9. Note that we only get the two items we edited, despite the fact that all of them have a NULL tag9. 'Empty' is different from 'NULL.':</h3>");
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/things/?show_details&search_tag9=', NULL, NULL, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        basalt_tests_display_things_result_xml($result);
    }
}
?>
