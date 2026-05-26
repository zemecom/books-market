<?php

use PHPUnit\Framework\TestCase;

final class BookFormTest extends TestCase
{
    public function testRequiresIsbnAndPublishYear(): void
    {
        $form = new BookForm();
        $form->title = 'Domain-Driven Design';
        $form->authorIds = [1];

        self::assertFalse($form->validate());
        self::assertArrayHasKey('isbn', $form->getErrors());
        self::assertArrayHasKey('publishYear', $form->getErrors());
    }

    public function testRejectsFuturePublishYear(): void
    {
        $form = new BookForm();
        $form->title = 'Future Book';
        $form->isbn = '123456789';
        $form->authorIds = [1];

        $futureYear = (int) Yii::app()->clock->now()->format('Y') + 1;
        $form->publishYear = (string) $futureYear;

        self::assertFalse($form->validate());
        self::assertArrayHasKey('publishYear', $form->getErrors());
        self::assertSame('Publish year cannot be in the future.', $form->getErrors('publishYear')[0]);
    }
}
