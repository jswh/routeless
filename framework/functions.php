<?php
if (!function_exists('dash2Camel')) {
    function dash2Camel($str) {
        $parts = explode('-', $str);
        $parts =array_map(function ($p) {
            $p = ucfirst(strtolower($p));
            return $p;
        }, $parts);

        return implode('', $parts);
    }
}

if (!function_exists('publicMembers')) {
    function publicMembers($obj) {
        if (!is_object($obj)) return [];

        $members = [];
        foreach ($obj as $k => $v) {
            $members[] = $k;
        }
        return $members;
    }
}

if (!function_exists('obj2Array')) {
    function obj2Array($obj) {
        if (!is_object($obj)) return [];
        $members = [];
        foreach ($obj as $k => $v) {
            $members[$k] = $v;
        }
        return $members;
    }
}

if (!function_exists('randStr')) {
    function randStr($len, $chars = 'abcdefghijklmnopqrstuvwxvyABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
        $str = '';
        for ($i = 0; $i < $len; $i ++) {
            $str .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
