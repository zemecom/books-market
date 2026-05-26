<?php

class m260526_000001_init_schema extends CDbMigration
{
    public function safeUp(): void
    {
        $options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

        $this->createTable('users', [
            'id' => 'pk',
            'login' => 'string NOT NULL',
            'password_hash' => 'string NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime NOT NULL',
        ], $options);
        $this->createIndex('ux_users_login', 'users', 'login', true);

        $this->createTable('authors', [
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'bio' => 'text NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime NOT NULL',
        ], $options);

        $this->createTable('books', [
            'id' => 'pk',
            'title' => 'string NOT NULL',
            'description' => 'text NULL',
            'published_at' => 'date NOT NULL',
            'cover_path' => 'string NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime NOT NULL',
        ], $options);

        $this->createTable('book_author', [
            'book_id' => 'integer NOT NULL',
            'author_id' => 'integer NOT NULL',
        ], $options);
        $this->addPrimaryKey('pk_book_author', 'book_author', 'book_id, author_id');
        $this->addForeignKey('fk_book_author_book', 'book_author', 'book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_book_author_author', 'book_author', 'author_id', 'authors', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('author_subscription', [
            'id' => 'pk',
            'author_id' => 'integer NOT NULL',
            'phone' => 'string NOT NULL',
            'phone_normalized' => 'string NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime NOT NULL',
        ], $options);
        $this->addForeignKey('fk_author_subscription_author', 'author_subscription', 'author_id', 'authors', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('ux_author_subscription_unique', 'author_subscription', 'author_id, phone_normalized', true);

        $this->createTable('sms_notification_log', [
            'id' => 'pk',
            'book_id' => 'integer NOT NULL',
            'phone' => 'string NOT NULL',
            'message' => 'text NOT NULL',
            'status' => 'string NOT NULL',
            'error_text' => 'text NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime NOT NULL',
        ], $options);
        $this->addForeignKey('fk_sms_notification_log_book', 'sms_notification_log', 'book_id', 'books', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('authitem', [
            'name' => 'string NOT NULL',
            'type' => 'integer NOT NULL',
            'description' => 'text',
            'bizrule' => 'text',
            'data' => 'text',
        ], $options);
        $this->addPrimaryKey('pk_authitem', 'authitem', 'name');

        $this->createTable('authitemchild', [
            'parent' => 'string NOT NULL',
            'child' => 'string NOT NULL',
        ], $options);
        $this->addPrimaryKey('pk_authitemchild', 'authitemchild', 'parent, child');
        $this->addForeignKey('fk_authitemchild_parent', 'authitemchild', 'parent', 'authitem', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_authitemchild_child', 'authitemchild', 'child', 'authitem', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('authassignment', [
            'itemname' => 'string NOT NULL',
            'userid' => 'string NOT NULL',
            'bizrule' => 'text',
            'data' => 'text',
        ], $options);
        $this->addPrimaryKey('pk_authassignment', 'authassignment', 'itemname, userid');
        $this->addForeignKey('fk_authassignment_itemname', 'authassignment', 'itemname', 'authitem', 'name', 'CASCADE', 'CASCADE');

        $now = date('Y-m-d H:i:s');
        $this->insert('users', [
            'id' => 1,
            'login' => Yii::app()->params['adminLogin'],
            'password_hash' => password_hash(Yii::app()->params['adminPassword'], PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->insert('authitem', ['name' => 'admin', 'type' => 2, 'description' => 'Administrator']);
        $this->insert('authassignment', ['itemname' => 'admin', 'userid' => '1']);
    }

    public function safeDown(): void
    {
        $this->dropTable('authassignment');
        $this->dropTable('authitemchild');
        $this->dropTable('authitem');
        $this->dropTable('sms_notification_log');
        $this->dropTable('author_subscription');
        $this->dropTable('book_author');
        $this->dropTable('books');
        $this->dropTable('authors');
        $this->dropTable('users');
    }
}
