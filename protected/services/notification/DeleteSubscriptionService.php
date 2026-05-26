<?php

declare(strict_types=1);

class DeleteSubscriptionService
{
    public function __construct(private SubscriptionRepository $subscriptions) {}

    public function delete(int $id): void
    {
        $subscription = AuthorSubscription::model()->findByPk($id);
        if ($subscription === null) {
            throw new CHttpException(404, 'Subscription not found.');
        }

        if (!$subscription->delete()) {
            throw new RuntimeException('Failed to delete subscription.');
        }
    }
}
