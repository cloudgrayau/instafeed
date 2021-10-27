<?php

namespace cloudgrayau\instafeed\migrations;
use craft\db\Migration;

/**
 * Install migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
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

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('instafeed_tokens');
    }
}
