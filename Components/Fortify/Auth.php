<?php
// EncryptedStore.php
// Part of Fortify Component
// (C) 2022-present Christopher Digno

namespace Components\Fortify;

use Components\Fortify\EncryptedStore as ES;
use Components\Reminisce\Session;

class Auth {
    public static function store(array $data) {
        $session = Session::getInstance();
        $DBStore = ES::getInstance();

        $requiredFields = [
            "fullname" => false,
            "username" => false,
            "password" => false,
            "password-confirm" => false,
        ];

        foreach ($data as $datKey => $datValue) {
            foreach ($requiredFields as $reqKey => $reqValue) {
                if ($datKey == $reqKey && !empty($datValue)) {
                    $requiredFields[$reqKey] = true;
                }
            }
        }

        // Validate required
        foreach ($requiredFields as $key => $value) {
            if ($value == false) {
                $session->flash("Semua field harus diisi!");
                return false;
            }
        }

        // Validate username: no spaces
        if (strpos($data["username"], " ") !== false) {
            $session->flash("Username tidak boleh mengandung spasi!");
            return false;
        }

        // Validate password: same as confirmation
        if (strcmp($data["password"], $data["password-confirm"]) != 0) {
            $session->flash("Password berbeda dengan konfirmasi!");
            return false;
        }

        // Validate username: unique
        if (!$DBStore->checkUsername($data["username"])) {
            $session->flash("Username yang anda pilih sudah dipakai!");
            return false;
        }

        // Add field, generate norek
        $data["balance"] = 0;
        $data["account_no"] = "6969" . strval(random_int(10000000, 99999999));
        while (!$DBStore->checkAccNumber($data["account_no"])) {
            $data["account_no"] = "6969" . strval(random_int(10000000, 99999999));
        }

        // Store
        unset($data["password-confirm"]);
        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
        $DBStore->appendPlaintextToDB(json_encode($data));

        $session->isLoggedIn = true;
        $session->user = $data;

        return true;
    }

    public static function authenticate(string $username, string $password) {
        $DBStore = ES::getInstance();
        $session = Session::getInstance();

        $auth = $DBStore->attempt($username, $password);
        if ($auth === false) {
            $session->flash("Username atau password anda salah!");
            return false;
        } else {
            $session->isLoggedIn = true;
            $session->user = $auth;
            return true;
        }
    }

    public static function destroy() {
        $session = Session::getInstance();
        $session->destroy();
    }

    public static function guest() {
        $session = Session::getInstance();
        if ($session->isLoggedIn == true) {
            return false;
        }
        return true;
    }

    public static function getBalance() {
        $DBStore = ES::getInstance();
        $session = Session::getInstance();

        if (!self::guest()) {
            $user = $DBStore->getUser($session->user["username"]);
            if ($user === false) {
                return null;
            } else {
                return $user["balance"];
            }
        } else {
            return null;
        }
    }

    public static function modifyBalance($modifier) {
        $DBStore = ES::getInstance();
        $session = Session::getInstance();

        $DBStore->modifyBalance($session->user["username"], $modifier);
    }

    public static function transfer($username, $amount) {
        $DBStore = ES::getInstance();
        $session = Session::getInstance();

        return $DBStore->transfer($session->user["username"], $username, $amount);
    }
}
