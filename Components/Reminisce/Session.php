<?php
// Session.php
// Part of Reminisce Component
// (C) 2022-present Christopher Digno
// With contributions of linblow@hotmail.fr

namespace Components\Reminisce;

use Components\Shepherd\Http;

class Session {
    const STARTED = true;
    const NOT_STARTED = false;

    private $is_started = self::NOT_STARTED;
    private static $instance;
    private $flashData;

    private function startIf() {
        if ($this->is_started == self::NOT_STARTED) {
            $this->is_started = session_start([
                "name" => "DinoBank_Sesh",
                "cookie_lifetime" => 7200,
                "sid_length" => 128
            ]);
        }
        return $this->is_started;
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        self::$instance->startIf();
        return self::$instance;
    }

    public function __set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function push($key, $value) {
        $this->__set($key, $value);
    }

    public function __get($name) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    public function get($name) {
        return $this->__get($name);
    }

    public function isSet($name) {
        return isset($_SESSION[$name]);
    }

    public function remove($name) {
        unset($_SESSION[$name]);
    }

    public function invalidate() {
        session_unset();
        session_regenerate_id(false);
        $newSession = session_id();
        session_write_close();
        session_id($newSession);
        unset($instance);
        self::getInstance();
    }

    public function invalidateAndRedirect($url, $status = 302) {
        $this->invalidate();
        Http::redirect($url, $status);
    }

    public function destroy() {
        if ($this->is_started == self::STARTED) {
            $this->is_started = !session_destroy();
            unset($_SESSION);

            return !$this->is_started;
        }
        return false;
    }

    public function dump() {
        return [
            "id" => session_id(),
            "is_started" => $this->is_started,
            "variables" => $_SESSION ?? null,
        ];
    }

    public function flash($data) {
        $this->flashData = $data;
    }

    public function getFlash() {
        if (isset($this->flashData)) {
            $ret = $this->flashData;
            unset($this->flashData);
            return $ret;
        } else {
            return null;
        }
    }

    public function hasFlash() {
        if (isset($this->flashData)) return true;
        else return false;
    }
}
