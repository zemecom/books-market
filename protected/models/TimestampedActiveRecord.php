<?php

declare(strict_types=1);

/**
 * @property string|null $created_at
 * @property string|null $updated_at
 */
abstract class TimestampedActiveRecord extends CActiveRecord
{
    protected function beforeSave(): bool
    {
        $now = Yii::app()->clock->now()->format('Y-m-d H:i:s');

        if ($this->hasAttribute('updated_at')) {
            $this->updated_at = $now;
        }

        if ($this->getIsNewRecord() && $this->hasAttribute('created_at')) {
            $this->created_at = $now;
        }

        return parent::beforeSave();
    }
}
