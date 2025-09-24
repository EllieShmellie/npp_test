<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\BlockedDomain;

final class BlockedDomainRepository implements BlockedDomainRepositoryInterface
{
    public function hasBlockedAncestor(array $domains): bool
    {
        if ($domains === []) {
            return false;
        }

        return BlockedDomain::find()
            ->where(['domain' => $domains])
            ->limit(1)
            ->exists();
    }
}
