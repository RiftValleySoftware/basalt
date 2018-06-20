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
function call_REST_API( $method,
                        $url,
                        $data_file = NULL,
                        $api_key = NULL,
                        &$httpCode = NULL,
                        $display_log = false
                        ) {

    $file = NULL;
    $content_type = NULL;
    $file_size = 0;
    $temp_file_name = NULL;
    
    if ($data_file) {
        $file_location = $data_file['filepath'];
        $file_type = $data_file['type'];
        
        $temp_file_name = tempnam(sys_get_temp_dir(), 'RVP');
        $source_file = fopen($file_location, 'r');
        $file = fopen($temp_file_name, 'w');
        
        $file_data = base64_encode(fread($source_file, filesize($file_location)));
        fwrite($file, $file_data, strlen($file_data));
        
        fclose($file);
        fclose($source_file);

        $content_type = $file_type.':base64';
        $file_size = filesize($temp_file_name);
        $file = fopen($temp_file_name, 'rb');
    }
        
    if (isset($api_key) && $api_key) {
        echo('<p style="vertical-align:middle;font-style:italic">API KEY: <big><code>'.$api_key.'</code></big></p>');
    }
    
    echo('<p style="vertical-align:middle;font-style:italic">'.$method.' URI: <big><code>'.$url.'</code></big></p>');
    
    $curl = curl_init();
    
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, true);
            
            if ($file) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:', 'Content-type: multipart/form-data']);
                $post = Array('payload'=> curl_file_create($temp_file_name, $content_type));
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            } else {
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:']);
            }
            break;
            
        case "PUT":
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:']);
            curl_setopt($curl, CURLOPT_PUT, true);
            
            if ($file) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
                curl_setopt($curl, CURLOPT_INFILE, $file);
                curl_setopt($curl, CURLOPT_INFILESIZE, $file_size);
            }
            break;
            
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            
        default:
    }

    // Authentication
    if (isset($api_key)) {
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$api_key:$api_key");
    }

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, false);
    
    if (isset($display_log) && $display_log) {
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ( $curl, $header_line ) {
            echo "<pre>".$header_line.'</pre>';
            return strlen($header_line);
        });
        echo('<div style="margin:1em">');
        echo("<h4>Sending REST $method CALL:</h4>");
        echo('<div>URL: <code>'.htmlspecialchars($url).'</code></div>');

        if ($api_key) {
            echo('<div>API KEY:<pre>'.htmlspecialchars($api_key).'</pre></div>');
        }
    }
    
    $result = curl_exec($curl);

    if ($result === false) {
        $info = curl_getinfo($curl);
        $result = 'error occured during curl. Info: '.var_export($info);
    }
    
    if (isset($httpCode)) {
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    }

    curl_close($curl);
    
    if ($file) {
        fclose($file);
    }
    
    if (isset($display_log) && $display_log) {
        if (isset($data_file)) {
            echo('<div>ADDITIONAL DATA:<pre>'.htmlspecialchars(print_r($data_file, true)).'</pre></div>');
        }
        if (isset($httpCode) && $httpCode) {
            echo('<div>HTTP CODE:<code>'.htmlspecialchars($httpCode, true).'</code></div>');
        }
        echo('<div>RESULT:<pre>'.htmlspecialchars(print_r($result, true)).'</pre></div>');
        echo("</div>");
    }

    return $result;
}
