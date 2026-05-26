<?php

use Tests\Support\AcceptanceTester;

final class TopAuthorsReportIsPublicCest
{
    public function topAuthorsReportIsPublic(AcceptanceTester $I): void
    {
        $I->amOnPage('/report/topAuthors?year=2026');
        $I->see('Top Authors Report');
        $I->see('Eric Evans');
        $I->see('2');
    }
}
