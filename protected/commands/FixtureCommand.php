<?php

declare(strict_types=1);

class FixtureCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $db = Yii::app()->db;

        echo "Cleaning up database...\n";
        $db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        $db->createCommand("TRUNCATE TABLE author_subscription")->execute();
        $db->createCommand("TRUNCATE TABLE sms_notification_log")->execute();
        $db->createCommand("TRUNCATE TABLE book_author")->execute();
        $db->createCommand("TRUNCATE TABLE books")->execute();
        $db->createCommand("TRUNCATE TABLE authors")->execute();
        $db->createCommand("TRUNCATE TABLE users")->execute();
        $db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();

        echo "Inserting Users...\n";
        $users = [
            ['id' => 1, 'login' => 'admin', 'password_hash' => password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12])],
            ['id' => 2, 'login' => 'user', 'password_hash' => password_hash('user123', PASSWORD_BCRYPT, ['cost' => 12])],
        ];
        $now = Yii::app()->clock->now()->format('Y-m-d H:i:s');
        foreach ($users as $user) {
            $user['created_at'] = $now;
            $user['updated_at'] = $now;
            $db->createCommand()->insert('users', $user);
        }

        echo "Inserting Authors...\n";
        $authorsData = [
            'Eric Evans' => 'Eric Evans is a thought leader in software design and domain-driven design and the author of "Domain-Driven Design".',
            'Martin Fowler' => 'Martin Fowler is a software developer, author and international public speaker on software architecture.',
            'Robert C. Martin' => 'Robert Cecil Martin, also known as Uncle Bob, is an American software engineer, instructor, and best-selling author.',
            'Kent Beck' => 'Kent Beck is an American software engineer and the creator of extreme programming.',
            'Erich Gamma' => 'Erich Gamma is a Swiss computer scientist and co-author of the influential software engineering textbook, Design Patterns.',
            'Ralph Johnson' => 'Ralph Johnson is a co-author of the Design Patterns book.',
            'Richard Helm' => 'Richard Helm is a co-author of the Design Patterns book.',
            'John Vlissides' => 'John Vlissides was a software scientist and a co-author of the Design Patterns book.',
            'Sandi Metz' => 'Sandi Metz is a software engineer and author of Practical Object-Oriented Design in Ruby.',
            'Michael Feathers' => 'Michael Feathers is the founder and Director of R7K Research & Conveyance and author of Working Effectively with Legacy Code.',
        ];

        $authorIds = [];
        $i = 1;
        $now = Yii::app()->clock->now()->format('Y-m-d H:i:s');
        foreach ($authorsData as $name => $bio) {
            $db->createCommand()->insert('authors', [
                'id' => $i,
                'name' => $name,
                'bio' => $bio,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $authorIds[$name] = $i;
            $i++;
        }

        echo "Inserting Books...\n";
        $booksData = [
            ['title' => 'Domain-Driven Design: Tackling Complexity in the Heart of Software', 'publish_year' => 2003, 'authors' => ['Eric Evans']],
            ['title' => 'Patterns of Enterprise Application Architecture', 'publish_year' => 2002, 'authors' => ['Martin Fowler']],
            ['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'publish_year' => 2008, 'authors' => ['Robert C. Martin']],
            ['title' => 'Test Driven Development: By Example', 'publish_year' => 2002, 'authors' => ['Kent Beck']],
            ['title' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'publish_year' => 1994, 'authors' => ['Erich Gamma', 'Richard Helm', 'Ralph Johnson', 'John Vlissides']],
            ['title' => 'Refactoring: Improving the Design of Existing Code', 'publish_year' => 1999, 'authors' => ['Martin Fowler', 'Kent Beck']],
            ['title' => 'Clean Architecture: A Craftsman\'s Guide to Software Structure and Design', 'publish_year' => 2017, 'authors' => ['Robert C. Martin']],
            ['title' => 'Practical Object-Oriented Design in Ruby', 'publish_year' => 2012, 'authors' => ['Sandi Metz']],
            ['title' => '99 Bottles of OOP', 'publish_year' => 2016, 'authors' => ['Sandi Metz']],
            ['title' => 'Working Effectively with Legacy Code', 'publish_year' => 2004, 'authors' => ['Michael Feathers']],
            ['title' => 'Extreme Programming Explained: Embrace Change', 'publish_year' => 1999, 'authors' => ['Kent Beck']],
            ['title' => 'Agile Software Development, Principles, Patterns, and Practices', 'publish_year' => 2002, 'authors' => ['Robert C. Martin']],
        ];

        $bookId = 1;
        foreach ($booksData as $book) {
            $isbn = '978-' . rand(0, 9) . '-' . rand(100, 999) . '-' . rand(10000, 99999) . '-' . rand(0, 9);
            $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $day = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);

            $now = Yii::app()->clock->now()->format('Y-m-d H:i:s');
            $db->createCommand()->insert('books', [
                'id' => $bookId,
                'title' => $book['title'],
                'description' => 'A great book about software engineering.',
                'isbn' => $isbn,
                'publish_year' => $book['publish_year'],
                'published_at' => $book['publish_year'] . '-' . $month . '-' . $day,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($book['authors'] as $authorName) {
                if (isset($authorIds[$authorName])) {
                    $db->createCommand()->insert('book_author', [
                        'book_id' => $bookId,
                        'author_id' => $authorIds[$authorName],
                    ]);
                }
            }
            $bookId++;
        }

        echo "Fixtures loaded successfully!\n";
    }
}
