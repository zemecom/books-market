<?php

declare(strict_types=1);

class UpdateSubscriptionForm extends CFormModel
{
    public string $phone = '';

    public function rules(): array
    {
        return [
            ['phone', 'required'],
            ['phone', 'match', 'pattern' => '/^(\+7|8)[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/', 'message' => 'Phone number must be a valid Russian phone number.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => 'Phone Number',
        ];
    }
}
