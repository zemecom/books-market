<?php

use PHPUnit\Framework\TestCase;

final class TopAuthorsReportServiceTest extends TestCase
{
    public function testReturnsSortedTopAuthors(): void
    {
        $service = new TopAuthorsReportService(
            new FixedTopAuthorsReadRepository([
                ['author_id' => 2, 'author_name' => 'B', 'books_count' => 5, 'publish_year' => 2026],
                ['author_id' => 1, 'author_name' => 'A', 'books_count' => 7, 'publish_year' => 2026],
            ]),
        );

        $result = $service->getTopAuthors(2026);

        self::assertSame(2, count($result));
        self::assertSame(7, $result[0]['books_count']);
        self::assertSame('A', $result[0]['author_name']);
    }
}

final class FixedTopAuthorsReadRepository extends TopAuthorsReadRepository
{
    public function __construct(private array $rows) {}

    public function fetchTopAuthors(?int $year = null, int $limit = 10): array
    {
        return $this->rows;
    }
}
