<?php

class m260526_155220_add_isbn_and_title_indexes extends CDbMigration
{
    public function safeUp()
    {
        $this->createIndex('ux_books_isbn', 'books', 'isbn', true);
        $this->createIndex('ix_books_title', 'books', 'title');
    }

    public function safeDown()
    {
        $this->dropIndex('ix_books_title', 'books');
        $this->dropIndex('ux_books_isbn', 'books');
    }
}
