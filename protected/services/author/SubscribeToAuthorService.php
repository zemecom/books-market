<?php

declare(strict_types=1);

class SubscribeToAuthorService
{
    public function __construct(
        private AuthorRepository $authors,
        private SubscriptionRepository $subscriptions,
        private PhoneNormalizer $normalizer,
    ) {}

    public function subscribe(int $authorId, SubscribeAuthorForm $form): SubscriptionResult
    {
        if (!$this->authors->exists($authorId)) {
            throw new CHttpException(404, 'Author not found.');
        }

        $phone = (string) $this->normalizer->normalize($form->phone);
        $existing = $this->subscriptions->findExisting($authorId, $phone);
        if ($existing !== null) {
            return new SubscriptionResult(false, 'This phone is already subscribed to the author.', (int) $existing->id);
        }

        try {
            $subscription = $this->subscriptions->create($authorId, $form->phone, $phone);
        } catch (CDbException $exception) {
            $existing = $this->subscriptions->findExisting($authorId, $phone);
            if ($existing !== null) {
                return new SubscriptionResult(false, 'This phone is already subscribed to the author.', (int) $existing->id);
            }

            throw $exception;
        }

        return new SubscriptionResult(true, 'Subscription created successfully.', (int) $subscription->id);
    }
}
