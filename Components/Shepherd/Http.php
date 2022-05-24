<?php
// Http.php
// Part of Shepherd Component
// (C) 2022-present Christopher Digno

namespace Components\Shepherd;

class Http {
    public static function redirect($url, $status = 302) {
        header("Location: " . $url, true, $status);
        die;
    }
}
