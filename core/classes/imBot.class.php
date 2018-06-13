<?php

/**
 * Simple Bot ask users city name
 * @author  Nikolaev Aleksei
 * @version 1.0
 * @access  public
 * */
class imBot
{

    public $request = [];

    public function __construct($params)
    {
        $this->request = $params;
    }

    /**
     * Settings for installation on bitrix24
     */

    public function installApp()
    {

        $backUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (in_array($_SERVER['SERVER_PORT'],
                [80, 443]) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];

        $this->sendRequest('imbot.register',
            [
                'CODE'                  => 'imCityBot',
                'TYPE'                  => 'O',
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
    }


    /**
     * Show message when user join the chat
     */

    public function joinChat()
    {
        $this->sendRequest('imbot.message.add', [
            'BOT_ID'    => $this->request['data']['PARAMS']['BOT_ID'],
            'DIALOG_ID' => $this->request['data']['PARAMS']['DIALOG_ID'],
            'MESSAGE'   => 'Привет! Я Узнавака, напишите название своего города.'
        ], $this->request['auth']);
    }


    /**
     * Show message when user enters his message
     */
    public function sendAnswer()
    {

        $city = $this->getCity($_REQUEST['data']['PARAMS']['MESSAGE']);
        if (!$city) {
            $this->sendRequest('imbot.message.add', [
                'BOT_ID'    => $this->request['data']['PARAMS']['BOT_ID'],
                "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
                "MESSAGE"   => 'Первый раз вижу такой город. Попробуйте еще раз.'
            ],
                $this->request["auth"]);
        } else {
            $this->sendRequest('imbot.message.add', [
                'BOT_ID'    => $this->request['data']['PARAMS']['BOT_ID'],
                "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
                "MESSAGE"   => 'Спасибо за ответ. Хорошего дня.'
            ],
                $this->request["auth"]);
        }
    }

    /**
     * Create and send request to bitrix24
     *
     * @param string $method bitrix24 api method
     * @param array  $params parameters for api request. Every method has his own parameters
     * @param array  $auth   bitrix24 api auth array. From $_REQUEST['auth']
     *
     * @return string
     */
    public function sendRequest(string $method, array $params = [], array $auth = []): string
    {
        $URL = 'https://' . $auth['domain'] . '/rest/' . $method;
        $queryParams = http_build_query(array_merge($params, ['auth' => $auth['access_token']]));
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST           => 1,
            CURLOPT_HEADER         => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $URL,
            CURLOPT_POSTFIELDS     => $queryParams,
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);

        return $result;
    }

    /**
     * Get city list by name or a part of name
     *
     * @param string $cName city name
     *
     * @return string
     */

    public function getCity($cName): string
    {
        $url = 'http://geohelper.info/api/v1/cities?&apiKey=' . GEO_API_KEY . '&locale[lang]=ru&filter[name]=' . $cName;
        $cities = json_decode(file_get_contents($url));
        $result = (count($cities->result) === 0) ? false : true;

        return $result;

    }


}