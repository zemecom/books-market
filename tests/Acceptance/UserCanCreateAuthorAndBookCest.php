<?php

use Tests\Support\AcceptanceTester;

final class UserCanCreateAuthorAndBookCest
{
    public function userCanCreateAuthorAndBook(AcceptanceTester $I): void
    {
        $I->amOnPage('/login');
        $I->submitForm('#yw0', [
            'LoginForm[username]' => 'user',
            'LoginForm[password]' => 'user123',
        ]);

        $I->amOnPage('/author/create');
        $I->submitForm('#yw0', [
            'Author[name]' => 'Vaughn Vernon',
            'Author[bio]' => 'Implementing Domain-Driven Design',
        ]);
        $I->see('Author created successfully.');
        $I->see('Vaughn Vernon');

        $I->fillField('SubscribeAuthorForm[phone]', '+7 (999) 000-00-00');
        $I->click('Subscribe');
        $I->see('Subscription created successfully.');
        $I->dontSee('+7 (999) 000-00-00');
        $I->dontSee('Subscribers');

        $I->amOnPage('/book/create');
        $I->fillField('BookForm[title]', 'Implementing DDD');
        $I->fillField('BookForm[isbn]', '978-0-321-71407-7');
        $I->fillField('BookForm[publishYear]', '2026');
        $I->fillField('BookForm[description]', 'Hands-on DDD guide');
        $I->selectOption('select[name="BookForm[authorIds][]"]', 'Vaughn Vernon');
        $I->click('Save');
        $I->see('Book created successfully.');
        $I->see('Implementing DDD');
        $I->see('978-0-321-71407-7');
        $I->see('2026');
        $I->see('Vaughn Vernon');
    }
}
