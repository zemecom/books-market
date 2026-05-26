<?php

declare(strict_types=1);

class SmsLogReadRepository
{
    public function fetchLogs(): array
    {
        return Yii::app()->db->createCommand()
            ->select('l.id, l.phone, l.message, l.status, l.error_text, l.created_at, b.title as book_title')
            ->from('sms_notification_log l')
            ->leftJoin('books b', 'b.id = l.book_id')
            ->order('l.created_at DESC')
            ->queryAll();
    }
}
