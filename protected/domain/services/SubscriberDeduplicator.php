<?php

declare(strict_types=1);

class SubscriberDeduplicator
{
    public function deduplicate(array $subscriptions): array
    {
        $unique = [];

        foreach ($subscriptions as $subscription) {
            $phone = $subscription['phone_normalized'];
            if (!array_key_exists($phone, $unique)) {
                $unique[$phone] = $subscription;
            }
        }

        return array_values($unique);
    }
}
