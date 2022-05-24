<?php
// File.php
// Part of Reminisce Component
// (C) 2022-present Christopher Digno

namespace Components\Reminisce;

class File {
    private $filename;
    private $streamInstance;

    public function __construct($fname, $method = "r+") {
        $this->filename = dirname(__DIR__, 2) . $fname;
        $this->streamInstance = fopen($this->filename, $method);
    }

    public function __destruct() {
        fclose($this->streamInstance);
    }

    public function read() {
        return fread($this->streamInstance, filesize($this->filename));
    }

    public function readArray() {
        return json_decode($this->read(), true);
    }

    public function write(string $data) {
        fwrite($this->streamInstance, $data);
    }

    public function clear() {
        ftruncate($this->streamInstance, 0);
        rewind($this->streamInstance);
    }
}
