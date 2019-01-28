<?php

namespace Routeless\Core\RPC;

class Request
{
    protected
        $uri,
        $authUser
    ;
    const HTTP_METHOD_GET  = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_HEAD = 'HEAD';
    const HTTP_METHOD_OPTIONS = 'OPTIONS';

    public static function capture() {
        $request = new static();
        $request->uri = $_SERVER['REQUEST_URI'];

        return $request;
    }

    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function path() {
        return explode('?', $this->uri)[0];
    }

    public function input() {
        return $this->json() ?: $_POST;
    }

    public function json() {
        $data = file_get_contents('php://input');

        return @json_decode($data, true) ?: null;
    }

    public function header($key) {
        return $_SERVER['HTTP_' . str_replace('-', '_', strtoupper($key))] ?? null;
    }

    public function setAuthUser($user) {
        if ($this->authUser) {
            throw new \Exception('can only set auth user once');
        }
        $this->authUser = $user;
    }

    /**
     * @return null|\Application\Models\User
     */
    public function authUser() {
        return $this->authUser ?: null;
    }

}