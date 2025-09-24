<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blocked_domain}}`.
 */
final class m240511_000001_create_blocked_domain_table extends Migration
{
    private const TABLE_NAME = '{{%blocked_domain}}';

    public function safeUp(): bool
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'domain' => $this->string(253)->notNull()->unique(),
            'reversed_domain' => $this->string(253)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx-blocked_domain-reversed_domain',
            self::TABLE_NAME,
            'reversed_domain'
        );

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE_NAME);

        return true;
    }
}
