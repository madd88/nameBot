<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/core/config.php";


$imBot = new imBot($_REQUEST);

switch ($_REQUEST['event']){
    case 'ONAPPINSTALL':
        $imBot->installApp();
        break;

    case 'ONIMBOTJOINCHAT':
        $imBot->joinChat();
        break;
    case 'ONIMBOTMESSAGEADD':
        $imBot->sendAnswer();
        break;
}
