<?php
$projectName = isset($argv[1]) ? $argv[1] : '';

if (!empty($projectName)) {
    $envContent = "ENV_FOLDER=$projectName\n";
    $envFile = __DIR__ . '/config.env';

    file_put_contents($envFile, $envContent, FILE_APPEND | LOCK_EX);
}