<?php

declare(strict_types=1);

class LogController extends Controller
{
    public function filters(): array
    {
        return ['accessControl'];
    }

    public function accessRules(): array
    {
        return [
            ['allow', 'actions' => ['index'], 'roles' => ['admin']],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actionIndex(): void
    {
        $logs = Yii::app()->services->smsLogQueryService()->getLogs();
        $this->render('index', ['logs' => $logs]);
    }
}
