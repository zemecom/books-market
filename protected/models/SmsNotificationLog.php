<?php

declare(strict_types=1);

class SmsNotificationLog extends TimestampedActiveRecord
{
    public static function model($className = __CLASS__): self
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'sms_notification_log';
    }

    public function rules(): array
    {
        return [
            ['book_id, phone, message, status', 'required'],
            ['book_id', 'numerical', 'integerOnly' => true],
            ['phone', 'length', 'max' => 32],
            ['status', 'length', 'max' => 16],
            ['error_text', 'safe'],
        ];
    }
}
