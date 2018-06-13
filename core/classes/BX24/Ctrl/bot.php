<?php

/**
 * Класс обрабатывающий события для бота
 */

namespace BX24\Ctrl;

class bot extends \BX24\Ctrl\ctrl
{

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function sendRequest()
    {

        switch ($this->request['event']){
            case 'ONAPPINSTALL':
                $this->model->installApp();
                break;
            /*    case 'ONIMBOTMESSAGEADD':
                    $imBot->sendAnswer();
                    break;*/
            case 'ONIMBOTJOINCHAT':
                $this->model->joinChat('Привет! Я, Узнавака. Напиши наименование своего города.');
                break;
        }
    }
}