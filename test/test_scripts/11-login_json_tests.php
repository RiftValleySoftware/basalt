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

basalt_run_tests(91, 'JSON LOGIN TESTS', '');

// -------------------------- DEFINITIONS AND TESTS -----------------------------------

function basalt_test_define_0091() {
    basalt_run_single_direct_test(91, 'FAIL: Test Login Validation Routine', 'We login once with a regular login, and make sure that the login passes, then we try with our special \'knockout string,\' which should result in the login being invalidated.', 'login_tests');
}

function basalt_test_0091($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    
    echo('<h4>Log in \'aspie\' (This should work):</h4>');
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=aspie&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($aspie_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Log out \'aspie\':</h4>');
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Logout</code></h3>');
    }
    
    echo('<h4>Log in \'aspie\' with our special \'trigger\' string (This should Fail with a 403):</h4>');
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=aspie&password=CoreysGoryStory&TEST-SCRAG-BASALT-LOGIN', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($aspie_api_key, true)).'</code></h3>');
    }
}

// --------------------

function basalt_test_define_0092() {
    basalt_run_single_direct_test(92, 'FAIL: Change Login Passwords', 'Try changing various login passwords. We are also making sure that the user is logged out when their password is changed.', 'login_tests');
}

function basalt_test_0092($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>Log in \'aspie\':</h4>');
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=aspie&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($aspie_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Change Our Own Password Via the User (No \'login_user\'):</h4>');
    echo('<div><p class="explain">We will first try it directly, without the \'login_user.\'</p></div>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?password=ThisIsAPassword', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Change Our Own Password Via the User (With \'login_user\'):</h4>');
    echo('<div><p class="explain">Next, we add the \'login_user.\'</p></div>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/7?password=ThisIsAPassword&login_user', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Log out \'aspie\' (We expect this to fail, as \'aspie\' should have been force-logged out by the password change):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Logout</code></h3>');
    }

    echo('<h4>Log in \'aspie,\' again, using the new password:</h4>');
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=aspie&password=ThisIsAPassword', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($aspie_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Change Our Own Password Via the Login:</h4>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/9?password=CoreysGoryStory', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Log out \'aspie\' again (We expect this to fail, as \'aspie\' should have been force-logged out by the password change):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Logout</code></h3>');
    }

    echo('<h4>Log in \'aspie,\' again, using the new password:</h4>');
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=aspie&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($aspie_api_key, true)).'</code></h3>');
    }

    echo('<h4>Log out \'aspie\' again. This time, it will succeed:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Logout</code></h3>');
    }

    echo('<h4>Log in \'norm,\', using the old password. \'norm\' is a standalone login. There is no user associated with it:</h4>');
    $norm_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=norm&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($norm_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Change Our Own Password Via the Login:</h4>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/3?password=ThisIsAPassword', NULL, $norm_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Log out \'norm\' again (We expect this to fail, as \'norm\' should have been force-logged out by the password change):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Logout</code></h3>');
    }

    echo('<h4>Log in \'norm,\' again, using the new password:</h4>');
    $norm_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=norm&password=ThisIsAPassword', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($norm_api_key, true)).'</code></h3>');
    }

    echo('<h4>Now, tell us about ourselves:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info', NULL, $norm_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Now, tell us about ourselves (\'norm\'):</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $norm_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Log in \'aspie,\' again, using the latest password:</h4>');
    $aspie_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=aspie&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($aspie_api_key, true)).'</code></h3>');
    }

    echo('<h4>Now, tell us about ourselves (\'aspie\'). First, just the login:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Now, tell us about ourselves (\'aspie\'). Next, just the user:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Now, tell us about ourselves (\'aspie\'). Next, the user and the login info:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/people/my_info?login_user', NULL, $aspie_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0093() {
    basalt_run_single_direct_test(93, 'PASS: Delete Logins and Users', 'We log in with a manager, and try deleting some logins and associated users.', 'login_tests');
}

function basalt_test_0093($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
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

function basalt_test_define_0094() {
    basalt_run_single_direct_test(94, 'PASS: Delete Just A Login', 'In this test, we log in with \'bob\' (if you remember, can edit the login; but not the user), and verify with \'king-cobra.\'', 'login_tests');
}

function basalt_test_0094($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
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

// --------------------

function basalt_test_define_0095() {
    basalt_run_single_direct_test(95, 'PASS: Create A Login', 'In this test, we log in with \'king-cobra,\' and createa a standalone login.', 'login_tests');
}

function basalt_test_0095($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>Log in \'king-cobra\':</h4>');
    $king_cobra_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=king-cobra&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
    
    $new_login_id = NULL;
    $password = NULL;
    
    echo('<h4>We will create the simplest login possible, with just a login string ID:</h4>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/?login_string=NewLogin', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $new_login_id = $json_object->people->logins->new_login->login_id;
        $password = $json_object->people->logins->new_login->password;
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>Now, log into the new ID:</h4>');
    $new_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$new_login_id.'&password='.$password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($new_api_key, true)).'</code></h3>');
    }

    echo('<h4>Now, tell us about ourselves:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $new_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Now, delete the new login:</h4>');
    $result = call_REST_API('DELETE', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/'.$new_login_id, NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>We will create another standalone login, with more associated information:</h4>');
    $result = call_REST_API('POST', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/?login_string=AnotherNewLogin&name=Another+New+Login&read_token=3&tokens=2,3,4,5,6,7,8&password=ANewPassword,ThisIs', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $new_login_id = $json_object->people->logins->new_login->login_id;
        $password = $json_object->people->logins->new_login->password;
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Now, log into the new ID:</h4>');
    $new_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$new_login_id.'&password='.$password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($new_api_key, true)).'</code></h3>');
    }

    echo('<h4>Now, tell us about ourselves:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $new_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
}

