<?php

declare(strict_types=1);

class BookController extends Controller
{
    public function filters(): array
    {
        return ['accessControl', 'postOnly + delete'];
    }

    public function accessRules(): array
    {
        return [
            ['allow', 'actions' => ['index', 'view'], 'users' => ['*']],
            ['allow', 'actions' => ['create', 'update', 'delete'], 'roles' => ['user']],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actionIndex(): void
    {
        $queryService = Yii::app()->services->bookCatalogQueryService();
        $totalItems = $queryService->countCatalog($_GET);

        $perPage = $this->resolvePerPage('book_catalog_per_page');

        $pages = new CPagination($totalItems);
        $pages->pageSize = $perPage;

        $rows = $queryService->getCatalog(
            filters: $_GET,
            limit: $pages->limit,
            offset: $pages->offset,
        );

        $this->render('index', [
            'rows' => $rows,
            'pages' => $pages,
            'perPage' => $perPage,
        ]);
    }

    public function actionView(int $id): void
    {
        $book = Yii::app()->services->bookViewQueryService()->getById($id);
        $this->render('view', ['book' => $book]);
    }

    public function actionCreate(): void
    {
        $form = new BookForm();
        if (isset($_POST['BookForm'])) {
            $postData = $_POST['BookForm'];
            unset($postData['coverFile']);
            $form->attributes = $postData;
            $form->authorIds = $_POST['BookForm']['authorIds'] ?? [];
            $form->coverFile = CUploadedFile::getInstance($form, 'coverFile');
            if ($form->validate()) {
                $book = Yii::app()->services->createBookService()->create($form);
                Yii::app()->user->setFlash('success', 'Book created successfully.');
                $this->redirect(['view', 'id' => $book->id]);
            }
        }

        $this->render('create', [
            'form' => $form,
            'authorOptions' => Yii::app()->services->authorRepository()->dropdownOptions(),
        ]);
    }

    public function actionUpdate(int $id): void
    {
        $book = Yii::app()->services->bookRepository()->findModelById($id);
        $form = new BookForm();
        $form->title = $book->title;
        $form->description = $book->description;
        $form->isbn = $book->isbn;
        $form->publishYear = (string) $book->publish_year;
        $form->authorIds = array_map(static fn(Author $author): int => (int) $author->id, $book->authors);

        if (isset($_POST['BookForm'])) {
            $postData = $_POST['BookForm'];
            unset($postData['coverFile']);
            $form->attributes = $postData;
            $form->authorIds = $_POST['BookForm']['authorIds'] ?? [];
            $form->coverFile = CUploadedFile::getInstance($form, 'coverFile');
            if ($form->validate()) {
                $book = Yii::app()->services->updateBookService()->update($id, $form);
                Yii::app()->user->setFlash('success', 'Book updated successfully.');
                $this->redirect(['view', 'id' => $book->id]);
            }
        }

        $this->render('update', [
            'form' => $form,
            'book' => $book,
            'authorOptions' => Yii::app()->services->authorRepository()->dropdownOptions(),
        ]);
    }

    public function actionDelete(int $id): void
    {
        Yii::app()->services->deleteBookService()->delete($id);
        Yii::app()->user->setFlash('success', 'Book deleted successfully.');
        $this->redirect(['index']);
    }
}
