<?php

declare(strict_types=1);

class PhoneNormalizer
{
    public function normalize(string $rawPhone): PhoneNumber
    {
        $digits = preg_replace('/\D+/', '', trim($rawPhone));

        if ($digits === '') {
            throw new ValidationException('Phone is required.');
        }

        if (strlen($digits) === 10) {
            $digits = '7' . $digits;
        } elseif (strlen($digits) === 11 && $digits[0] === '8') {
            $digits = '7' . substr($digits, 1);
        }

        if (!preg_match('/^7\d{10}$/', $digits)) {
            throw new ValidationException('Phone format is invalid.');
        }

        return new PhoneNumber('+' . $digits);
    }
}
