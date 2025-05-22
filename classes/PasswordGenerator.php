<?php


class PasswordGenerator {
    public function generate($length, $lowercase = 0, $uppercase = 0, $numbers = 0, $special = 0) {
        $password = '';
        $characters = [
            'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
            'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'numbers'   => '0123456789',
            'special'   => '!@#$%^&*()_+-=[]{};:,.<>?'
        ];

        // Add selected characters
        $password .= $this->getRandomCharacters($characters['lowercase'], $lowercase);
        $password .= $this->getRandomCharacters($characters['uppercase'], $uppercase);
        $password .= $this->getRandomCharacters($characters['numbers'], $numbers);
        $password .= $this->getRandomCharacters($characters['special'], $special);

        $usedLength = $lowercase + $uppercase + $numbers + $special;

        // Fill the rest if length not reached
        if ($usedLength < $length) {
            $allChars = $characters['lowercase'] . $characters['uppercase'] . $characters['numbers'] . $characters['special'];
            $password .= $this->getRandomCharacters($allChars, $length - $usedLength);
        }

        // Shuffle to randomize final password
        return str_shuffle($password);
    }

    private function getRandomCharacters($chars, $count) {
        $result = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $count; $i++) {
            $result .= $chars[rand(0, $max)];
        }
        return $result;
    }
}
