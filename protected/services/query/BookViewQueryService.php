<?php

declare(strict_types=1);

class BookViewQueryService
{
    public function __construct(private BookViewReadRepository $books) {}

    public function getById(int $bookId): array
    {
        return $this->books->findById($bookId);
    }
}
