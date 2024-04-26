<?php

namespace Core;

class Dotenv
{
    protected array $variables = [];

    public function __construct(string $filePath)
    {
        $this->load($filePath);
    }

    protected function load(string $filePath): void
    {
        if (file_exists($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $this->variables[$key] = $value;
                }
            }
        }
    }

    public function get(string $key)
    {
        return $this->variables[$key];
    }
}