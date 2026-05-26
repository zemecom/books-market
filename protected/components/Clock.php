<?php

declare(strict_types=1);

class Clock extends CApplicationComponent
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}
