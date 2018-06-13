<?php

include_once ROOT . "/core/traits/common.php";


/**
 * Запускаем начальный метод sendRequest()
 */


class app
{

    /**
     * @param array $request $_REQUEST
     */

    static function init($request)
    {

        $controllerName = '\BX24\Ctrl\bot';
        $methodName = 'sendRequest';

        $controller = new $controllerName($request);

        if (method_exists($controller, $methodName)) {

            $controller->$methodName($request);

        } else {

            trigger_error("Cant find method");

        }
    }

}