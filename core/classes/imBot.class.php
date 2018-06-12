<?php

class imBot
{

    public $request = [];

    public function __construct($params){
        $this->request = $params;
    }

    public function installApp(){

        $backUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (in_array($_SERVER['SERVER_PORT'],
                array(80, 443)) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];

        $result = $this->sendRequest('imbot.register',
            [
                'CODE'                  => 'imCityBot',
                'TYPE'                  => 'B',
                'EVENT_MESSAGE_ADD'     => $backUrl,
                'EVENT_WELCOME_MESSAGE' => $backUrl,
                'EVENT_BOT_DELETE'      => $backUrl,
                'PROPERTIES'            =>
                    [
                        'NAME'              => 'Узнавака',
                        'LAST_NAME'         => '',
                        'COLOR'             => 'GREEN',
                        'EMAIL'             => 'madd.niko@gmail.com',
                        'PERSONAL_BIRTHDAY' => '2018-06-13',
                        'WORK_POSITION'     => 'Узнаю город',
                        'PERSONAL_WWW'      => '',
                        'PERSONAL_GENDER'   => 'M',
                        'PERSONAL_PHOTO'    => ''
                    ]
            ],
            $this->request["auth"]);

        return $result;
    }

    public function joinChat(){

        $result = $this->sendRequest('imbot.message.add', array(
            'BOT_ID'    => $this->request['data']['PARAMS']['BOT_ID'],
            'DIALOG_ID' => $this->request['data']['PARAMS']['DIALOG_ID'],
            'MESSAGE'   => 'Привет! Я Узнавака, напишите название города.'
        ), $this->request['auth']);

        return $result;
    }


    public function sendRequest(string $method, array $params = [], array $auth = []) : string
    {
        $URL = 'https://' . $auth['domain'] . '/rest/' . $method;
        $queryParams = http_build_query(array_merge($params, array('auth' => $auth['access_token'])));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $URL,
            CURLOPT_POSTFIELDS => $queryParams,
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);
        return $result;
    }




}