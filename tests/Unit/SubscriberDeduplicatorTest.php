<?php

use PHPUnit\Framework\TestCase;

final class SubscriberDeduplicatorTest extends TestCase
{
    public function testDeduplicatesSamePhoneAcrossAuthors(): void
    {
        $deduplicator = new SubscriberDeduplicator();
        $subscriptions = [
            ['phone_normalized' => '+79990000001', 'author_id' => 1],
            ['phone_normalized' => '+79990000001', 'author_id' => 2],
        ];

        $result = $deduplicator->deduplicate($subscriptions);

        self::assertCount(1, $result);
        self::assertSame('+79990000001', $result[0]['phone_normalized']);
    }

    public function testKeepsDifferentPhones(): void
    {
        $deduplicator = new SubscriberDeduplicator();
        $subscriptions = [
            ['phone_normalized' => '+79990000001'],
            ['phone_normalized' => '+79990000002'],
        ];

        self::assertCount(2, $deduplicator->deduplicate($subscriptions));
    }

    public function testReturnsEmptyArrayForEmptyInput(): void
    {
        $deduplicator = new SubscriberDeduplicator();

        self::assertSame([], $deduplicator->deduplicate([]));
    }
}
