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

basalt_run_tests(1, 'GENERIC BASELINE', 'Try the basic list plugins command, and access each plugin for its XML schema.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0001() {
    basalt_run_single_direct_test(1, 'FAIL: List Plugins (JSON)', 'We first try with no plugin selector. We expect that to fail, then we try with \'baseline\' as the selector.', 'user_tests');
}

function basalt_test_0001($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we try directly:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Next, we add \'/baseline\':</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    echo('<h3>Next, we add \'/baseline/version\':</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline/version', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0002() {
    basalt_run_single_direct_test(2, 'FAIL: List Plugins (XML)', 'We first try with no plugin selector. We expect that to fail, then we try with \'baseline\' as the selector.', 'user_tests');
}

function basalt_test_0002($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, we try directly:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we add \'/baseline\':</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, we add \'/baseline/version\':</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xml/baseline/version', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0003() {
    basalt_run_single_direct_test(3, 'FAIL: Get All Plugin Schemas (XSD)', 'We determine what plugins we have, then get the XML schema for each', 'user_tests');
}

function basalt_test_0003($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h3>First, try a direct call (should fail):</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xsd/', NULL, NULL, $result_code, true);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
    }
    echo('<h3>Next, get all of our plugins:</h3>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/baseline', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
        $plugin_list = json_decode($result)->baseline->plugins;
        foreach ($plugin_list as $plugin_name) {
            echo('<h3 style="margin-top:0.25em">This is the schema for '.$plugin_name.':</h3>');
            $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/xsd/'.$plugin_name, NULL, NULL, $result_code);
            if (isset($result_code) && $result_code && (200 != $result_code)) {
                echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
            } else {
                echo('<pre style="color:green">'.prettify_xml($result).'</pre>');
            }
        }
    }
}
?>
