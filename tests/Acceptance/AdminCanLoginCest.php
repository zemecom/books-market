<?php

use Tests\Support\AcceptanceTester;

final class AdminCanLoginCest
{
    public function adminCanLogin(AcceptanceTester $I): void
    {
        $I->amOnPage('/login');
        $I->submitForm('#yw0', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin123',
        ]);
        $I->see('Logout');
        $I->see('Authors');
    }
}
