<?php

declare(strict_types=1);

class BookCatalogReadRepository
{
    public function fetchCatalog(array $filters = [], ?int $limit = null, ?int $offset = null): array
    {
        $command = Yii::app()->db->createCommand()
            ->select('b.id, b.title, b.description, b.isbn, b.publish_year, b.published_at, b.cover_path, GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR ", ") AS authors')
            ->from('books b')
            ->leftJoin('book_author ba', 'ba.book_id = b.id')
            ->leftJoin('authors a', 'a.id = ba.author_id')
            ->group('b.id')
            ->order('b.publish_year DESC, b.title ASC');

        if (!empty($filters['year'])) {
            $command->where('b.publish_year = :year', [':year' => (int) $filters['year']]);
        }

        if ($limit !== null) {
            $command->limit($limit);
        }
        if ($offset !== null) {
            $command->offset($offset);
        }

        $rows = $command->queryAll();

        return array_map(static function (array $row): array {
            $row['cover_url'] = $row['cover_path'] ? '/' . ltrim($row['cover_path'], '/') : null;
            return $row;
        }, $rows);
    }

    public function countCatalog(array $filters = []): int
    {
        $command = Yii::app()->db->createCommand()
            ->select('COUNT(DISTINCT b.id)')
            ->from('books b');

        if (!empty($filters['year'])) {
            $command->where('b.publish_year = :year', [':year' => (int) $filters['year']]);
        }

        return (int) $command->queryScalar();
    }
}
