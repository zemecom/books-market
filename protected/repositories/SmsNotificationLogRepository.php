<?php

declare(strict_types=1);

class SmsNotificationLogRepository
{
    public function log(array $payload): void
    {
        $model = new SmsNotificationLog();
        $model->book_id = $payload['book_id'];
        $model->phone = $payload['phone'];
        $model->message = $payload['message'];
        $model->status = $payload['status'];
        $model->error_text = $payload['error_text'];

        if (!$model->save()) {
            throw new RuntimeException('Unable to save SMS notification log.');
        }
    }
}
