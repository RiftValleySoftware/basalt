<?php
/***************************************************************************************************************************/
/**
    COBRA Security Administration Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/

require_once(dirname(__FILE__).'/functions.php');

$test_name_array = Array();

date_default_timezone_set ( 'UTC' );

foreach (new DirectoryIterator(dirname(__FILE__).'/test_scripts') as $fileInfo) {
    if (($fileInfo->getExtension() === 'php') && ('index.php' != $fileInfo->getBasename())) {
        array_push($test_name_array, $fileInfo->getBasename('.php'));
    }
}

if (isset($test_name_array) && is_array($test_name_array) && count($test_name_array)) {
    sort($test_name_array);
    $test_name_array = "Array('".implode("','", $test_name_array)."')";
} else {
    $test_name_array = "Array('')";
}

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>ANDISOL</title>
        <link rel="shortcut icon" href="../icon.png" type="image/png" />
        <style>
            *{margin:0;padding:0}
            body {
                font-family: Arial, San-serif;
                }
            
            div.main_div {
                margin-top:0.25em;
                margin-bottom: 0.25em;
                margin-left:1em;
                padding: 0.5em;
            }
            
            div.inner_div {
                margin-top:0.25em;
                margin-left:1em;
                padding: 0.25em;
            }
            
            .explain {
                font-style: italic;
            }
            
            h1.header {
                font-size: x-large;
                text-align:center;
            }
            
            h2.header {
                font-size: large;
                margin-top: 1em;
                text-align:center;
            }
            
            div.open h1.header {
            }
            
            div.closed h1.header {
            }
            
            div.open div.container {
                margin-left:1em;
                display: block;
            }
            
            div.closed div.container {
                display: none;
            }
            
            h3.inner_header {
                font-size: medium;
            }
            
            div.inner_open h2.inner_header {
            }
            
            div.inner_closed h2.inner_header {
            }
            
            div.inner_open div.inner_container {
                margin-left:1em;
                display: block;
                border:1px dashed #555;
            }
            
            div.inner_closed div.inner_container {
                display: none;
            }
            
            div.test-wrapper {
                display: table;
                margin:auto;
                padding: 0.25em;
                margin-top:1em;
                border-radius:0.5em;
                border:2px solid #999;
                min-width:30em;
            }
            
            div.collection_wrapper {
            }
            
        </style>
        
        <script type="text/javascript" src="ajaxLoader.js"></script>
        <script type="text/javascript" src="runTests.js"></script>
        <script type="text/javascript">
            function toggle_main_state(in_id) {
                var item = document.getElementById(in_id);
                
                if ( item.className == 'closed' ) {
                    item.className = 'open';
                } else {
                    item.className = 'closed';
                };
            };
            
            function toggle_inner_state(in_id) {
                var item = document.getElementById(in_id);
                
                if ( item.className == 'inner_closed' ) {
                    item.className = 'inner_open';
                } else {
                    item.className = 'inner_closed';
                };
                
            };
            
            function expose_tests() {
                var item = document.getElementById('throbber-container');
                
                if (item) {
                    item.style="display:none";
                };
                
                var item = document.getElementById('tests-wrapped-up');
                
                if (item) {
                    item.style="display:block";
                };
            };
        </script>
    </head>
    <body>
        <h1 style="text-align:center">ANDISOL OBJECT MODEL LAYER</h1>
        <div style="text-align:center;padding:1em;">
            <div id="throbber-container" style="text-align:center">
                <h3 id="progress-report" style="margin-top:1em"></h3>
                <img src="images/throbber.gif" alt="throbber" style="position:absolute;width:190px;top:50%;left:50%;margin-top:-95px;margin-left:-95px" />
                <img src="../icon.png" alt="icon" style="position:absolute;width:128px;top:50%;left:50%;margin-top:-64px;margin-left:-64px" />
            </div>
            <?php
            $start_time = microtime(true);
            ?>
            <div id="tests-wrapped-up" style="display:none">
                <img src="../icon.png" style="display:block;margin:auto;width:80px" alt="" />
                <div id="tests-displayed"></div>
                <h3 style="margin-top:1em"><a href="./">RETURN TO MAIN ENVIRONMENT SETUP</a></h3>
            </div>
            <script type="text/javascript">
                runTests(<?php echo($test_name_array) ?>);
            </script>
        </div>
    </body>
</html>

