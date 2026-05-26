<?php

declare(strict_types=1);

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $isbn
 * @property int $publish_year
 * @property string $published_at
 * @property string|null $cover_path
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Author[] $authors
 */
class Book extends TimestampedActiveRecord
{
    public static function model($className = __CLASS__): self
    {
        return parent::model($className);
    }

    public function tableName(): string
    {
        return 'books';
    }

    public function rules(): array
    {
        return [
            ['title, isbn, publish_year, published_at', 'required'],
            ['publish_year', 'numerical', 'integerOnly' => true],
            ['isbn', 'length', 'max' => 64],
            ['description, cover_path', 'safe'],
        ];
    }

    public function relations(): array
    {
        return [
            'bookAuthors' => [self::HAS_MANY, 'BookAuthor', 'book_id'],
            'authors' => [self::MANY_MANY, 'Author', 'book_author(book_id, author_id)'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'isbn' => 'ISBN',
            'publish_year' => 'Publish Year',
            'published_at' => 'Published At',
            'cover_path' => 'Cover',
        ];
    }

    public function getAuthorNames(): string
    {
        return implode(', ', array_map(static fn(Author $author): string => $author->name, $this->authors));
    }

    public function getCoverUrl(): ?string
    {
        if ($this->cover_path === null || $this->cover_path === '') {
            return null;
        }

        return '/' . ltrim($this->cover_path, '/');
    }
}
