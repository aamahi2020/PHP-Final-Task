<?php
//PasswordGenerator.php

class PasswordGenerator {
    public function generate($length, $lowercase = 0, $uppercase = 0, $numbers = 0, $special = 0) {
        $password = '';
        $chars = [
            'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
            'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'numbers'   => '0123456789',
            'special'   => '!@#$%^&*()_+-=[]{};:,.<>?'
        ];

        $all = '';

        $password .= $this->pickRandom($chars['lowercase'], $lowercase);
        $password .= $this->pickRandom($chars['uppercase'], $uppercase);
        $password .= $this->pickRandom($chars['numbers'], $numbers);
        $password .= $this->pickRandom($chars['special'], $special);

        $used = $lowercase + $uppercase + $numbers + $special;

        if ($used < $length) {
            $all .= $chars['lowercase'] . $chars['uppercase'] . $chars['numbers'] . $chars['special'];
            $password .= $this->pickRandom($all, $length - $used);
        }

        return str_shuffle($password);
    }

    private function pickRandom($characters, $count) {
        $result = '';
        for ($i = 0; $i < $count; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $result .= $characters[$index];
        }
        return $result;
    }
}
