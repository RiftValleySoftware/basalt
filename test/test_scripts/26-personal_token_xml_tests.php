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

basalt_run_tests(186, 'PERSONAL TOKEN XML TESTS', 'EXPLAIN');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0186() {
    basalt_run_single_direct_test(186, 'TEST', 'EXPLAIN', 'personal_id_test', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0186($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
}

function basalt_test_define_0187() {
    basalt_run_single_direct_test(187, 'TEST', 'EXPLAIN', 'personal_id_test', 'admin', '', CO_Config::god_mode_password());
}

function basalt_test_0187($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
}
?>
