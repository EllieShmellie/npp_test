<?php

declare(strict_types=1);

use DateTimeImmutable;
use yii\db\Migration;

final class m240511_000002_seed_blocked_domains extends Migration
{
    private const TABLE_NAME = '{{%blocked_domain}}';

    public function safeUp(): bool
    {
        $now = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $rows = [
            ['facebook.com', 'com.facebook', $now],
            ['vk.com', 'com.vk', $now],
            ['ok.ru', 'ru.ok', $now],
            ['instagram.com', 'com.instagram', $now],
        ];

        $this->batchInsert(
            self::TABLE_NAME,
            ['domain', 'reversed_domain', 'created_at'],
            $rows
        );

        return true;
    }

    public function safeDown(): bool
    {
        $this->delete(self::TABLE_NAME, ['domain' => ['facebook.com', 'vk.com', 'ok.ru', 'instagram.com']]);

        return true;
    }
}
