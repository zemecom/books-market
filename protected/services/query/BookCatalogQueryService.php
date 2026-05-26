<?php

declare(strict_types=1);

class BookCatalogQueryService
{
    public function __construct(private BookCatalogReadRepository $books) {}

    public function getCatalog(array $filters = [], ?int $limit = null, ?int $offset = null): array
    {
        return $this->books->fetchCatalog($filters, $limit, $offset);
    }

    public function countCatalog(array $filters = []): int
    {
        return $this->books->countCatalog($filters);
    }
}
