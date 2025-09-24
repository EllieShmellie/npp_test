<?php

declare(strict_types=1);

use app\repositories\BlockedDomainRepository;
use app\repositories\BlockedDomainRepositoryInterface;
use app\services\DomainBlockChecker;
use app\services\DomainBlockCheckerInterface;
use yii\caching\CacheInterface;

return [
    'definitions' => [
        BlockedDomainRepositoryInterface::class => BlockedDomainRepository::class,
        DomainBlockCheckerInterface::class => static function (\yii\di\Container $container): DomainBlockCheckerInterface {
            $repository = $container->get(BlockedDomainRepositoryInterface::class);
            $cache = null;
            $app = \Yii::$app;

            if ($app !== null && $app->has('cache')) {
                $component = $app->get('cache');
                if ($component instanceof CacheInterface) {
                    $cache = $component;
                }
            }

            return new DomainBlockChecker($repository, $cache);
        },
    ],
];
