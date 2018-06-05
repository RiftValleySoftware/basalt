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
require_once(dirname(__FILE__).'/functions.php');

// ---------------------------------- TEST DISPATCHER -------------------------------------------

function basalt_run_tests($starting_test, $in_title, $in_explain = NULL) {
    ob_start();
    echo('<div id="test-'.htmlspecialchars($starting_test).'" class="closed">');
        echo('<h2 class="header"><a href="javascript:toggle_main_state(\'test-'.htmlspecialchars($starting_test).'\')">'.htmlspecialchars($in_title).'</a></h2>');
        echo('<div class="container">');
            if ($in_explain) {
                echo('<p class="explain">'.htmlspecialchars($in_explain).'</p>');
            }
            for ($i = $starting_test; $i < 10000; $i++) {
                $function_name = sprintf("basalt_test_define_%04d", $i);
                if (function_exists($function_name)) {
                    $function_name();
                } else {
                    break;
                }
            }
        echo('</div>');
    echo('</div>');
    $buffer = ob_get_clean();
    echo($buffer);
}

// ------------------------------- RUN SINGLE DIRECT TEST ---------------------------------------

function basalt_run_single_direct_test($in_num, $in_title, $in_explain = NULL, $in_database = NULL, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%04d", intval($in_num));
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="inner_container">');
            echo('<p class="explain" style="display:table;margin-left:auto;margin-right:auto">'.$in_explain.'</p>');
            echo('<div class="container">');
                if ($in_database) {
                    $st1 = microtime(true);
                    prepare_databases($in_database);
                    $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
                    echo("<h4>It took $fetchTime seconds to load the database.</h4>");
                }
                $st1 = microtime(true);
                $function_name = sprintf('basalt_test_%04d', $in_num);
                    $function_name($in_login, $in_hashed_password, $in_password);
                $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
                echo("<h4>The test took $fetchTime seconds to complete.</h4>");
            echo('</div>');
        echo('</div>');
    echo('</div>');
}
?>