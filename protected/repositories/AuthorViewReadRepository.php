<?php

declare(strict_types=1);

class AuthorViewReadRepository
{
    public function findById(int $authorId): array
    {
        $author = Yii::app()->db->createCommand()
            ->select('id, name, bio')
            ->from('authors')
            ->where('id = :id', [':id' => $authorId])
            ->queryRow();

        if ($author === false) {
            throw new CHttpException(404, 'Author not found.');
        }

        $books = Yii::app()->db->createCommand()
            ->select('b.id, b.title, b.publish_year, b.published_at')
            ->from('books b')
            ->join('book_author ba', 'ba.book_id = b.id')
            ->where('ba.author_id = :authorId', [':authorId' => $authorId])
            ->order('b.publish_year DESC, b.title ASC')
            ->queryAll();

        $subscriptions = Yii::app()->db->createCommand()
            ->select('id, phone')
            ->from('author_subscription')
            ->where('author_id = :authorId', [':authorId' => $authorId])
            ->order('created_at DESC')
            ->queryAll();

        return [
            'id' => (int) $author['id'],
            'name' => $author['name'],
            'bio' => $author['bio'],
            'books' => $books,
            'subscriptions' => $subscriptions,
        ];
    }

    public function fetchAuthors(?int $limit = null, ?int $offset = null): array
    {
        $command = Yii::app()->db->createCommand()
            ->select('id, name, bio')
            ->from('authors')
            ->order('name ASC');

        if ($limit !== null) {
            $command->limit($limit);
        }
        if ($offset !== null) {
            $command->offset($offset);
        }

        return $command->queryAll();
    }

    public function countAuthors(): int
    {
        $command = Yii::app()->db->createCommand()
            ->select('COUNT(*)')
            ->from('authors');

        return (int) $command->queryScalar();
    }
}
