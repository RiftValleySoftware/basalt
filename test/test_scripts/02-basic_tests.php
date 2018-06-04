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

basalt_run_tests(3, 'TESTING 2', 'This is the second set of tests.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0003() {
    basalt_run_single_direct_test(3, 'TEST TWEE', 'Lorem Ipsum Dodo', 'base_tests');
}

function basalt_test_0003($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    return make_andisol($in_login, $in_hashed_password, $in_password);
}

//-------------

function basalt_test_define_0004() {
    basalt_run_single_direct_test(4, 'TEST FOH', 'Lorem Ipsum Dodo');
}

function basalt_test_0004($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    return make_andisol($in_login, $in_hashed_password, $in_password);
}
?>
