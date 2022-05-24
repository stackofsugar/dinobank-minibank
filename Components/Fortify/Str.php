<?php
// Str.php
// Part of Fortify Component
// (C) 2022-present Christopher Digno
// With contributions of Scott Arciszewski

namespace Components\Fortify;

class Str {
    static function random(int $length = 64, string $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
