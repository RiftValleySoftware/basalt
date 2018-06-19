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

basalt_run_tests(89, 'JSON LOGIN TESTS', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0089() {
    basalt_run_single_direct_test(89, 'PASS: Delete Logins and Users', 'We log in with a manager, and try deleting some logins and associated users.', 'login_tests');
}

function basalt_test_0089($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>First, log in \'asp\':</h4>');
    $asp_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=asp&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($asp_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Next, log in \'bob\':</h4>');
    $bob_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=bob&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($bob_api_key, true)).'</code></h3>');
    }

    echo('<h4>Next, log in \'king-cobra\':</h4>');
    $king_cobra_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=king-cobra&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
    
    echo('<div><p class="explain">We have logged in \'asp,\' which can see (and write) user 3 (\'krait\'), but not login 6 (\'krait\'). We also log in \'bob,\' which can see both the user and the login. However, \'bob\' cannot write to the user, and we log in \'king-cobra,\' which can write (and see) both.</p></div>');

    echo('<h4>BEFORE, using \'asp\' -Note that we cannot see the login, which is ID 6, but we can see (and write) the user:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details&login_user', NULL, $asp_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>BEFORE, using \'bob\' -Note that we can now see (and write) the login, but we cannot write the user:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details&login_user', NULL, $bob_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>BEFORE, using \'king-cobra\' -Note that we can now see and write both the login and the user:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details&login_user', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<div><p class="explain">OK. Now we know where we stand. Our mission, should we choose to accept it, is to delete the \'krait\' user and login, <strong>via the login (ID: 6); not the user (ID: 3)</strong>.</p></div>');
    
    echo('<h4>Let\'s start with \'asp\':</h4>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/6?delete_user', NULL, $asp_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Let\'s try it with \'bob\':</h4>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/6?delete_user', NULL, $bob_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>No dice. The reason that we got that, was because we are allowed to delete the login, but not the user. In the next test, we\'ll try deleting just the login.</h4>');
    
    echo('<h4>Let\'s try it with \'king-cobra\':</h4>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/6?delete_user', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h4>BINGO!</h4>');
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Now, let\'s make sure she\'s dead, Jim:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details&login_user', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0090() {
    basalt_run_single_direct_test(90, 'PASS: Delete Just A Login', 'In this test, we log in with \'bob\' (if you remember, can edit the login; but not the user), and verify with \'king-cobra.\'', 'login_tests');
}

function basalt_test_0090($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>First, log in \'bob\':</h4>');
    $bob_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=bob&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($bob_api_key, true)).'</code></h3>');
    }

    echo('<h4>Next, log in \'king-cobra\':</h4>');
    $king_cobra_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=king-cobra&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
    
    echo('<div><p class="explain">We have logged in \'asp,\' which can see (and write) user 3 (\'krait\'), but not login 6 (\'krait\'). We also log in \'bob,\' which can see both the user and the login. However, \'bob\' cannot write to the user, and we log in \'king-cobra,\' which can write (and see) both.</p></div>');

    echo('<h4>BEFORE, using \'bob\' -Note that we can see (and write) the login, but we cannot write the user:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details&login_user', NULL, $bob_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>BEFORE, using \'king-cobra\' -Note that we can see and write both the login and the user. We will use this ID to check our work:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details&login_user', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<div><p class="explain">OK. Now we know where we stand. Our mission, should we choose to accept it, is to delete the \'krait\' login only, using \'bob.\'</p></div>');
    
    echo('<h4>Let\'s try it with \'bob.\' Note that this time, we did not attach \'delete_user\':</h4>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/6', NULL, $bob_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Now, let\'s see what we have. We should have a user, but no associated login:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/3?show_details&login_user', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}
?>
