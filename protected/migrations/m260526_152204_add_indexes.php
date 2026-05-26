<?php

class m260526_152204_add_indexes extends CDbMigration
{
    public function safeUp()
    {
        $this->createIndex('ix_book_author_author_id', 'book_author', 'author_id');
        $this->createIndex('ix_books_publish_year_title', 'books', 'publish_year, title');
        $this->createIndex('ix_authors_name', 'authors', 'name');
        $this->createIndex('ix_author_subscription_author_id_created_at', 'author_subscription', 'author_id, created_at');
        $this->createIndex('ix_sms_notification_log_created_at', 'sms_notification_log', 'created_at');
    }

    public function safeDown()
    {
        $this->dropIndex('ix_sms_notification_log_created_at', 'sms_notification_log');
        $this->dropIndex('ix_author_subscription_author_id_created_at', 'author_subscription');
        $this->dropIndex('ix_authors_name', 'authors');
        $this->dropIndex('ix_books_publish_year_title', 'books');
        $this->dropIndex('ix_book_author_author_id', 'book_author');
    }
}
