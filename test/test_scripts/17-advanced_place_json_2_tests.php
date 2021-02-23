<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, The Great Rift Valley Software Company
    
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


    The Great Rift Valley Software Company: https://riftvalleysoftware.com
*/
// ------------------------------ MAIN CODE -------------------------------------------

require_once(dirname(dirname(__FILE__)).'/run_basalt_tests.php');

basalt_run_tests(135, 'ADVANCED JSON PLACES TESTS PART 2', 'Verify POST, DELETE and Special Functions.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0135() {
    basalt_run_single_direct_test(135, 'PASS: Create A New Location', 'Log in, and create a new simple location with no data at all.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0135($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Create a new empty place:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/places/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0136() {
    basalt_run_single_direct_test(136, 'PASS: Create A New Location At the Lincoln Memorial', 'Log in, and create a new location, loaded up as the Lincoln Memorial.', 'dc_area_tests', 'DCAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0136($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the DC Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Create our nice place:</h3>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/places/?read_token=1&write_token=1&name=The+Lincoln+Memorial&lang=en&longitude=-77.0502&latitude=38.8893&fuzz_factor=5&address_venue=Lincoln+Memorial&address_street_address=2+Lincoln+Memorial+Circle+NW&address_extra_information=Go+Up+The+Steps+And+Say+Hi+To+Abe&address_town=Washington+DC&address_county=DC&address_state=DC&address_postal_code=20037&address_nation=USA&tag8=HI&tag9=HOWAYA&child_ids=3,4,5,1470,1480,1725,1727', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0137() {
    basalt_run_single_direct_test(137, 'PASS: Delete One Single Place.', 'Log in, and delete one single location.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0137($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/places/2?show_parents', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0138() {
    basalt_run_single_direct_test(138, 'PASS: Delete Three Places.', 'Log in, and delete three individual locations.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0138($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/places/2,3,4', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0139() {
    basalt_run_single_direct_test(139, 'PASS: Delete Every Place We Can See.', 'Log in, and delete all the places that we can see.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0139($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the MD Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/places/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0139_first_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0139_first_change_record_div\')" style="font-weight:bold">See What Thou Hast Wrought:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0139_second_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0139_second_change_record_div\')" style="font-weight:bold">Are They Gone?</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0140() {
    basalt_run_single_direct_test(140, 'PASS: Delete Some Places In A Radius Search.', 'Log in, and delete a few of the places returned in a radius search.', 'dc_area_tests', 'VAAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0140($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In as the VA Admin.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    echo('<h3>Delete the first record:</h3>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=10&search_longitude=-77.063776&search_latitude=38.894926', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0140_first_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0140_first_change_record_div\')" style="font-weight:bold">See What Thou Hast Wrought:</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    echo('<h3>Make sure they are gone:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=10&search_longitude=-77.063776&search_latitude=38.894926', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0140_second_change_record_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0140_second_change_record_div\')" style="font-weight:bold">Are They Gone?</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0141() {
    basalt_run_single_direct_test(141, 'PASS: Test Pagination.', 'Do not log in, and do a big search, looking for various pages.', 'dc_area_tests');
}

function basalt_test_0141($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, Look For Everything At Once:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places->results;
        echo('<div  style="text-align:left;display:table"><div id="test_0141_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0141_results_1_div\')" style="font-weight:bold">The Whole Enchilada ('.count($places).'):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Next, Paginate in Pages of Twenty:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $places = json_decode($result)->places->results;
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0141_results_1_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0141_results_1_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $places = json_decode($result)->places->results;
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0141_results_2_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0141_results_2_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                    echo('</div>');
                echo('</div></div>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
}

// --------------------

function basalt_test_define_0142() {
    basalt_run_single_direct_test(142, 'PASS: Test Count', 'Don\'t log in, and get back just a count.', 'dc_area_tests');
}

function basalt_test_0142($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, Look For Everything At Once:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_count_only', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Next, Paginate in Pages of Twenty:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_count_only&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $places = json_decode($result)->places->results;
            if (!isset($places) || !isset($places->count) || !intval($places->count)) {
                break;
            } else {
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Do it again, but this time with a location/radius search:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_count_only&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $places = json_decode($result)->places->results;
            if (!isset($places) || !isset($places->count) || !intval($places->count)) {
                break;
            } else {
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
}

// --------------------

function basalt_test_define_0143() {
    basalt_run_single_direct_test(143, 'PASS: Get Just The IDs', 'We don\'t log in, and do the same searches as above.', 'dc_area_tests');
}

function basalt_test_0143($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, Look For Everything At Once:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_ids_only', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $places = json_decode($result)->places->results->ids;
        echo('<div  style="text-align:left;display:table"><div id="test_0143_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0143_results_1_div\')" style="font-weight:bold">The Whole Enchilada ('.count($places).'):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $page_size = 20;
    $current_page = 0;
    echo('<h3>Next, Paginate in Pages of Twenty:</h3>');
    do {
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_ids_only&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $places = json_decode($result)->places->results;
            if (isset($places->ids)) {
                $places = $places->ids;
            }
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0143_results_2_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0143_results_2_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
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
        $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_ids_only&search_radius=10&search_longitude=-77.063776&search_latitude=38.894926&search_page_size='.$page_size.'&search_page_number='.$current_page, NULL, NULL, $result_code);
        if (isset($result_code) && $result_code && (200 != $result_code)) {
            echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            break;
        } else {
            $places = json_decode($result)->places->results;
            if (isset($places->ids)) {
                $places = $places->ids;
            }
            if (!isset($places) || !is_array($places) || !count($places)) {
                break;
            } else {
                echo('<div  style="text-align:left;display:table"><div id="test_0143_results_3_'.$current_page.'div" class="inner_closed">');
                    echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0143_results_3_'.$current_page.'div\')" style="font-weight:bold">Page '.($current_page + 1).' ('.count($places).'):</a></h3>');
                    echo('<div class="main_div inner_container">');
                        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                    echo('</div>');
                echo('</div></div>');
                $current_page++;
            }
        }
    } while ($current_page < 1000);
}

// --------------------

function basalt_test_define_0144() {
    basalt_run_single_direct_test(144, 'PASS: Do An Address Lookup Radius Search', 'We don\'t log in, and do a search, based on a string address (the White House).', 'dc_area_tests');
}

function basalt_test_0144($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_radius=10&search_address=1600+Pennsylvania+Avenue,+Washington+DC', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0145() {
    basalt_run_single_direct_test(145, 'PASS: Do An Address Venue String Search', 'We log in, and do a search, based on a string search for an explicit venue name, then we try with wildcards.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0145($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_venue=Good+Samaritan+Hospital&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_venue=%Hospital&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0146() {
    basalt_run_single_direct_test(146, 'PASS: Do A Street Address String Search', 'We don\'t log in, and do a search, based on a string search for a street address (1496 S. Main Street); then, use a couple of wildcards (%Main St% and 149%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0146($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_street_address=1496+S.+Main+Street&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0146_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0146_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_street_address=%Main+St%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0146_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0146_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_street_address=149%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0146_results_3_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0146_results_3_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0147() {
    basalt_run_single_direct_test(147, 'PASS: Do A Town String Search', 'We don\'t log in, and do a search, based on a string search for a town (Rockville); then, use a wildcard (Ro%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0147($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_town=Rockville&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0147_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0147_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_town=Ro%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0147_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0147_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0148() {
    basalt_run_single_direct_test(148, 'PASS: Do A State String Search', 'We don\'t log in, and do a search, based on a string search for a state (DE); then, use a wildcard (D%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0148($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_state=DE&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0148_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0148_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_state=D%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0148_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0148_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0149() {
    basalt_run_single_direct_test(149, 'PASS: Do A County String Search', 'We don\'t log in, and do a search, based on a string search for a county (Montgomery); then, use a wildcard (Montgomery%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0149($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_county=Montgomery&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0149_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0149_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_county=Montgomery%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0149_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0149_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0150() {
    basalt_run_single_direct_test(150, 'PASS: Do A Town and State String Search', 'We don\'t log in, and do a search, based on a string search for a town and a state (Wilmington, DE); then, use a wildcard (W%, D%), and see what happens.', 'dc_area_tests');
}

function basalt_test_0150($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_town=Wilmington&search_state=DE&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0150_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0150_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
    
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_town=W%&search_state=D%&show_details', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0150_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0150_results_2_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0151() {
    basalt_run_single_direct_test(151, 'PASS: Do A Tag String Search', 'We log in, and do a search, based on a string search for an explicit venue name, add tags to those records, then search for the tags.', 'dc_area_tests', 'MDAdmin', '', 'CoreysGoryStory');
}

function basalt_test_0151($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>Log In.</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$in_login.'&password='.$in_password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_venue=Good+Samaritan+Hospital&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_venue=Good+Samaritan+Hospital&tag8=I+Like&tag9=Ike', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_tag8=I+Like', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_tag9=Ike', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_tag9=Ike&search_tag8=I+Like', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h3>EXTRA CREDIT: This string is in quite a few records:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/?search_tag8=20:00:00&show_details', NULL, $api_result, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0151_results_1_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0151_results_1_div\')" style="font-weight:bold">See the Results:</a></h3>');
            echo('<div class="main_div inner_container">');
            echo('<pre style="color:green">'.prettify_json($result).'</pre>');
            echo('</div>');
        echo('</div></div>');
    }
}

// --------------------

function basalt_test_define_0152() {
    basalt_run_single_direct_test(152, 'PASS: Test Hierarchy', 'We log in, and create a small hierarchy. We then make sure that the child and parent objects are reported correctly', 'dc_area_tests');
}

function basalt_test_0152($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    function set_up_hierarchy(  $in_current_id,
                                $in_hierarchy_list
                                ) {
        if ($in_current_id && isset($in_hierarchy_list) && is_array($in_hierarchy_list) && count($in_hierarchy_list)) {
            echo('<h3>Setting up the hierarchy for ID '.intval($in_current_id).'</h3>');
            $result_code = '';
            echo('<h3>Log In As the "God" Admin</h3>');
            $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            } else {
                echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
                if (1724 < intval($in_current_id)) {
                    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/'.intval($in_current_id).'/?child_ids='.implode(',', array_map('intval', array_keys($in_hierarchy_list))), NULL, $api_result, $result_code);
                } else {
                    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/places/'.intval($in_current_id).'/?child_ids='.implode(',', array_map('intval', array_keys($in_hierarchy_list))), NULL, $api_result, $result_code);
                }
                if (isset($result_code) && $result_code && (200 != $result_code)) {
                    echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
                    return;
                } else {
                    echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                    $extra_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
                }
            }
        }
        
        if (isset($in_hierarchy_list) && is_array($in_hierarchy_list) && count($in_hierarchy_list)) {
            foreach ($in_hierarchy_list as $id => $child) {
                set_up_hierarchy(intval($id), $child);
            }
        }
    }
    
    $hierarchy_list =   [
                            1730 => [           // MainAdmin
                                1725 => [       // MDAdmin
                                    2 => [
                                        3 => 0,
                                        4 => 0,
                                        5 => 0
                                    ],
                                    6 => [
                                        7 => 0,
                                        8 => 0,
                                        9 => 0
                                    ],
                                    10 => [
                                        11 => 0,
                                        12 => 0,
                                        13 => 0
                                    ]
                                ],
                                860 => 0    // Keep an eye on this one.
                            ],
                            [
                                1726 => [       // VAAdmin
                                    855 => [
                                        856 => 0,
                                        857 => 0,
                                        858 => 0,
                                        860 => 0
                                    ],
                                    859 => [
                                        860 => 0,
                                        861 => 0,
                                        862 => 0
                                    ],
                                    863 => [
                                        864 => 0,
                                        865 => 0,
                                        866 => 0
                                    ]
                                ]
                            ]
                        ];
    
    echo('<div  style="text-align:left;display:table"><div id="test_0152_results_1_div" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0152_results_1_div\')" style="font-weight:bold">See the Hierarchy Setup Process:</a></h3>');
        echo('<div class="main_div inner_container">');
            $st1 = microtime(true);
            set_up_hierarchy(0, $hierarchy_list, NULL);
            $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div></div>');
    
    $result_code = '';
    echo('<h3>Log In As "God." Again</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/2,3,4,5,6,7,8,9,10,11,12,13,855,856,857,858,859,860,861,862,863,864,865,866/?show_details', NULL, $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0152_results_2_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0152_results_2_div\')" style="font-weight:bold">See the Hierarchy Setup Results (Places -Without show_parents):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            echo('</div>');
        echo('</div></div>');
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/places/2,3,4,5,6,7,8,9,10,11,12,13,855,856,857,858,859,860,861,862,863,864,865,866/?show_parents', NULL, $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0152_results_3_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0152_results_3_div\')" style="font-weight:bold">See the Hierarchy Setup Results (Places -With show_parents):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            echo('</div>');
        echo('</div></div>');
    }
   
    $extra_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $api_result, $result_code);
        
    $result_code = '';
    echo('<h3>Log In As "God." Again</h3>');
    $api_result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=admin&password='.CO_Config::god_mode_password(), NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($api_result, true)).'</code></h3>');
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/1725,1726,1727,1728,1729,1730,1731/?show_details', NULL, $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0152_results_4_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0152_results_4_div\')" style="font-weight:bold">See the Hierarchy Setup Results (People -Without show_parents):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            echo('</div>');
        echo('</div></div>');
    }
    
    $st1 = microtime(true);
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/1725,1726,1727,1728,1729,1730,1731/?show_parents', NULL, $api_result, $result_code);
    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<div  style="text-align:left;display:table"><div id="test_0152_results_5_div" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test_0152_results_5_div\')" style="font-weight:bold">See the Hierarchy Setup Results (People -With show_parents):</a></h3>');
            echo('<div class="main_div inner_container">');
                echo('<pre style="color:green">'.prettify_json($result).'</pre>');
                echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            echo('</div>');
        echo('</div></div>');
    }
}
?>
