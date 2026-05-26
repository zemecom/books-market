<?php

use PHPUnit\Framework\TestCase;

final class UpdateBookServiceTest extends TestCase
{
    public function testUpdatesBookAndResyncsAuthorsWithoutNotifier(): void
    {
        $books = new InMemoryBookRepository();
        $book = $books->createBook([
            'title' => 'Old',
            'description' => 'Old description',
            'isbn' => '111-1-11-111111-1',
            'publish_year' => 2025,
            'published_at' => '2025-01-01',
            'cover_path' => null,
        ]);
        $books->syncAuthors($book->id, [1]);

        $service = new UpdateBookService(
            $books,
            new InMemoryAuthorRepository([1 => 'Evans', 2 => 'Fowler']),
            new NullCoverImageStorage(),
            new BookAuthorPolicy(),
        );
        $form = new BookForm();
        $form->title = 'New';
        $form->description = 'New description';
        $form->isbn = '222-2-22-222222-2';
        $form->publishYear = '2026';
        $form->authorIds = [2];

        $updated = $service->update($book->id, $form);

        self::assertSame('New', $updated->title);
        self::assertSame('222-2-22-222222-2', $updated->isbn);
        self::assertSame(2026, (int) $updated->publish_year);
        self::assertSame('2026-01-01', $updated->published_at);
        self::assertSame([2], $books->authorLinks[$book->id]);
    }
}
