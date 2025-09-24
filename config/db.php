<?php

return [
    'class' => 'yii\\db\\Connection',
    'dsn' => getenv('DB_DSN') ?: 'pgsql:host=127.0.0.1;port=5432;dbname=yii2app',
    'username' => getenv('DB_USERNAME') ?: 'yii2',
    'password' => getenv('DB_PASSWORD') ?: 'yii2pass',
    'charset' => 'utf8',
];
