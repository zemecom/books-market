<?php

use Tests\Support\AcceptanceTester;

final class GuestCannotCreateBookCest
{
    public function guestCannotCreateBook(AcceptanceTester $I): void
    {
        $I->amOnPage('/book/create');
        $source = $I->grabPageSource();
        $I->assertTrue(str_contains($source, 'Login') || str_contains($source, 'not authorized'));

        $I->amOnPage('/author/create');
        $source = $I->grabPageSource();
        $I->assertTrue(str_contains($source, 'Login') || str_contains($source, 'not authorized'));
    }
}
