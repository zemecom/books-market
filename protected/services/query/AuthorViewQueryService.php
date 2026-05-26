<?php

declare(strict_types=1);

class AuthorViewQueryService
{
    public function __construct(private AuthorViewReadRepository $authors) {}

    public function getById(int $authorId): array
    {
        return $this->authors->findById($authorId);
    }

    public function getAuthors(?int $limit = null, ?int $offset = null): array
    {
        return $this->authors->fetchAuthors($limit, $offset);
    }

    public function countAuthors(): int
    {
        return $this->authors->countAuthors();
    }
}
