<?php

declare(strict_types=1);

class TopAuthorsReportService
{
    public function __construct(private TopAuthorsReadRepository $authors) {}

    public function getTopAuthors(?int $year = null, int $limit = 10): array
    {
        $rows = $this->authors->fetchTopAuthors($year, $limit);
        usort($rows, static function (array $left, array $right): int {
            if ((int) $left['books_count'] === (int) $right['books_count']) {
                return strcmp($left['author_name'], $right['author_name']);
            }

            return (int) $right['books_count'] <=> (int) $left['books_count'];
        });

        return array_slice($rows, 0, $limit);
    }
}
