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

    public function guestKeepsBooksItemsPerPageAfterRefresh(AcceptanceTester $I): void
    {
        $I->amOnPage('/book/index?perPage=25');
        $I->seeOptionIsSelected('#perPageSelect', '25');

        $I->amOnPage('/book/index');
        $I->seeOptionIsSelected('#perPageSelect', '25');
    }

    public function guestKeepsAuthorsItemsPerPageAfterRefresh(AcceptanceTester $I): void
    {
        $I->amOnPage('/author/index?perPage=10');
        $I->seeOptionIsSelected('#perPageSelect', '10');

        $I->amOnPage('/author/index');
        $I->seeOptionIsSelected('#perPageSelect', '10');
    }
}
