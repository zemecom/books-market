<?php

use Tests\Support\AcceptanceTester;

final class UserCanLoginCest
{
    public function userCanLogin(AcceptanceTester $I): void
    {
        $I->amOnPage('/login');
        $I->submitForm('#yw0', [
            'LoginForm[username]' => 'user',
            'LoginForm[password]' => 'user123',
        ]);
        $I->see('Logout');
    }
}
