<?php

namespace Core;

class Define
{
    public const ENV_FILE = __DIR__ . '/../config.env';
    public const ROUTES_FILE = __DIR__ . '/../src/routes.php';
    public const LAYOUT_FILE = __DIR__ . '/../src/View/app.php';
    public const DATABASE_FILE = __DIR__ . '/../database.sql';
    public const ERROR_LOG_FILE = __DIR__ . '/../logs/errors.log';
    public const EXCEPTION_LOG_FILE = __DIR__ . '/../logs/exceptions.log';
    public const PUBLIC_CSS = __DIR__ . '/../public/css/';
    public const PUBLIC_JS = __DIR__ . '/../public/js/';
    public const PUBLIC_IMG = __DIR__ . '/../public/img/';
    public const PUBLIC = __DIR__ . '/../public/';

    public const DIRECTORIES = [
        'core' => __DIR__ . '/',
        'migrations' => __DIR__ . '/../migration/',
        'controllers' => __DIR__ . '/../src/Controller/',
        'models' => __DIR__ . '/../src/Model/',
        'entities' => __DIR__ . '/../src/Entity/',
        'traits' => __DIR__ . '/../src/Trait/',
        'views' => __DIR__ . '/../src/View/',
        'components' => __DIR__ . '/../src/View/components/',
        'middlewares' => __DIR__ . '/../src/Middleware/',
        'logs' => __DIR__ . '/../logs/',
        'tests' => __DIR__ . '/../tests/',
        'functional_tests' => __DIR__ . '/../tests/functional/',
        'unit_tests' => __DIR__ . '/../tests/unit/',
    ];

    public const NAMESPACES = [
        'core' => '\Core\\',
        'controllers' => '\App\Controller\\',
        'models' => '\App\Model\\',
        'entities' => '\App\Entity\\',
        'traits' => '\App\Trait\\',
        'views' => '\App\View\\',
        'middlewares' => '\App\Middleware\\',
        'functional_tests' => '\Tests\Functional\\',
        'unit_tests' => '\Tests\Unit\\',
    ];
}