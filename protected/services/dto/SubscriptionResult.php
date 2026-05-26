<?php

declare(strict_types=1);

class SubscriptionResult
{
    public function __construct(
        public bool $created,
        public string $message,
        public ?int $subscriptionId,
    ) {}
}
