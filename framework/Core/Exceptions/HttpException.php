<?php

namespace Routeless\Core\Exceptions;

class HttpException extends \Exception
{
    public function __construct($code = 0, $message = '')
    {
        parent::__construct($message, $code);
    }
}