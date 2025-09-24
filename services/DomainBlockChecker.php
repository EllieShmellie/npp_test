<?php

declare(strict_types=1);

namespace app\services;

use app\repositories\BlockedDomainRepositoryInterface;
use InvalidArgumentException;
use yii\caching\CacheInterface;

final class DomainBlockChecker implements DomainBlockCheckerInterface
{
    private const CACHE_KEY_PREFIX = 'domain-block-checker';

    public function __construct(
        private readonly BlockedDomainRepositoryInterface $repository,
        private readonly ?CacheInterface $cache = null,
        private readonly int $cacheTtl = 300
    ) {
    }

    public function check(string $address): bool
    {
        $normalized = $this->normalizeDomain($address);
        if ($normalized === null) {
            throw new InvalidArgumentException('Некорректное имя домена.');
        }

        $suffixes = $this->buildSuffixes($normalized);

        if ($this->cache === null) {
            return $this->repository->hasBlockedAncestor($suffixes);
        }

        $cacheKey = [self::CACHE_KEY_PREFIX, $normalized];

        return (bool) $this->cache->getOrSet(
            $cacheKey,
            fn (): bool => $this->repository->hasBlockedAncestor($suffixes),
            $this->cacheTtl
        );
    }

    private function normalizeDomain(string $address): ?string
    {
        $trimmed = strtolower(trim($address));
        if ($trimmed === '') {
            return null;
        }

        $trimmed = rtrim($trimmed, '.');
        if ($trimmed === '') {
            return null;
        }

        $ascii = $this->toAsciiDomain($trimmed);
        if ($ascii === null) {
            return null;
        }

        if (!filter_var($ascii, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return null;
        }

        return $ascii;
    }

    private function toAsciiDomain(string $domain): ?string
    {
        $result = idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);

        if ($result === false) {
            return null;
        }

        return strtolower($result);
    }

    /**
     * @return string[]
     */
    private function buildSuffixes(string $domain): array
    {
        $parts = explode('.', $domain);
        $suffixes = [];
        $partCount = count($parts);

        for ($i = 0; $i < $partCount; $i++) {
            $suffixes[] = implode('.', array_slice($parts, $i));
        }

        return $suffixes;
    }
}
