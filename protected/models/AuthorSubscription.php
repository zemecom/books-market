<?php

declare(strict_types=1);

class AuthorSubscription extends TimestampedActiveRecord
{
    public static function model($className = __CLASS__): self
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'author_subscription';
    }

    public function rules(): array
    {
        return [
            ['author_id, phone, phone_normalized', 'required'],
            ['author_id', 'numerical', 'integerOnly' => true],
            ['phone, phone_normalized', 'length', 'max' => 32],
        ];
    }

    public function relations(): array
    {
        return [
            'author' => [self::BELONGS_TO, 'Author', 'author_id'],
        ];
    }
}
