<?php

declare(strict_types=1);

class SmsLogQueryService
{
    public function __construct(private SmsLogReadRepository $logs) {}

    public function getLogs(): array
    {
        return $this->logs->fetchLogs();
    }
}
