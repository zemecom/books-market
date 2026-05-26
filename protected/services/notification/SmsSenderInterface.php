<?php

declare(strict_types=1);

interface SmsSenderInterface
{
    public function send(string $phone, string $message): void;
}
