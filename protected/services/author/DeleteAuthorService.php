<?php

declare(strict_types=1);

class DeleteAuthorService
{
    public function __construct(private AuthorRepository $authors) {}

    public function delete(int $authorId): void
    {
        $author = Author::model()->findByPk($authorId);
        if ($author === null) {
            return;
        }

        if ($this->authors->hasBooks($authorId)) {
            throw new ValidationException('Cannot delete author who has books.');
        }

        if (!$author->delete()) {
            throw new RuntimeException('Unable to delete author.');
        }
    }
}
