<?php
/**
 * Created by PhpStorm.
 * User: wcaow
 * Date: 05/28
 * Time: 11:23
 */

namespace Core\RPC;


use Core\Exceptions\HttpException;
use Core\RPC\Request;
use Core\RPC\Response;

abstract class Controller
{
    protected $request, $response;
    final public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->beforeExec();
    }

    protected function beforeExec() { }

    protected function success($data = null, $msg = 'ok', int $code = 200) {
        return array_filter(compact('code', 'msg', 'data'));
    }

    /**
     * @return \Application\Models\User
     * @throws HttpException
     */
    protected function checkAuth() {
        $user = $this->request->authUser();
        if (!$user) {
            throw new HttpException(403, '请先登录');
        }
        return $user;
    }
}