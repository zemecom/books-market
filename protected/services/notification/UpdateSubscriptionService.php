<?php

declare(strict_types=1);

class UpdateSubscriptionService
{
    public function __construct(
        private SubscriptionRepository $subscriptions,
        private PhoneNormalizer $normalizer,
    ) {}

    public function update(int $subscriptionId, UpdateSubscriptionForm $form): void
    {
        $subscription = AuthorSubscription::model()->findByPk($subscriptionId);
        if ($subscription === null) {
            throw new CHttpException(404, 'Subscription not found.');
        }

        $phoneNormalized = (string) $this->normalizer->normalize($form->phone);

        if ($phoneNormalized !== $subscription->phone_normalized) {
            $existing = $this->subscriptions->findExisting((int) $subscription->author_id, $phoneNormalized);
            if ($existing !== null) {
                throw new ValidationException('This phone is already subscribed to this author.');
            }
        }

        $subscription->phone = $form->phone;
        $subscription->phone_normalized = $phoneNormalized;

        if (!$subscription->save()) {
            throw new ValidationException(CHtml::errorSummary($subscription));
        }
    }
}
