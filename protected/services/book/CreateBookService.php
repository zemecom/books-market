<?php

declare(strict_types=1);

class CreateBookService
{
    public function __construct(
        private BookRepository $books,
        private AuthorRepository $authors,
        private CoverImageStorageInterface $coverStorage,
        private NotifyBookSubscribersService $notifier,
        private BookAuthorPolicy $policy,
    ) {}

    public function create(BookForm $form): Book
    {
        $authorIds = array_values(array_map('intval', $form->authorIds));
        $this->policy->assertHasAuthors($authorIds);
        $this->authors->assertAuthorsExist($authorIds);

        $transaction = $this->books->beginTransaction();
        try {
            $coverPath = $form->coverFile instanceof CUploadedFile ? $this->coverStorage->store($form->coverFile) : null;
            $book = $this->books->createBook([
                'title' => $form->title,
                'description' => $form->description,
                'isbn' => $form->isbn,
                'publish_year' => $form->getPublishYearValue(),
                'published_at' => $form->toPublishedAt(),
                'cover_path' => $coverPath,
            ]);
            $this->books->syncAuthors((int) $book->id, $authorIds);
            $transaction->commit();
        } catch (Throwable $exception) {
            $transaction->rollback();
            throw $exception;
        }

        try {
            $this->notifier->notify((int) $book->id, $authorIds, $book->title);
        } catch (Throwable $exception) {
            Yii::log($exception->getMessage(), CLogger::LEVEL_WARNING, 'books-market.notification');
        }

        return $book;
    }
}
