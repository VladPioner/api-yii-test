<?php

define(DB_HOST,'localhost');
define(DB_NEME,'api_test');
define(DB_USER,'root');
define(DB_PASS,'');

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.DB_HOST.';dbname='.DB_NEME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
