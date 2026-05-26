<?php

use PHPUnit\Framework\TestCase;

final class DeleteBookServiceTest extends TestCase
{
    public function testDeletesBookAndAuthorLinks(): void
    {
        $books = new InMemoryBookRepository();
        $book = $books->createBook([
            'title' => 'Delete me',
            'description' => '',
            'isbn' => '333-3-33-333333-3',
            'publish_year' => 2025,
            'published_at' => '2025-01-01',
            'cover_path' => null,
        ]);
        $books->syncAuthors($book->id, [1, 2]);

        $service = new DeleteBookService($books);
        $service->delete($book->id);

        self::assertArrayNotHasKey($book->id, $books->books);
        self::assertArrayNotHasKey($book->id, $books->authorLinks);
    }
}
