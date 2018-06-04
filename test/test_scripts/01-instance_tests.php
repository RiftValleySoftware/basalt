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
require_once(dirname(dirname(__FILE__)).'/functions.php');
    
// -------------------------------- TEST DISPATCHER ---------------------------------------------

function basalt_basic_run_tests() {
    basalt_basic_run_test(1, 'TEST', 'TEST');
}

// -------------------------------- TESTS ---------------------------------------------

function basalt_basic_test_001($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    return make_andisol($in_login, $in_hashed_password, $in_password);
}

// -------------------------------- STRUCTURE ---------------------------------------------

function basalt_basic_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(true);
            $function_name = sprintf('basalt_basic_test_%03d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(true) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

ob_start();
    prepare_databases('base_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">BASALT BASIC TESTS</h1>');
        echo('<div id="instance-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'instance-tests\')">BASIC TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                basalt_basic_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
