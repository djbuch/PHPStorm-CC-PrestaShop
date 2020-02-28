<?php

$output_folder = 'CC-helpers';

function readFilesRecursively($folder, $output_folder) {
    if (!file_exists($output_folder)) {
        mkdir($output_folder);
    }
    $array = explode('/', $folder);
    $base = array_pop($array);

    $files = scandir($folder);

    foreach($files as $key => $value){
        if ($value == '.' || $value == '..' || $value == 'index.php')
            continue;
        if(!is_dir($folder. DIRECTORY_SEPARATOR .$value)){
            $class_name = str_replace(".php","",$value);
            //echo $folder. DIRECTORY_SEPARATOR .$value."\n";
            $file_content = file_get_contents($folder. DIRECTORY_SEPARATOR .$value);
            if (stristr($file_content, 'class '.$class_name."Core") !== false) {
                $abstract = '';
                if (stristr($file_content, 'abstract class '.$class_name."Core") !== false) {
                    $abstract = 'abstract ';
                }
                $code = "<?php\n\n" .
                    $abstract . "class ".$class_name." extends ".$class_name."Core {}";
                if (!file_exists($output_folder. "/" . $base )) {
                    mkdir($output_folder. "/" . $base );
                }
                file_put_contents($output_folder . "/" . $base . "/" . $value, $code);
            }
        } else if(is_dir($folder. DIRECTORY_SEPARATOR .$value)) {
            readFilesRecursively($folder. DIRECTORY_SEPARATOR .$value, $output_folder."/".$base);
        }
    }
}

readFilesRecursively(dirname(__FILE__).'/classes',  $output_folder);
readFilesRecursively(dirname(__FILE__).'/controllers',  $output_folder);

