<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT']);
define('GEO_API_KEY','udY7yWi50yDbYqCm0R3Bhw1DWiyqmtUO');


function __autoload($className) {

    $classPath = ROOT . '/core/classes/' . $className . '.class.php';

    if (file_exists($classPath)){

        include_once($classPath);

        if (!class_exists($className, false)) {
            trigger_error("Unable to load class: $className", E_USER_ERROR);
        }
    }

    else{

        die("Could'nt find $className file.");

    }

}