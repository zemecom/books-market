<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class BookQueryServicesTest extends TestCase
{
    public function testCatalogQueryReturnsReadRows(): void
    {
        $service = new BookCatalogQueryService(new FixedBookCatalogReadRepository([
            ['id' => 1, 'title' => 'DDD', 'isbn' => '978-0-321-12521-7', 'publish_year' => 2026, 'authors' => 'Evans', 'cover_url' => '/uploads/a.jpg'],
        ]));

        $rows = $service->getCatalog();

        self::assertSame('DDD', $rows[0]['title']);
        self::assertSame('978-0-321-12521-7', $rows[0]['isbn']);
        self::assertSame(2026, $rows[0]['publish_year']);
    }

    public function testBookViewQueryReturnsReadModel(): void
    {
        $service = new BookViewQueryService(new FixedBookViewReadRepository([
            'id' => 1,
            'title' => 'Patterns',
            'isbn' => '978-0-201-63361-0',
            'publish_year' => 1994,
            'authors' => [['id' => 1, 'name' => 'GOF']],
            'cover_url' => '/uploads/patterns.jpg',
        ]));

        $book = $service->getById(1);

        self::assertSame('/uploads/patterns.jpg', $book['cover_url']);
        self::assertSame('978-0-201-63361-0', $book['isbn']);
        self::assertSame(1994, $book['publish_year']);
        self::assertSame('GOF', $book['authors'][0]['name']);
    }
}

final class FixedBookCatalogReadRepository extends BookCatalogReadRepository
{
    public function __construct(private array $rows) {}

    public function fetchCatalog(array $filters = [], ?int $limit = null, ?int $offset = null): array
    {
        return $this->rows;
    }

    public function countCatalog(array $filters = []): int
    {
        return count($this->rows);
    }
}

final class FixedBookViewReadRepository extends BookViewReadRepository
{
    public function __construct(private array $row) {}

    public function findById(int $bookId): array
    {
        return $this->row;
    }
}
