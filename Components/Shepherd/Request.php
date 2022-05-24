<?php
// Http.php
// Part of Shepherd Component
// (C) 2022-present Christopher Digno

namespace Components\Shepherd;

class Request {
    private $post = [];
    private static $instance;

    private function initPost() {
        $cleanPost = [];
        foreach ($_POST as $postKey => $postValue) {
            $cleanPost[$postKey] = htmlspecialchars($postValue);
        }
        $this->post = $cleanPost;
    }

    public static function capture() {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        self::$instance->initPost();
        return self::$instance;
    }

    public function hasPost() {
        if ($_POST) return true;
        else return false;
    }

    public function __get($name) {
        if (isset($this->post[$name])) {
            return $this->post[$name];
        } else {
            return null;
        }
    }

    public function asArray() {
        if (isset($this->post)) {
            return $this->post;
        } else {
            return [];
        }
    }

    public function old($name) {
        $ret = $this->__get($name);
        if (isset($ret)) return $ret;
        else return "";
    }
}
