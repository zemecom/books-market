<?php

declare(strict_types=1);

class BookAuthor extends CActiveRecord
{
    public static function model($className = __CLASS__): self
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'book_author';
    }

    public function rules(): array
    {
        return [
            ['book_id, author_id', 'required'],
            ['book_id, author_id', 'numerical', 'integerOnly' => true],
        ];
    }
}
