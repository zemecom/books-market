<?php

use PHPUnit\Framework\TestCase;

final class NotifyBookSubscribersServiceTest extends TestCase
{
    public function testSendsSmsToUniqueSubscribersAndLogsResults(): void
    {
        $sender = new FakeSmsSender();
        $subscriptions = new InMemorySubscriptionRepository([
            ['phone_normalized' => '+79990000001', 'phone' => '+7 999 000-00-01', 'author_id' => 1],
            ['phone_normalized' => '+79990000001', 'phone' => '+7 999 000-00-01', 'author_id' => 2],
            ['phone_normalized' => '+79990000002', 'phone' => '+7 999 000-00-02', 'author_id' => 2],
        ]);
        $logs = new InMemorySmsNotificationLogRepository();

        $service = new NotifyBookSubscribersService(
            $subscriptions,
            new SubscriberDeduplicator(),
            $sender,
            $logs,
        );

        $result = $service->notify(10, [1, 2], 'DDD in Practice');

        self::assertSame(2, $result->sentCount);
        self::assertSame(0, $result->errorCount);
        self::assertSame(['+79990000001', '+79990000002'], $sender->sentPhones);
        self::assertCount(2, $logs->entries);
        self::assertSame('sent', $logs->entries[0]['status']);
        self::assertSame('New book "DDD in Practice" is now available.', $logs->entries[0]['message']);
    }

    public function testContinuesWhenOneSmsFails(): void
    {
        $sender = new FakeSmsSender(['+79990000001']);
        $subscriptions = new InMemorySubscriptionRepository([
            ['phone_normalized' => '+79990000001', 'phone' => '+7 999 000-00-01', 'author_id' => 1],
            ['phone_normalized' => '+79990000002', 'phone' => '+7 999 000-00-02', 'author_id' => 2],
        ]);
        $logs = new InMemorySmsNotificationLogRepository();

        $service = new NotifyBookSubscribersService(
            $subscriptions,
            new SubscriberDeduplicator(),
            $sender,
            $logs,
        );

        $result = $service->notify(11, [1, 2], 'Patterns');

        self::assertSame(1, $result->sentCount);
        self::assertSame(1, $result->errorCount);
        self::assertCount(2, $logs->entries);
        self::assertSame('error', $logs->entries[0]['status']);
        self::assertSame('sent', $logs->entries[1]['status']);
        self::assertSame('New book "Patterns" is now available.', $logs->entries[0]['message']);
    }
}

final class InMemorySubscriptionRepository extends SubscriptionRepository
{
    public function __construct(private array $subscriptions = []) {}

    public function findByAuthorIds(array $authorIds): array
    {
        return array_values(array_filter($this->subscriptions, static function (array $subscription) use ($authorIds): bool {
            return in_array($subscription['author_id'], $authorIds, true);
        }));
    }
}

final class InMemorySmsNotificationLogRepository extends SmsNotificationLogRepository
{
    public array $entries = [];

    public function log(array $payload): void
    {
        $this->entries[] = $payload;
    }
}
