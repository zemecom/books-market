<?php

declare(strict_types=1);

class AuthorRepository
{
    public function assertAuthorsExist(array $authorIds): void
    {
        foreach ($authorIds as $authorId) {
            if (!$this->exists((int) $authorId)) {
                throw new ValidationException('Author not found.');
            }
        }
    }

    public function exists(int $authorId): bool
    {
        return Author::model()->exists('id = :id', [':id' => $authorId]);
    }

    public function hasBooks(int $authorId): bool
    {
        return BookAuthor::model()->exists('author_id = :id', [':id' => $authorId]);
    }

    public function dropdownOptions(): array
    {
        $authors = Author::model()->findAll(['order' => 'name ASC']);
        $result = [];
        foreach ($authors as $author) {
            $result[$author->id] = $author->name;
        }

        return $result;
    }
}
