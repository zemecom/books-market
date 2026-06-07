<?php

use PHPUnit\Framework\TestCase;

final class CreateBookServiceTest extends TestCase
{
    public function testCreatesBookWithAtLeastOneAuthorAndCallsNotifier(): void
    {
        $books = new InMemoryBookRepository();
        $authors = new InMemoryAuthorRepository([1 => 'Evans']);
        $notifier = new RecordingNotifier();
        $storage = new NullCoverImageStorage();
        $service = new CreateBookService($books, $authors, $storage, $notifier, new BookAuthorPolicy());
        $form = new BookForm();
        $form->title = 'Domain-Driven Design';
        $form->description = 'Blue book';
        $form->isbn = '978-0-321-12521-7';
        $form->publishYear = '2026';
        $form->authorIds = [1];

        $book = $service->create($form);

        self::assertSame(1, (int) $book->id);
        self::assertSame('978-0-321-12521-7', $book->isbn);
        self::assertSame(2026, (int) $book->publish_year);
        self::assertSame('2026-01-01', $book->published_at);
        self::assertSame([1], $books->authorLinks[1]);
        self::assertSame(1, $notifier->calls);
    }

    public function testRejectsBookWithoutAuthors(): void
    {
        $service = new CreateBookService(
            new InMemoryBookRepository(),
            new InMemoryAuthorRepository(),
            new NullCoverImageStorage(),
            new RecordingNotifier(),
            new BookAuthorPolicy(),
        );
        $form = new BookForm();
        $form->title = 'No authors';
        $form->isbn = '978-0-321-12521-7';
        $form->publishYear = '2026';
        $form->authorIds = [];

        $this->expectException(ValidationException::class);
        $service->create($form);
    }

    public function testNotifierFailureDoesNotRollbackBookCreation(): void
    {
        $books = new InMemoryBookRepository();
        $service = new CreateBookService(
            $books,
            new InMemoryAuthorRepository([1 => 'Evans']),
            new NullCoverImageStorage(),
            new RecordingNotifier(true),
            new BookAuthorPolicy(),
        );
        $form = new BookForm();
        $form->title = 'Resilient';
        $form->isbn = '978-0-321-12521-7';
        $form->publishYear = '2026';
        $form->authorIds = [1];

        $book = $service->create($form);

        self::assertSame(1, (int) $book->id);
        self::assertArrayHasKey(1, $books->books);
    }
}

final class InMemoryBookRepository extends BookRepository
{
    public array $books = [];
    public array $authorLinks = [];
    private int $nextId = 1;

    public function beginTransaction()
    {
        return new NullTransaction();
    }

    public function createBook(array $attributes): Book
    {
        $book = new Book();
        $book->id = $this->nextId++;
        $book->title = $attributes['title'];
        $book->description = $attributes['description'];
        $book->isbn = $attributes['isbn'];
        $book->publish_year = $attributes['publish_year'];
        $book->published_at = $attributes['published_at'];
        $book->cover_path = $attributes['cover_path'];
        $this->books[$book->id] = $book;

        return $book;
    }

    public function updateBook(Book $book, array $attributes): Book
    {
        $book->title = $attributes['title'];
        $book->description = $attributes['description'];
        $book->isbn = $attributes['isbn'];
        $book->publish_year = $attributes['publish_year'];
        $book->published_at = $attributes['published_at'];
        $book->cover_path = $attributes['cover_path'];
        $this->books[$book->id] = $book;

        return $book;
    }

    public function syncAuthors(int $bookId, array $authorIds): void
    {
        $this->authorLinks[$bookId] = $authorIds;
    }

    public function findModelById(int $bookId): Book
    {
        return $this->books[$bookId];
    }

    public function deleteBook(Book $book): void
    {
        unset($this->books[$book->id], $this->authorLinks[$book->id]);
    }
}

final class InMemoryAuthorRepository extends AuthorRepository
{
    public function __construct(private array $authors = []) {}

    public function assertAuthorsExist(array $authorIds): void
    {
        foreach ($authorIds as $authorId) {
            if (!array_key_exists($authorId, $this->authors)) {
                throw new ValidationException('Author not found.');
            }
        }
    }

    public function exists(int $authorId): bool
    {
        return array_key_exists($authorId, $this->authors);
    }
}

final class RecordingNotifier extends NotifyBookSubscribersService
{
    public int $calls = 0;

    public function __construct(private bool $shouldThrow = false) {}

    public function notify(int $bookId, array $authorIds, string $bookTitle): NotificationResult
    {
        $this->calls++;
        if ($this->shouldThrow) {
            throw new RuntimeException('SMS failed');
        }

        return new NotificationResult(0, 0, []);
    }
}

final class NullCoverImageStorage implements CoverImageStorageInterface
{
    public function store(CUploadedFile $file): string
    {
        return '';
    }

    public function delete(string $path): void {}
}

final class NullTransaction
{
    public function commit(): void {}

    public function rollback(): void {}
}
