<?php

declare(strict_types=1);

class UpdateBookService
{
    public function __construct(
        private BookRepository $books,
        private AuthorRepository $authors,
        private CoverImageStorageInterface $coverStorage,
        private BookAuthorPolicy $policy,
    ) {}

    public function update(int $bookId, BookForm $form): Book
    {
        $authorIds = array_values(array_map('intval', $form->authorIds));
        $this->policy->assertHasAuthors($authorIds);
        $this->authors->assertAuthorsExist($authorIds);

        $book = $this->books->findModelById($bookId);
        $coverPath = $book->cover_path;
        $newCoverPath = null;
        if ($form->coverFile instanceof CUploadedFile) {
            $newCoverPath = $this->coverStorage->store($form->coverFile);
            $coverPath = $newCoverPath;
        }

        $transaction = $this->books->beginTransaction();
        try {
            $book = $this->books->updateBook($book, [
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
            if ($newCoverPath !== null) {
                $this->coverStorage->delete($newCoverPath);
            }
            throw $exception;
        }

        return $book;
    }
}
