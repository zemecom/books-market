<?php

declare(strict_types=1);

class DeleteBookService
{
    public function __construct(private BookRepository $books) {}

    public function delete(int $bookId): void
    {
        $transaction = $this->books->beginTransaction();
        try {
            $book = $this->books->findModelById($bookId);
            $this->books->deleteBook($book);
            $transaction->commit();
        } catch (Throwable $exception) {
            $transaction->rollback();
            throw $exception;
        }
    }
}
