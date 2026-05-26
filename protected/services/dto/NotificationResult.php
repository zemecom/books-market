<?php

declare(strict_types=1);

class NotificationResult
{
    public function __construct(
        public int $sentCount,
        public int $errorCount,
        public array $phones,
    ) {}
}
