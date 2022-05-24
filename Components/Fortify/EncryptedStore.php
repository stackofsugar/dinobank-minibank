<?php
// EncryptedStore.php
// Part of Fortify Component
// (C) 2022-present Christopher Digno

namespace Components\Fortify;

use Components\Reminisce\File;

class EncryptedStore {
    private $dbStoreFilename = "/db/ac.db";
    private $key = "3s6v9y)B&E)H@MbQeThWmZq4t7w!z%C*";

    private bool $haveInit = false;
    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function insertPlaintextToDB(string $plaintext) {
        $nonce = random_bytes(24);
        $ciphertext = sodium_crypto_secretbox($plaintext, $nonce, $this->key);

        $DBStream = new File("/db/ac.db", "w");
        $nonceStream = new File("/secret/nb.pem", "w");
        $DBStream->write($ciphertext);
        $nonceStream->write($nonce);
    }

    public function getDBArray() {
        if ((file_exists(dirname(__DIR__, 2) . "/secret/nb.pem")) && (file_exists(dirname(__DIR__, 2) . "/db/ac.db"))) {
            $DBStream = new File("/db/ac.db", "r");
            $nonceStream = new File("/secret/nb.pem", "r");
            $ciphertext = $DBStream->read();
            $nonce = $nonceStream->read();

            try {
                $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $this->key);
            } catch (\SodiumException $ex) {
                return [];
            }
            if ($plaintext === false) {
                return [];
            } else {
                $decoded = json_decode($plaintext, true);
                foreach ($decoded as &$item) {
                    if (!is_array($item)) {
                        $item = json_decode($item, true);
                    }
                }
                return $decoded;
            }
        } else {
            return [];
        }
    }

    public function appendPlaintextToDB(string $plaintext) {
        $DBArray = $this->getDBArray();
        if (!$DBArray) {
            $arrayText = [$plaintext];
            $this->insertPlaintextToDB(json_encode($arrayText));
        } else {
            array_push($DBArray, $plaintext);
            $this->insertPlaintextToDB(json_encode($DBArray));
        }
    }

    public function checkAccNumber(string $accNum) {
        $DBArray = $this->getDBArray();
        foreach ($DBArray as $DBItems) {
            if (strcmp($DBItems["account_no"], $accNum) == 0) {
                return false;
            }
        }
        return true;
    }

    public function checkUsername(string $username) {
        $DBArray = $this->getDBArray();
        foreach ($DBArray as $DBItems) {
            if (strcmp($DBItems["username"], $username) == 0) {
                return false;
            }
        }
        return true;
    }

    public function attempt(string $username, string $password) {
        $DBArray = $this->getDBArray();
        foreach ($DBArray as $DBItems) {
            if (strcmp($DBItems["username"], $username) == 0) {
                if (password_verify($password, $DBItems["password"])) {
                    return $DBItems;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    public function getUser(string $username) {
        $DBArray = $this->getDBArray();
        foreach ($DBArray as $DBItems) {
            if (strcmp($DBItems["username"], $username) == 0) {
                return $DBItems;
            }
        }
        return false;
    }

    public function modifyBalance(string $username, int $modifier) {
        $ret = false;
        $DBArray = $this->getDBArray();
        foreach ($DBArray as &$DBItems) {
            $DBItemCopy = $DBItems;
            if (strcmp($DBItems["username"], $username) == 0) {
                if (($DBItemCopy["balance"] + $modifier) < 0) {
                    break;
                } else {
                    $DBItems["balance"] = $DBItems["balance"] + $modifier;
                    $ret = true;
                    break;
                }
            }
        }
        $this->insertPlaintextToDB(json_encode($DBArray));
        return $ret;
    }

    public function transfer(string $usernameFrom, string $usernameTo, string $amount) {
        if ($this->modifyBalance($usernameFrom, -$amount)) {
            $this->modifyBalance($usernameTo, $amount);
            return true;
        } else return false;
    }

    public function getUsernameByAccNo(string $accNo) {
        $DBArray = $this->getDBArray();
        foreach ($DBArray as $DBItems) {
            if (strcmp($DBItems["account_no"], $accNo) == 0) {
                return $DBItems["username"];
            }
        }
        return null;
    }
}
