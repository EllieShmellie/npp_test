<?php

declare(strict_types=1);

namespace app\repositories;

interface BlockedDomainRepositoryInterface
{
    /**
     *
     * @param string[] $domains
     */
    public function hasBlockedAncestor(array $domains): bool;
}
