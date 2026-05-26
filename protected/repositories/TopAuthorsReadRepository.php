<?php

declare(strict_types=1);

class TopAuthorsReadRepository
{
    public function fetchTopAuthors(?int $year = null, int $limit = 10): array
    {
        $selectYear = $year !== null ? 'b.publish_year' : 'NULL AS publish_year';
        $sql = "SELECT a.id AS author_id, a.name AS author_name, {$selectYear}, COUNT(DISTINCT b.id) AS books_count
             FROM authors a
             INNER JOIN book_author ba ON ba.author_id = a.id
             INNER JOIN books b ON b.id = ba.book_id";

        if ($year !== null) {
            $sql .= ' WHERE b.publish_year = :year';
        }

        $sql .= ' GROUP BY a.id, a.name';
        if ($year !== null) {
            $sql .= ', b.publish_year';
        }

        $sql .= ' ORDER BY books_count DESC, author_name ASC
             LIMIT ' . (int) $limit;

        $command = Yii::app()->db->createCommand($sql);
        if ($year !== null) {
            $command->bindValue(':year', $year);
        }

        return $command->queryAll();
    }
}
