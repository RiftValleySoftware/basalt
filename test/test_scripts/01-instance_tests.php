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

basalt_run_tests(1, 'TESTING 1', 'This is the first set of tests.');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0001() {
    basalt_run_single_direct_test(1, 'TEST TWEE', 'Lorem Ipsum Dodo', 'base_tests');
}

function basalt_test_0001($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    return make_andisol($in_login, $in_hashed_password, $in_password);
}

//-------------

function basalt_test_define_0002() {
    basalt_run_single_direct_test(2, 'TEST FOH', 'Lorem Ipsum Dodo');
}

function basalt_test_0002($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    return make_andisol($in_login, $in_hashed_password, $in_password);
}
?>
