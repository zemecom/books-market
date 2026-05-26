<?php

declare(strict_types=1);

class PhoneNumber
{
    public function __construct(private string $value) {}

    public function __toString(): string
    {
        return $this->value;
    }
}
