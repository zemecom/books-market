<?php

use Tests\Support\AcceptanceTester;

final class SmsIsTriggeredWhenBookCreatedCest
{
    public function smsIsTriggeredWhenBookCreated(AcceptanceTester $I): void
    {
        $I->haveInDatabase('author_subscription', [
            'author_id' => 1,
            'phone' => '+7 (999) 888-77-66',
            'phone_normalized' => '+79998887766',
            'created_at' => '2026-05-26 00:00:00',
            'updated_at' => '2026-05-26 00:00:00',
        ]);

        $I->amOnPage('/login');
        $I->submitForm('#yw0', [
            'LoginForm[username]' => 'user',
            'LoginForm[password]' => 'user123',
        ]);

        $I->amOnPage('/book/create');
        $I->submitForm('#yw0', [
            'BookForm[title]' => 'Notification Driven',
            'BookForm[isbn]' => '978-0-321-80003-9',
            'BookForm[description]' => 'Test notification flow',
            'BookForm[publishYear]' => '2026',
            'BookForm[authorIds][]' => ['1'],
        ]);

        $I->see('Book created successfully.');
        $I->seeInDatabase('sms_notification_log', [
            'phone' => '+79998887766',
            'status' => 'sent',
        ]);
    }
}
