<?php

declare(strict_types=1);

class AuthorController extends Controller
{
    public function filters(): array
    {
        return ['accessControl', 'postOnly + delete, subscribe, deleteSubscription'];
    }

    public function accessRules(): array
    {
        return [
            ['allow', 'actions' => ['index', 'view', 'subscribe'], 'users' => ['*']],
            ['allow', 'actions' => ['create', 'update', 'delete', 'updateSubscription'], 'roles' => ['user']],
            ['allow', 'actions' => ['deleteSubscription'], 'roles' => ['admin']],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actionIndex(): void
    {
        $queryService = Yii::app()->services->authorViewQueryService();
        $totalItems = $queryService->countAuthors();

        $perPage = $this->resolvePerPage('author_catalog_per_page');

        $pages = new CPagination($totalItems);
        $pages->pageSize = $perPage;

        $authors = $queryService->getAuthors(
            limit: $pages->limit,
            offset: $pages->offset,
        );

        $this->render('index', [
            'authors' => $authors,
            'pages' => $pages,
            'perPage' => $perPage,
        ]);
    }

    public function actionView(int $id): void
    {
        $author = Yii::app()->services->authorViewQueryService()->getById($id);

        $subscribeForm = new SubscribeAuthorForm();
        if (Yii::app()->hasComponent('session') && isset(Yii::app()->session['last_subscription_phone'])) {
            $subscribeForm->phone = Yii::app()->session['last_subscription_phone'];
        }

        $this->render('view', [
            'author' => $author,
            'subscribeForm' => $subscribeForm,
        ]);
    }

    public function actionCreate(): void
    {
        $author = new Author();
        if (isset($_POST['Author'])) {
            $author->attributes = $_POST['Author'];
            if ($author->save()) {
                Yii::app()->user->setFlash('success', 'Author created successfully.');
                $this->redirect(['view', 'id' => $author->id]);
            }
        }

        $this->render('create', ['author' => $author]);
    }

    public function actionUpdate(int $id): void
    {
        $author = Author::model()->findByPk($id);
        if ($author === null) {
            throw new CHttpException(404, 'Author not found.');
        }

        if (isset($_POST['Author'])) {
            $author->attributes = $_POST['Author'];
            if ($author->save()) {
                Yii::app()->user->setFlash('success', 'Author updated successfully.');
                $this->redirect(['view', 'id' => $author->id]);
            }
        }

        $this->render('update', ['author' => $author]);
    }

    public function actionDelete(int $id): void
    {
        try {
            Yii::app()->services->deleteAuthorService()->delete($id);
            Yii::app()->user->setFlash('success', 'Author deleted successfully.');
            $this->redirect(['index']);
        } catch (ValidationException $e) {
            Yii::app()->user->setFlash('error', $e->getMessage());
            $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionSubscribe(int $id): void
    {
        $form = new SubscribeAuthorForm();
        if (isset($_POST['SubscribeAuthorForm'])) {
            $form->attributes = $_POST['SubscribeAuthorForm'];
            if (Yii::app()->hasComponent('session')) {
                Yii::app()->session['last_subscription_phone'] = $form->phone;
            }

            if ($form->validate()) {
                try {
                    $result = Yii::app()->services->subscribeToAuthorService()->subscribe($id, $form);
                    Yii::app()->user->setFlash($result->created ? 'success' : 'warning', $result->message);
                } catch (ValidationException $e) {
                    Yii::app()->user->setFlash('error', $e->getMessage());
                }
            } else {
                $errors = $form->getErrors();
                if (!empty($errors)) {
                    Yii::app()->user->setFlash('error', current(current($errors)));
                }
            }
        }

        $this->redirect(['view', 'id' => $id]);
    }

    public function actionUpdateSubscription(int $id): void
    {
        $subscription = AuthorSubscription::model()->findByPk($id);
        if ($subscription === null) {
            throw new CHttpException(404, 'Subscription not found.');
        }

        $form = new UpdateSubscriptionForm();
        $form->phone = $subscription->phone;

        if (isset($_POST['UpdateSubscriptionForm'])) {
            $form->attributes = $_POST['UpdateSubscriptionForm'];
            if ($form->validate()) {
                try {
                    Yii::app()->services->updateSubscriptionService()->update($id, $form);
                    Yii::app()->user->setFlash('success', 'Subscription updated successfully.');
                    $this->redirect(['view', 'id' => $subscription->author_id]);
                } catch (ValidationException $e) {
                    $form->addError('phone', $e->getMessage());
                }
            }
        }

        $this->render('updateSubscription', [
            'form' => $form,
            'subscription' => $subscription,
        ]);
    }

    public function actionDeleteSubscription(int $id): void
    {
        $subscription = AuthorSubscription::model()->findByPk($id);
        if ($subscription === null) {
            throw new CHttpException(404, 'Subscription not found.');
        }

        $authorId = $subscription->author_id;
        Yii::app()->services->deleteSubscriptionService()->delete($id);
        Yii::app()->user->setFlash('success', 'Subscription deleted successfully.');

        $this->redirect(['view', 'id' => $authorId]);
    }
}
