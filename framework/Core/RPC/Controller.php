<?php

namespace Routeless\Core\RPC;

use Routeless\Contracts\Authorizable;
use Routeless\Core\Exceptions\HttpException;

abstract class Controller
{
    protected $request, $response;

    final public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->beforeExec();
    }

    protected function beforeExec()
    {
    }

    protected function success($data = null, $msg = 'ok', int $code = 200)
    {
        return array_filter(compact('code', 'msg', 'data'));
    }

    /**
     * @return Authorizable
     * @throws HttpException
     */
    protected function checkAuth()
    {
        $user = $this->request->authUser();
        if (!$user) {
            throw new HttpException(403, '请先登录');
        }
        return $user;
    }
}
