<?php

namespace BX24\Model;

/**
 * Бот для открытых линий
 * Основная функция задать вопрос в чате
 * @author  Nikolaev Aleksei
 * @version 1.0
 * @access  public
 * */
class imBot
{

    public $request = [];

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Установка приложения в Битрикс24
     */

    public function installApp()
    {

        $backUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (in_array($_SERVER['SERVER_PORT'],
                array(80, 443)) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];

        $this->sendRequest('imbot.register',
            [
                'CODE'                  => 'imCityBot2',
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
     * Выводит сообщение при соединении к чату
     *
     * @param string $message Приветственное сообщение
     *
     */
//'Привет! Я Узнавака, напишите название своего города.'
    public function joinChat(string $message = '')
    {
        $this->sendRequest('imbot.message.add', [
            'BOT_ID'    => $this->request['data']['PARAMS']['BOT_ID'],
            'DIALOG_ID' => $this->request['data']['PARAMS']['DIALOG_ID'],
            'MESSAGE'   => $message
        ], $this->request['auth']);

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/log.txt", json_encode($this->request) . "-join\r\n", FILE_APPEND);

    }


    /**
     * Ответ на сообщение пользователю
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
     * Отправка запроса в Bitrix24
     *
     * @param string $method имя метода
     * @param array  $params список параметров запроса
     * @param array  $auth   bitrix24 массив аутентификации
     *
     * @return string
     */
    public function sendRequest(string $method, array $params = [], array $auth = [])
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
     * Получаем список городов по названию
     *
     * @param string $cName Название города
     *
     * @return bool
     */

    public function getCity($cName): bool
    {
        $url = 'http://geohelper.info/api/v1/cities?&apiKey=' . GEO_API_KEY . '&locale[lang]=ru&filter[name]=' . $cName;
        $cities = json_decode(file_get_contents($url));
        $result = (count($cities->result) === 0) ? false : true;

        return $result;

    }


}