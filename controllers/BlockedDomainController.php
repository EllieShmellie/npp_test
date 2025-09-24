<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\DomainBlockCheckerInterface;
use InvalidArgumentException;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

final class BlockedDomainController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly DomainBlockCheckerInterface $domainBlockChecker,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'check' => ['GET'],
                ],
            ],
        ]);
    }

    public function actionCheck(): array
    {
        $domain = Yii::$app->request->getQueryParam('domain');
        if ($domain === null) {
            throw new BadRequestHttpException('Параметр запроса "domain" обязателен.');
        }

        try {
            $blocked = $this->domainBlockChecker->check($domain);
        } catch (InvalidArgumentException) {
            throw new BadRequestHttpException('Некорректное имя домена.');
        }

        return [
            'domain' => $domain,
            'blocked' => $blocked,
        ];
    }
}
