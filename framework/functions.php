<?php
if (!function_exists('dash2Camel')) {
    function dash2Camel($str)
    {
        $parts = explode('-', $str);
        $parts = array_map(function ($p) {
            return str2Camel($p);
        }, $parts);

        return implode('', $parts);
    }
}

if (!function_exists('str2Camel')) {
    function str2Camel($str)
    {
        return ucfirst(strtolower($str));
    }
}

if (!function_exists('publicMembers')) {
    function publicMembers($obj)
    {
        if (!is_object($obj)) return [];

        $members = [];
        foreach ($obj as $k => $v) {
            $members[] = $k;
        }
        return $members;
    }
}

if (!function_exists('obj2Array')) {
    function obj2Array($obj)
    {
        if (!is_object($obj)) return [];
        $members = [];
        foreach ($obj as $k => $v) {
            $members[$k] = $v;
        }
        return $members;
    }
}

if (!function_exists('randStr')) {
    function randStr($len, $chars = 'abcdefghijklmnopqrstuvwxvyABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $str = '';
        $charsLen = mb_strlen($chars);

        for ($i = 0; $i < $len; $i++) {
            $pos = rand(0, $charsLen - 1);
            $str .= mb_substr($chars, $pos, 1);
        }
        return $str;
    }
}

if (!function_exists('dataShouldExist')) {
    function dataShouldExist($data, $what = 'data')
    {
        if (!$data) throw new \Routeless\Core\Exceptions\HttpException(404, "$what not found");
    }
}
