<?php

use Tests\Support\AcceptanceTester;

final class GuestCanViewBooksCest
{
    public function guestCanViewCatalogAndBook(AcceptanceTester $I): void
    {
        $I->amOnPage('/book/index');
        $I->see('Books Catalog');
        $I->see('Strategic Design');
        $I->click('Strategic Design');
        $I->see('Strategic Design');
        $I->see('Eric Evans');
    }
}
