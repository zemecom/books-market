<?php

declare(strict_types=1);

class BookAuthorPolicy
{
    public function assertHasAuthors(array $authorIds): void
    {
        $authorIds = array_values(array_filter(array_map('intval', $authorIds)));
        if ($authorIds === []) {
            throw new ValidationException('Book must have at least one author.');
        }
    }
}
