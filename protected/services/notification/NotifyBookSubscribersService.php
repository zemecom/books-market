<?php

declare(strict_types=1);

class NotifyBookSubscribersService
{
    public function __construct(
        private SubscriptionRepository $subscriptions,
        private SubscriberDeduplicator $deduplicator,
        private SmsSenderInterface $sender,
        private SmsNotificationLogRepository $logs,
    ) {}

    public function notify(int $bookId, array $authorIds, string $bookTitle): NotificationResult
    {
        $subscriptions = $this->subscriptions->findByAuthorIds($authorIds);
        $subscriptions = $this->deduplicator->deduplicate($subscriptions);

        $sentCount = 0;
        $errorCount = 0;
        $phones = [];
        $message = sprintf('New book "%s" is now available.', $bookTitle);

        foreach ($subscriptions as $subscription) {
            $phone = $subscription['phone_normalized'];
            try {
                $this->sender->send($phone, $message);
                $this->logs->log([
                    'book_id' => $bookId,
                    'phone' => $phone,
                    'message' => $message,
                    'status' => 'sent',
                    'error_text' => null,
                ]);
                $sentCount++;
                $phones[] = $phone;
            } catch (Throwable $exception) {
                $this->logs->log([
                    'book_id' => $bookId,
                    'phone' => $phone,
                    'message' => $message,
                    'status' => 'error',
                    'error_text' => $exception->getMessage(),
                ]);
                $errorCount++;
            }
        }

        return new NotificationResult($sentCount, $errorCount, $phones);
    }
}
