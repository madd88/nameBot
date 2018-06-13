<?php

namespace BX24\Ctrl;

class ctrl
{
    public $model;
    public $request = [];

    function __construct($request){

        $this->request = $request;

        $this->model = new \BX24\Model\imBot($request);
    }
}