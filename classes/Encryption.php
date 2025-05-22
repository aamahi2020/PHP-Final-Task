<?php

class Encryption {
    private $cipher = "AES-256-CBC";
    private $key;
    private $iv;

    // Constructor: the key should be the user's plain password
    public function __construct($userPlainPassword) {
        $this->key = hash('sha256', $userPlainPassword); // convert password to 256-bit key
        $this->iv = substr($this->key, 0, 16); // IV must be 16 bytes
    }

    // Encrypt the password
    public function encrypt($data) {
        return openssl_encrypt($data, $this->cipher, $this->key, 0, $this->iv);
    }

    // Decrypt the password
    public function decrypt($data) {
        return openssl_decrypt($data, $this->cipher, $this->key, 0, $this->iv);
    }
}

?>
