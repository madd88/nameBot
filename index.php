<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/core/config.php";


$imBot = new imBot();

switch ($_REQUEST['event']){
    case 'ONAPPINSTALL':
        $imBot->installApp($_REQUEST);
        break;

}
