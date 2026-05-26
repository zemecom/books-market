<?php

use Tests\Support\AcceptanceTester;

final class GuestCanSubscribeToAuthorCest
{
    public function guestCanSubscribeToAuthor(AcceptanceTester $I): void
    {
        $I->amOnPage('/authors/1');
        $I->submitForm('#yw0', [
            'SubscribeAuthorForm[phone]' => '+7 (999) 555-44-33',
        ]);
        $I->see('Subscription created successfully.');
        $I->seeInDatabase('author_subscription', [
            'author_id' => 1,
            'phone_normalized' => '+79995554433',
        ]);
    }
}
