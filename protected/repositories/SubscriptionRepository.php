<?php

declare(strict_types=1);

class SubscriptionRepository
{
    public function findByAuthorIds(array $authorIds): array
    {
        if ($authorIds === []) {
            return [];
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition('author_id', $authorIds);
        $rows = AuthorSubscription::model()->findAll($criteria);

        return array_map(static function (AuthorSubscription $subscription): array {
            return [
                'id' => (int) $subscription->id,
                'author_id' => (int) $subscription->author_id,
                'phone' => $subscription->phone,
                'phone_normalized' => $subscription->phone_normalized,
            ];
        }, $rows);
    }

    public function findExisting(int $authorId, string $phoneNormalized): ?AuthorSubscription
    {
        $subscription = AuthorSubscription::model()->findByAttributes([
            'author_id' => $authorId,
            'phone_normalized' => $phoneNormalized,
        ]);

        return $subscription instanceof AuthorSubscription ? $subscription : null;
    }

    public function create(int $authorId, string $phone, string $phoneNormalized): AuthorSubscription
    {
        $subscription = new AuthorSubscription();
        $subscription->author_id = $authorId;
        $subscription->phone = $phone;
        $subscription->phone_normalized = $phoneNormalized;

        if (!$subscription->save()) {
            throw new ValidationException(CHtml::errorSummary($subscription));
        }

        return $subscription;
    }
}
