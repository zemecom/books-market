<?php

declare(strict_types=1);

class ServiceContainer extends CApplicationComponent
{
    private array $instances = [];

    public function bookRepository(): BookRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new BookRepository());
    }

    public function authorRepository(): AuthorRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new AuthorRepository());
    }

    public function subscriptionRepository(): SubscriptionRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new SubscriptionRepository());
    }

    public function smsNotificationLogRepository(): SmsNotificationLogRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new SmsNotificationLogRepository());
    }

    public function bookCatalogReadRepository(): BookCatalogReadRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new BookCatalogReadRepository());
    }

    public function bookViewReadRepository(): BookViewReadRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new BookViewReadRepository());
    }

    public function authorViewReadRepository(): AuthorViewReadRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new AuthorViewReadRepository());
    }

    public function topAuthorsReadRepository(): TopAuthorsReadRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new TopAuthorsReadRepository());
    }

    public function coverImageStorage(): CoverImageStorageInterface
    {
        return $this->shared(__FUNCTION__, static fn() => new LocalCoverImageStorage(Yii::app()->params['coverUploadDir']));
    }

    public function smsSender(): SmsSenderInterface
    {
        return $this->shared(__FUNCTION__, function () {
            if (Yii::app()->params['smsTransport'] === 'smpilot') {
                return new SmsPilotSender(
                    apiKey: Yii::app()->params['smsPilotApiKey'],
                    sender: Yii::app()->params['smsPilotSender'],
                    testMode: (bool) Yii::app()->params['smsPilotTestMode'],
                );
            }

            return new FakeSmsSender();
        });
    }

    public function notifyBookSubscribersService(): NotifyBookSubscribersService
    {
        return $this->shared(__FUNCTION__, fn() => new NotifyBookSubscribersService(
            $this->subscriptionRepository(),
            new SubscriberDeduplicator(),
            $this->smsSender(),
            $this->smsNotificationLogRepository(),
        ));
    }

    public function createBookService(): CreateBookService
    {
        return $this->shared(__FUNCTION__, fn() => new CreateBookService(
            $this->bookRepository(),
            $this->authorRepository(),
            $this->coverImageStorage(),
            $this->notifyBookSubscribersService(),
            new BookAuthorPolicy(),
        ));
    }

    public function updateBookService(): UpdateBookService
    {
        return $this->shared(__FUNCTION__, fn() => new UpdateBookService(
            $this->bookRepository(),
            $this->authorRepository(),
            $this->coverImageStorage(),
            new BookAuthorPolicy(),
        ));
    }

    public function deleteBookService(): DeleteBookService
    {
        return $this->shared(__FUNCTION__, fn() => new DeleteBookService($this->bookRepository()));
    }

    public function deleteAuthorService(): DeleteAuthorService
    {
        return $this->shared(__FUNCTION__, fn() => new DeleteAuthorService($this->authorRepository()));
    }

    public function subscribeToAuthorService(): SubscribeToAuthorService
    {
        return $this->shared(__FUNCTION__, fn() => new SubscribeToAuthorService(
            $this->authorRepository(),
            $this->subscriptionRepository(),
            new PhoneNormalizer(),
        ));
    }

    public function updateSubscriptionService(): UpdateSubscriptionService
    {
        return $this->shared(__FUNCTION__, fn() => new UpdateSubscriptionService(
            $this->subscriptionRepository(),
            new PhoneNormalizer(),
        ));
    }

    public function deleteSubscriptionService(): DeleteSubscriptionService
    {
        return $this->shared(__FUNCTION__, fn() => new DeleteSubscriptionService($this->subscriptionRepository()));
    }

    public function bookCatalogQueryService(): BookCatalogQueryService
    {
        return $this->shared(__FUNCTION__, fn() => new BookCatalogQueryService($this->bookCatalogReadRepository()));
    }

    public function bookViewQueryService(): BookViewQueryService
    {
        return $this->shared(__FUNCTION__, fn() => new BookViewQueryService($this->bookViewReadRepository()));
    }

    public function authorViewQueryService(): AuthorViewQueryService
    {
        return $this->shared(__FUNCTION__, fn() => new AuthorViewQueryService($this->authorViewReadRepository()));
    }

    public function topAuthorsReportService(): TopAuthorsReportService
    {
        return $this->shared(__FUNCTION__, fn() => new TopAuthorsReportService($this->topAuthorsReadRepository()));
    }

    public function smsLogReadRepository(): SmsLogReadRepository
    {
        return $this->shared(__FUNCTION__, static fn() => new SmsLogReadRepository());
    }

    public function smsLogQueryService(): SmsLogQueryService
    {
        return $this->shared(__FUNCTION__, fn() => new SmsLogQueryService($this->smsLogReadRepository()));
    }

    private function shared(string $key, callable $factory)
    {
        if (!array_key_exists($key, $this->instances)) {
            $this->instances[$key] = $factory();
        }

        return $this->instances[$key];
    }
}
