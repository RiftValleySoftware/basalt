<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>BAOBAB REST Documentation</title>
        <link rel="stylesheet" type="text/css" href="main/apidoc.css">
    </head>
    <body><?php
            $doc_array = ['baseline' => dirname(__FILE__).'/main/doc.html'];
            $dir = new DirectoryIterator(dirname(__FILE__).'/plugins');
            foreach ($dir as $fileinfo) {
                if (!$fileinfo->isDot() && $fileinfo->isDir()) {
                    $doc_array[$fileinfo->getBasename()] = $fileinfo->getPathname().'/doc.html';
                }
            }
            
            ksort($doc_array);
            
            foreach ($doc_array as $id => $file_path) {
                if (file_exists($file_path)) {
                    echo('<div class="rest_api_doc_div rest_api_doc_div-'.htmlspecialchars($id).'" id="div-'.htmlspecialchars($id).'">');
                        include_once($file_path);
                    echo('</div>');
                }
            }
        ?></body>
</html>
