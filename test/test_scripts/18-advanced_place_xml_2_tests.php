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

basalt_run_tests(150, 'ADVANCED XML PLACES TESTS PART 2', 'Verify POST, DELETE and Special Functions.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0150() {
    basalt_run_single_direct_test(150, 'PASS: Create A New Location', 'Log in, and create a new simple location with no data at all.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0150($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Create a new empty place:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/xml/places/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0151() {
    basalt_run_single_direct_test(151, 'PASS: Create A New Location At the Lincoln Memorial', 'Log in, and create a new location, loaded up as the Lincoln Memorial.', 'dc_area_tests', 'DCAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0151($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the DC Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Create our nice place:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?read_token=1&write_token=1&name=The+Lincoln+Memorial&lang=en&longitude=-77.0502&latitude=38.8893&fuzz_factor=5&address_venue=Lincoln+Memorial&address_street_address=2+Lincoln+Memorial+Circle+NW&address_extra_information=Go+Up+The+Steps+And+Say+Hi+To+Abe&address_town=Washington+DC&address_county=DC&address_state=DC&address_postal_code=20037&address_nation=USA&tag8=HI&tag9=HOWAYA&child_ids=3,4,5,1470,1480,1725,1727', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0152() {
    basalt_run_single_direct_test(152, 'PASS: Delete One Single Place.', 'Log in, and delete one single location.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0152($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/xml/places/2', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0153() {
    basalt_run_single_direct_test(153, 'PASS: Delete Three Places.', 'Log in, and delete three individual locations.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0153($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/xml/places/2,3,4', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0154() {
    basalt_run_single_direct_test(154, 'PASS: Delete Every Place We Can See.', 'Log in, and delete all the places that we can see.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0154($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/xml/places/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0154_first_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0154_first_change_record_div\')" style="font-weight:bold">See What Thou Hast Wrought:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    echo('<h3>We\'ll Need to Log In Again.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Make sure they are gone:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0154_second_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0154_second_change_record_div\')" style="font-weight:bold">Are They Gone?</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0155() {
    basalt_run_single_direct_test(155, 'PASS: Delete Some Places In A Radius Search.', 'Log in, and delete a few of the places returned in a radius search.', 'dc_area_tests', 'VAAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0155($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the VA Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_radius=10&search_longitude=-77.063776&search_latitude=38.894926', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0155_first_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0155_first_change_record_div\')" style="font-weight:bold">See What Thou Hast Wrought:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    echo('<h3>Make sure they are gone:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_radius=10&search_longitude=-77.063776&search_latitude=38.894926', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0155_second_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0155_second_change_record_div\')" style="font-weight:bold">Are They Gone?</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0156() {
    basalt_run_single_direct_test(156, 'PASS: Test Pagination.', 'Do not log in, and do a big search, looking for various pages.', 'dc_area_tests');
}

function basalt_test_0156($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, Look For Everything At Once:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->results->value;
        echo('<div  style="text-align:left;display:table"><div id="test_0156_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0156_results_1_div\')" style="font-weight:bold">The Whole Enchilada ('.count($places).'):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Next, Paginate in Pages of Twenty:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $xml_object = simplexml_load_string($result);
            $places = isset(json_decode(json_encode($xml_object))->results->value) ? json_decode(json_encode($xml_object))->results->value : NULL;
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0156_results_1_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0156_results_1_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                    echo('</div>');
                echo('</div></div>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Do it again, but this time with a location/radius search:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $xml_object = simplexml_load_string($result);
            $places = isset(json_decode(json_encode($xml_object))->results->value) ? json_decode(json_encode($xml_object))->results->value : NULL;
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0156_results_2_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0156_results_2_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                    echo('</div>');
                echo('</div></div>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
}

// --------------------

function basalt_test_define_0157() {
    basalt_run_single_direct_test(157, 'PASS: Test Count', 'Don\'t log in, and get back just a count.', 'dc_area_tests');
}

function basalt_test_0157($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, Look For Everything At Once:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_count_only', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Next, Paginate in Pages of Twenty:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_count_only&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $xml_object = simplexml_load_string($result);
            $places = isset(json_decode(json_encode($xml_object))->results->count) ? json_decode(json_encode($xml_object))->results->count : NULL;
            if (!isset($places) || !intval($places)) {
                break;
            } else {
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Do it again, but this time with a location/radius search:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_count_only&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $xml_object = simplexml_load_string($result);
            $places = isset(json_decode(json_encode($xml_object))->results->count) ? json_decode(json_encode($xml_object))->results->count : NULL;
            if (!isset($places) || !intval($places)) {
                break;
            } else {
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
}

// --------------------

function basalt_test_define_0158() {
    basalt_run_single_direct_test(158, 'PASS: Get Just The IDs', 'We don\'t log in, and do the same searches as above.', 'dc_area_tests');
}

function basalt_test_0158($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, Look For Everything At Once:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_ids_only', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $xml_object = simplexml_load_string($result);
        $places = json_decode(json_encode($xml_object))->results->ids->value;
        echo('<div  style="text-align:left;display:table"><div id="test_0158_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0158_results_1_div\')" style="font-weight:bold">The Whole Enchilada ('.count($places).'):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Next, Paginate in Pages of Twenty:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_ids_only&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $xml_object = simplexml_load_string($result);
            $places = isset(json_decode(json_encode($xml_object))->results->ids) ? json_decode(json_encode($xml_object))->results->ids->value : NULL;
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0158_results_1_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0158_results_1_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                    echo('</div>');
                echo('</div></div>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Do it again, but this time with a location/radius search:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_ids_only&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $xml_object = simplexml_load_string($result);
            $places = isset(json_decode(json_encode($xml_object))->results->ids) ? json_decode(json_encode($xml_object))->results->ids->value : NULL;
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0158_results_2_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0158_results_2_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
                    echo('</div>');
                echo('</div></div>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
}

// --------------------

function basalt_test_define_0159() {
    basalt_run_single_direct_test(159, 'PASS: Do An Address Lookup Radius Search', 'We don\'t log in, and do a search, based on a string address (the White House).', 'dc_area_tests');
}

function basalt_test_0159($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_radius=10&search_address=1600+Pennsylvania+Avenue,+Washington+DC', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0160() {
    basalt_run_single_direct_test(160, 'PASS: Do An Address Venue String Search', 'We log in, and do a search, based on a string search for an explicit venue name, then we try with wildcards.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0160($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_venue=Good+Samaritan+Hospital&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_venue=%Hospital&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0161() {
    basalt_run_single_direct_test(161, 'PASS: Do A Street Address String Search', 'We don\'t log in, and do a search, based on a string search for a street address (1496 S. Main Street); then, use a couple of wildcards (%Main St% and 149%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0161($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_street_address=1496+S.+Main+Street&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0161_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0161_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_street_address=%Main+St%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0161_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0161_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_street_address=149%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0161_results_3_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0161_results_3_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0162() {
    basalt_run_single_direct_test(162, 'PASS: Do A Town String Search', 'We don\'t log in, and do a search, based on a string search for a town (Rockville); then, use a wildcard (Ro%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0162($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_town=Rockville&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0162_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0162_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_town=Ro%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0162_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0162_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0163() {
    basalt_run_single_direct_test(163, 'PASS: Do A State String Search', 'We don\'t log in, and do a search, based on a string search for a state (DE); then, use a wildcard (D%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0163($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_state=DE&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0163_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0163_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_state=D%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0163_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0163_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0164() {
    basalt_run_single_direct_test(164, 'PASS: Do A County String Search', 'We don\'t log in, and do a search, based on a string search for a county (Montgomery); then, use a wildcard (Montgomery%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0164($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_county=Montgomery&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0164_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0164_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/places/?search_county=Montgomery%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0164_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0164_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}
?>
