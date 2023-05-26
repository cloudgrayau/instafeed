<?php
namespace cloudgrayau\instafeed\migrations;

use craft\db\Migration;

class Install extends Migration {

    public function safeUp(): void {
        $this->createTable('instafeed_tokens', [
          'id' => $this->primaryKey(),
          'siteId' => $this->integer()->notNull(),
          'access_token' => $this->text(),
          'token_expiration' => $this->dateTime(),
          'dateCreated' => $this->dateTime()->notNull(),
          'dateUpdated' => $this->dateTime()->notNull(),
          'uid' => $this->uid()
        ]);
        $this->createIndex(null, 'instafeed_tokens', 'siteId', false);
    }

    public function safeDown(): void {
        $this->dropTable('instafeed_tokens');
    }
    
}
