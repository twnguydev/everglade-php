<?php

require_once __DIR__ . '/../core/autoload.php';

try {
    $app = new Core\Core();
    $app->run();
} catch (Exception $e) {
    echo $e->getMessage();
}