// --------------------

function basalt_test_define_0096() {
    basalt_run_single_direct_test(96, 'FAIL: Changing Login Information', 'In this test, we log in with \'king-cobra,\' and create a standalone login.', 'login_tests');
}

function basalt_test_0096($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>Log in \'king-cobra\':</h4>');
    $king_cobra_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=king-cobra&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
    
    $new_login_id = NULL;
    $password = NULL;
    
    echo('<h4>First, List Our Own Information:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?login_user', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>We will change our name, read, write and token information:</h4>');
    $result = call_REST_API('PUT', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info?name=Another+Name+For+Nothin+Left+To+Lose&read_token=3&write_token=6&tokens=2,3,4,5,6,7,8&password=ANewPassword,ThisIs', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        $json_object = json_decode($result);
        $new_login_id = $json_object->people->logins->changed_logins[0]->after->login_id;
        $password = $json_object->people->logins->changed_logins[0]->after->password;
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }
    
    echo('<h4>We should now be logged out (We changed our password), so this should fail:</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/json/people/logins/my_info', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<pre style="color:green">'.prettify_json($result).'</pre>');
    }

    echo('<h4>Log in \'king-cobra\' with the new password:</h4>');
    $new_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id='.$new_login_id.'&password='.$password, NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
}

// --------------------

function basalt_test_define_0097() {
    basalt_run_single_direct_test(97, 'FAIL: Test Dual Login Protection', 'In this test, we log in with \'king-cobra,\' then immediately try to log in again. That should fail, then we logout, and try again, which should succeed.', 'login_tests');
}

function basalt_test_0097($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $result_code = '';
    echo('<h4>Log in \'king-cobra\':</h4>');
    $king_cobra_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=king-cobra&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Log in \'king-cobra\' Again:</h4>');
    $new_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=king-cobra&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
    
    echo('<h4>Log out \'king-cobra\':</h4>');
    $result = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/logout', NULL, $king_cobra_api_key, $result_code);
    if (isset($result_code) && $result_code && (205 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Logout</code></h3>');
    }
    
    echo('<h4>Log in \'king-cobra\' Again (Should work, this time):</h4>');
    $king_cobra_api_key = call_REST_API('GET', 'http://localhost/basalt/test/basalt_runner.php/login?login_id=king-cobra&password=CoreysGoryStory', NULL, NULL, $result_code);
    if (isset($result_code) && $result_code && (200 != $result_code)) {
        echo('<h3 style="color:red">RESULT CODE: '.htmlspecialchars(print_r($result_code, true)).'</h3>');
    } else {
        echo('<h3 style="color:green">Successful Login. Returned API Key: <code style="color:green">'.htmlspecialchars(print_r($king_cobra_api_key, true)).'</code></h3>');
    }
}
?>
