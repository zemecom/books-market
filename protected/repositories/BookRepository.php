<?php

declare(strict_types=1);

class BookRepository
{
    public function beginTransaction()
    {
        return Yii::app()->db->beginTransaction();
    }

    public function createBook(array $attributes): Book
    {
        $book = new Book();
        $book->attributes = $attributes;
        $this->saveOrThrow($book);

        return $book;
    }

    public function updateBook(Book $book, array $attributes): Book
    {
        $book->attributes = $attributes;
        $this->saveOrThrow($book);

        return $book;
    }

    public function syncAuthors(int $bookId, array $authorIds): void
    {
        BookAuthor::model()->deleteAllByAttributes(['book_id' => $bookId]);
        foreach ($authorIds as $authorId) {
            $link = new BookAuthor();
            $link->book_id = $bookId;
            $link->author_id = (int) $authorId;
            $this->saveOrThrow($link);
        }
    }

    public function findModelById(int $bookId): Book
    {
        $book = Book::model()->with('authors')->findByPk($bookId);
        if ($book === null) {
            throw new CHttpException(404, 'Book not found.');
        }

        return $book;
    }

    public function deleteBook(Book $book): void
    {
        BookAuthor::model()->deleteAllByAttributes(['book_id' => $book->id]);
        if (!$book->delete()) {
            throw new RuntimeException('Unable to delete book.');
        }
    }

    private function saveOrThrow(CActiveRecord $model): void
    {
        if (!$model->save()) {
            throw new ValidationException(CHtml::errorSummary($model));
        }
    }
}
