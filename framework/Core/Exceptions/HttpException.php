<?php
namespace Core\Exceptions;

class HttpException extends \Exception
{
    public function __construct($code = 0, $message = '')
    {
        parent::__construct($message, $code);
    }

    public static function dataShouldExist($data, $what = 'data') {
        if (!$data) throw new HttpException(404, "$what not exist");
    }
}