<?php

declare(strict_types=1);

class BookViewReadRepository
{
    public function findById(int $bookId): array
    {
        $book = Yii::app()->db->createCommand()
            ->select('id, title, description, isbn, publish_year, published_at, cover_path')
            ->from('books')
            ->where('id = :id', [':id' => $bookId])
            ->queryRow();

        if ($book === false) {
            throw new CHttpException(404, 'Book not found.');
        }

        $authors = Yii::app()->db->createCommand()
            ->select('a.id, a.name')
            ->from('authors a')
            ->join('book_author ba', 'ba.author_id = a.id')
            ->where('ba.book_id = :bookId', [':bookId' => $bookId])
            ->order('a.name ASC')
            ->queryAll();

        return [
            'id' => (int) $book['id'],
            'title' => $book['title'],
            'description' => $book['description'],
            'isbn' => $book['isbn'],
            'publish_year' => (int) $book['publish_year'],
            'published_at' => $book['published_at'],
            'cover_url' => $book['cover_path'] ? '/' . ltrim($book['cover_path'], '/') : null,
            'authors' => $authors,
        ];
    }
}
