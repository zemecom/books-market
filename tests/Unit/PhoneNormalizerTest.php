<?php

use PHPUnit\Framework\TestCase;

final class PhoneNormalizerTest extends TestCase
{
    public function testNormalizesRussianPhonePredictably(): void
    {
        $normalizer = new PhoneNormalizer();

        self::assertSame('+79991234567', (string) $normalizer->normalize('+7 (999) 123-45-67'));
    }

    public function testRejectsEmptyPhone(): void
    {
        $normalizer = new PhoneNormalizer();

        $this->expectException(ValidationException::class);
        $normalizer->normalize('');
    }

    public function testRejectsGarbagePhone(): void
    {
        $normalizer = new PhoneNormalizer();

        $this->expectException(ValidationException::class);
        $normalizer->normalize('abc');
    }

    public function testIgnoresSpacesAndHyphens(): void
    {
        $normalizer = new PhoneNormalizer();

        self::assertSame('+79995554433', (string) $normalizer->normalize('8 999-555-44-33'));
    }
}
