<?php

class m260526_000002_align_with_spec extends CDbMigration
{
    public function safeUp(): void
    {
        $this->addColumn('books', 'isbn', 'string NULL');
        $this->addColumn('books', 'publish_year', 'integer NULL');

        $this->update('books', ['publish_year' => new CDbExpression('YEAR(published_at)')]);

        foreach ($this->dbConnection->createCommand('SELECT id FROM books')->queryColumn() as $bookId) {
            $this->update('books', ['isbn' => sprintf('ISBN-%d', (int) $bookId)], 'id = :id', [':id' => (int) $bookId]);
        }

        $this->alterColumn('books', 'isbn', 'string NOT NULL');
        $this->alterColumn('books', 'publish_year', 'integer NOT NULL');

        $userLogin = Yii::app()->params['userLogin'];
        $userPassword = Yii::app()->params['userPassword'];
        $now = date('Y-m-d H:i:s');

        if (!$this->dbConnection->createCommand('SELECT COUNT(*) FROM authitem WHERE name = :name')
            ->queryScalar([':name' => 'user'])) {
            $this->insert('authitem', ['name' => 'user', 'type' => 2, 'description' => 'Catalog user']);
        }

        if (!$this->dbConnection->createCommand('SELECT COUNT(*) FROM authitemchild WHERE parent = :parent AND child = :child')
            ->queryScalar([':parent' => 'admin', ':child' => 'user'])) {
            $this->insert('authitemchild', ['parent' => 'admin', 'child' => 'user']);
        }

        $userId = $this->dbConnection->createCommand('SELECT id FROM users WHERE login = :login')
            ->queryScalar([':login' => $userLogin]);

        if ($userId === false) {
            $this->insert('users', [
                'login' => $userLogin,
                'password_hash' => password_hash($userPassword, PASSWORD_DEFAULT),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $userId = (int) $this->dbConnection->getLastInsertID();
        } else {
            $userId = (int) $userId;
        }

        if (!$this->dbConnection->createCommand('SELECT COUNT(*) FROM authassignment WHERE itemname = :itemname AND userid = :userid')
            ->queryScalar([':itemname' => 'user', ':userid' => (string) $userId])) {
            $this->insert('authassignment', [
                'itemname' => 'user',
                'userid' => (string) $userId,
            ]);
        }
    }

    public function safeDown(): void
    {
        $userLogin = Yii::app()->params['userLogin'];
        $userId = $this->dbConnection->createCommand('SELECT id FROM users WHERE login = :login')
            ->queryScalar([':login' => $userLogin]);

        if ($userId !== false) {
            $this->delete('authassignment', 'itemname = :itemname AND userid = :userid', [
                ':itemname' => 'user',
                ':userid' => (string) $userId,
            ]);
            $this->delete('users', 'id = :id', [':id' => (int) $userId]);
        }

        $this->delete('authitemchild', 'parent = :parent AND child = :child', [
            ':parent' => 'admin',
            ':child' => 'user',
        ]);
        $this->delete('authitem', 'name = :name', [':name' => 'user']);

        $this->dropColumn('books', 'publish_year');
        $this->dropColumn('books', 'isbn');
    }
}
