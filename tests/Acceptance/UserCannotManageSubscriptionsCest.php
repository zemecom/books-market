<?php

use Tests\Support\AcceptanceTester;

final class UserCannotManageSubscriptionsCest
{
    public function userCannotManageSubscriptions(AcceptanceTester $I): void
    {
        $I->haveInDatabase('author_subscription', [
            'author_id' => 1,
            'phone' => '+7 (999) 555-44-33',
            'phone_normalized' => '+79995554433',
            'created_at' => '2026-05-26 00:00:00',
            'updated_at' => '2026-05-26 00:00:00',
        ]);

        $I->amOnPage('/login');
        $I->submitForm('#yw0', [
            'LoginForm[username]' => 'user',
            'LoginForm[password]' => 'user123',
        ]);

        $I->amOnPage('/authors/1');
        $I->dontSee('Subscribers');
        $I->dontSee('+7 (999) 555-44-33');

        $I->amOnPage('/author/updateSubscription/1');
        $source = $I->grabPageSource();
        $I->assertTrue(str_contains($source, 'Login') || str_contains($source, 'not authorized') || str_contains($source, '403'));
    }
}
