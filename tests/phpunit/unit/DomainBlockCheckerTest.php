<?php

declare(strict_types=1);

namespace tests\phpunit\unit;

use app\repositories\BlockedDomainRepositoryInterface;
use app\services\DomainBlockChecker;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DomainBlockCheckerTest extends TestCase
{
    public function testExactDomainBlocked(): void
    {
        $checker = new DomainBlockChecker(new InMemoryBlockedDomainRepository(['facebook.com']));

        self::assertTrue($checker->check('facebook.com'));
    }

    public function testSubdomainBlockedWhenParentDomainIsBlocked(): void
    {
        $checker = new DomainBlockChecker(new InMemoryBlockedDomainRepository(['facebook.com']));

        self::assertTrue($checker->check('images.facebook.com'));
    }

    public function testSiblingDomainNotBlocked(): void
    {
        $checker = new DomainBlockChecker(new InMemoryBlockedDomainRepository(['facebook.com']));

        self::assertFalse($checker->check('facebook.com.ru'));
    }

    public function testInvalidDomainThrows(): void
    {
        $checker = new DomainBlockChecker(new InMemoryBlockedDomainRepository([]));

        $this->expectException(InvalidArgumentException::class);
        $checker->check('not a domain');
    }
}

final class InMemoryBlockedDomainRepository implements BlockedDomainRepositoryInterface
{
    public function __construct(private readonly array $domains)
    {
    }

    public function hasBlockedAncestor(array $domains): bool
    {
        return [] !== array_intersect($this->domains, $domains);
    }
}
