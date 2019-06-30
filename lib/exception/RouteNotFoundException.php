<?php

namespace proton\lib\exception;


class RouteNotFoundException extends BaseException
{
    public $code = 404;
    public $message = 'route is not Found';
    public $errorCode = 999;

    public $shouldToClient = true;
}