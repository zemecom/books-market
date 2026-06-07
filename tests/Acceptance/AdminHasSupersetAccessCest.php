<?php

use Tests\Support\AcceptanceTester;

final class AdminHasSupersetAccessCest
{
    public function adminHasSupersetAccess(AcceptanceTester $I): void
    {
        $I->amOnPage('/login');
        $I->submitForm('#yw0', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin123',
        ]);

        $I->amOnPage('/book/create');
        $I->see('Create Book');
        $I->amOnPage('/author/create');
        $I->see('Create Author');

        $I->amOnPage('/log/index');
        $I->see('SMS Notification Logs');

        // Test admin can delete subscription
        $I->amOnPage('/author/create');
        $I->submitForm('#yw0', [
            'Author[name]' => 'Author For Delete Test',
            'Author[bio]' => 'bio',
        ]);
        $I->fillField('SubscribeAuthorForm[phone]', '+7 (999) 000-00-00');
        $I->click('Subscribe');
        $I->see('+7 (999) 000-00-00');

        $I->click('Edit', 'table.table');
        $I->see('Edit Subscription');
        $I->fillField('UpdateSubscriptionForm[phone]', '+7 (999) 111-22-33');
        $I->click('Save');
        $I->see('Subscription updated successfully.');
        $I->see('+7 (999) 111-22-33');

        $I->click('Delete', 'table.table');
        $I->see('Subscription deleted successfully.');
        $I->dontSee('+7 (999) 111-22-33', 'table.table');
    }
}
