<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $domain
 * @property string $reversed_domain
 * @property string $created_at
 */
final class BlockedDomain extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%blocked_domain}}';
    }

    public function rules(): array
    {
        return [
            [['domain', 'reversed_domain'], 'required'],
            [['domain', 'reversed_domain'], 'string', 'max' => 253],
            [['domain'], 'unique'],
            [['reversed_domain'], 'unique'],
            [
                'domain',
                'match',
                'pattern' => '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/i',
                'message' => 'Некорректное имя домена.',
            ],
        ];
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if ($this->domain !== null) {
            $normalized = strtolower(trim($this->domain, ". \t\n\r\0\x0B"));
            $this->domain = $normalized;
            $this->reversed_domain = implode('.', array_reverse(explode('.', $normalized)));
        }

        return true;
    }
}
