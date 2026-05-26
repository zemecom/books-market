<?php

declare(strict_types=1);

class FakeSmsSender implements SmsSenderInterface
{
    public array $sentPhones = [];

    public function __construct(private array $failingPhones = []) {}

    public function send(string $phone, string $message): void
    {
        if (in_array($phone, $this->failingPhones, true)) {
            throw new RuntimeException('Fake SMS transport failure.');
        }

        $this->sentPhones[] = $phone;
    }
}
