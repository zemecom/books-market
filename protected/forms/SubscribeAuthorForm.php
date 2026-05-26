<?php

declare(strict_types=1);

class SubscribeAuthorForm extends CFormModel
{
    public string $phone = '';

    public function rules(): array
    {
        return [
            ['phone', 'required'],
            ['phone', 'length', 'max' => 32],
            ['phone', 'match', 'pattern' => '/^[\+\d\s\-\(\)]+$/', 'message' => 'Phone can only contain digits, spaces, and +-() characters.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => 'Phone',
        ];
    }
}
