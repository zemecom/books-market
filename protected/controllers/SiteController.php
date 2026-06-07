<?php

declare(strict_types=1);

class SiteController extends Controller
{
    public function filters(): array
    {
        return ['accessControl', 'postOnly + logout'];
    }

    public function accessRules(): array
    {
        return [
            ['allow', 'actions' => ['index', 'login', 'error'], 'users' => ['*']],
            ['allow', 'actions' => ['logout'], 'users' => ['@']],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actions(): array
    {
        return [];
    }

    public function actionIndex(): void
    {
        $this->redirect(['book/index']);
    }

    public function actionLogin(): void
    {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(['book/index']);
        }

        $model = new LoginForm();
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                $this->redirect(['book/index']);
            }
        }

        $this->render('login', ['model' => $model]);
    }

    public function actionLogout(): void
    {
        Yii::app()->user->logout(false);
        $this->redirect(['site/login']);
    }

    public function actionError(): void
    {
        $error = Yii::app()->errorHandler->error;
        if ($error === null) {
            throw new CHttpException(404, 'Page not found.');
        }

        $this->render('error', ['error' => $error]);
    }
}
