<?php

require_once __DIR__ . '/Define.php';

use Core\Define;

spl_autoload_register(function ($class) {
    foreach (Define::NAMESPACES as $namespace => $prefix) {
        $prefix = trim($prefix, '\\');
        $classParts = explode('\\', $class);
        if (strpos($class, $prefix) === 0) {
            $relativeClass = substr($class, strlen($prefix));
            $relativeClass = ltrim($relativeClass, '\\');
            $relativeClass = str_replace('\\', '/', $relativeClass);
            $file = stream_resolve_include_path(Define::DIRECTORIES[$namespace] . $relativeClass . '.php');

            if ($file !== false) {
                include $file;
                return;
            } else {
                $newFile = stream_resolve_include_path(Define::DIRECTORIES[$namespace] . end($classParts) . '.php');

                if ($newFile !== false) {
                    include $newFile;
                    return;
                }
            }
        }
    }

    $traitsDir = Define::DIRECTORIES['traits'];
    $traitFile = $traitsDir . $class . '.php';
    if (file_exists($traitFile)) {
        require_once $traitFile;
        return;
    } else {
        $traitsDir = Define::DIRECTORIES['core'];
        $traitFile = $traitsDir . $class . '.php';

        if (file_exists($traitFile)) {
            require_once $traitFile;
            return;
        }
    }
});