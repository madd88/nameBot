<?php

class imBot
{

    public function __construct(){

    }

    public function installApp(array $request = []){

        $backUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (in_array($_SERVER['SERVER_PORT'],
                array(80, 443)) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];

        $result = $this->sendRequest('imbot.register',
            [
                'CODE'                  => 'imNameBot',
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
                        'WORK_POSITION'     => 'Узнаю имена',
                        'PERSONAL_WWW'      => '',
                        'PERSONAL_GENDER'   => 'M',
                        'PERSONAL_PHOTO'    => ''
                    ]
            ],
            $request["auth"]);

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