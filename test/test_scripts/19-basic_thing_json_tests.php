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

basalt_run_tests(171, 'BASIC JSON THING TESTS', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0171() {
//     basalt_run_single_direct_test(171, 'LOAD UP', 'Log in as "God," and create a set of "things" to be tested.', 'dc_area_tests');
}

function basalt_test_0171($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    
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
        
        $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/things/?name='.urlencode($thing['name']).'&key='.$thing['key'], $thing, $api_result, $result_code);
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
    basalt_run_single_direct_test(172, 'BIG HONKERS', 'We retrieve items individually.', 'things_tests');
}

function basalt_test_0172($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $api_result = NULL;
    
    $things = basalt_tests_read_things();
    
    echo('<h3>First, we get them as individual resource IDs:</h3>');
    foreach ($things as $thing) {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$thing['db_index'].'?show_details', NULL, $api_result, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
        } else {
            $json_object = json_decode($result);
            $payload = $json_object->things[0]->payload;
            $type = $json_object->things[0]->payload_type;
            $json_object->things[0]->payload = '[LARGE PAYLOAD]';
            $json_object = json_encode($json_object);
            echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
            $type_header = explode('/', $type);
            $type_trailer = explode(';', $type_header[1]);
            switch ($type_header[0]) {
                case 'image':
                    if ('tiff' != $type_trailer[0]) {
                        echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="'.$thing['name'].'" alt="'.$thing['name'].'" style="width:256px" /></div>');
                    } else {
                        echo('<h4 style="color:green">'.$thing['name'].' ('.$type.')</h3>');
                    }
                    break;
                
                default:
                    echo('<h4 style="color:green">'.$thing['name'].' ('.$type.')</h3>');
                    break;
            }
        }
    }
    
//     echo('<h3>Next, we get them as individual resource keys:</h3>');
//     foreach ($things as $thing) {
//         $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$thing['key'].'?show_details', NULL, $api_result, $result_code);
//         if (isset($result_code) && $result_code && (200 != $result_code)) {
//             echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
//         } else {
//             $json_object = json_decode($result);
//             $payload = $json_object->things[0]->payload;
//             $type = $json_object->things[0]->payload_type;
//             $json_object->things[0]->payload = '[LARGE PAYLOAD]';
//             $json_object = json_encode($json_object);
//             echo('<pre style="color:green">'.prettify_json($json_object).'</pre>');
//             $type_header = explode('/', $type);
//             $type_trailer = explode(';', $type_header[1]);
//             switch ($type_header[0]) {
//                 case 'image':
//                     if ('tiff' != $type_trailer[0]) {
//                         echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="'.$thing['name'].'" alt="'.$thing['name'].'" style="width:256px" /></div>');
//                     } else {
//                         echo('<h4 style="color:green">'.$thing['name'].' ('.$type.')</h3>');
//                     }
//                     break;
//                 
//                 default:
//                     echo('<h4 style="color:green">'.$thing['name'].' ('.$type.')</h3>');
//                     break;
//             }
//         }
//     }
//     
//     echo('<h3>Finally, we get them as individual resource keys, but ask only for raw data:</h3>');
//     foreach ($things as $thing) {
//         $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/things/'.$thing['key'].'?data_only', NULL, $api_result, $result_code);
//         if (isset($result_code) && $result_code && (200 != $result_code)) {
//             echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
//         } else {
//             $payload_pair = explode(',', $result);
//             $type = $payload_pair[0];
//             $payload = $payload_pair[1];
// 
//             $type_header = explode('/', $type);
//             $type_trailer = explode(';', $type_header[1]);
//             switch ($type_header[0]) {
//                 case 'image':
//                     if ('tiff' != $type_trailer[0]) {
//                         echo('<div style="text-align:center;margin:1em"><img src="data:'.$type.','.$payload.'" title="'.$thing['name'].'" alt="'.$thing['name'].'" style="width:256px" /></div>');
//                     } else {
//                         echo('<h4 style="color:green">'.$thing['name'].' ('.$type.')</h3>');
//                     }
//                     break;
//                 
//                 default:
//                     echo('<h4 style="color:green">'.$thing['name'].' ('.$type.')</h3>');
//                     break;
//             }
//         }
//     }
}

?>
