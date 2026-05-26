<?php

declare(strict_types=1);

class User extends TimestampedActiveRecord
{
    public static function model($className = __CLASS__): self
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            ['login, password_hash', 'required'],
            ['login', 'length', 'max' => 64],
            ['password_hash', 'length', 'max' => 255],
        ];
    }
}
