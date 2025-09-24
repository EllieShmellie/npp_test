<?php

declare(strict_types=1);

namespace app\services;

interface DomainBlockCheckerInterface
{
    public function check(string $address): bool;
}
