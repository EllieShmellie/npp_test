# Проверка доменов

Тестовый сервис на Yii2 проверяет, заблокирован ли домен или любой из его родительских доменов. Эндпоинт: `GET /blocked-domain/check?domain=example.com`.

## Быстрый старт (Docker)

```bash
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php yii migrate
```

После миграций таблица заполнится тестовыми ограничениями (`facebook.com`, `vk.com`, `ok.ru`, `instagram.com`).

Проверить работу можно по адресу `http://localhost:8080/blocked-domain/check?domain=images.facebook.com`.

## Тесты

```bash
docker-compose exec app ./vendor/bin/phpunit
```

## Что внутри

- `migrations/m240511_000001_create_blocked_domain_table.php` — структура таблицы заблокированных доменов.
- `migrations/m240511_000002_seed_blocked_domains.php` — наполнение тестовыми записями.
- `app\services\DomainBlockChecker` — нормализация домена, поиск предков и кэширование результата.
- `app\controllers\BlockedDomainController` — REST-ответ с флагом `blocked`.
- `tests/phpunit/unit/DomainBlockCheckerTest.php` — юнит-тест для ключевых сценариев.
