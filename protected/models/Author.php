<?php

declare(strict_types=1);

class Author extends TimestampedActiveRecord
{
    public static function model($className = __CLASS__): self
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'authors';
    }

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'length', 'max' => 255],
            ['bio', 'safe'],
        ];
    }

    public function relations(): array
    {
        return [
            'books' => [self::MANY_MANY, 'Book', 'book_author(author_id, book_id)'],
            'subscriptions' => [self::HAS_MANY, 'AuthorSubscription', 'author_id'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'bio' => 'Bio',
        ];
    }
}
