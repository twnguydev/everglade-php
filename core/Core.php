<?php

namespace Core;

use Core\Entity\EntityGenerate;

class Core
{
    public Router $router;
    public Controller $controller;
    public Configuration $configuration;
    public EntityGenerate $entity;
    private Dotenv $dotenv;

    public function __construct()
    {
        $this->configuration = new Configuration();

        if ($this->configuration->getDebugMode()) {
            $this->handleErrors();
        }

        $this->dotenv = new Dotenv(Define::ENV_FILE);
        $this->dotenv->handle(Define::ENV_FILE);

        $this->router = new Router();
        $this->controller = new Controller();
        $this->entity = new EntityGenerate();
    }

    public function run()
    {
        try {
            $routerMode = $this->configuration->getRouterMode();

            if ($routerMode === 'hybrid') {
                $this->entity->generateAllSql();
                $this->controller->classRequest($this->router);
                $this->router->routeRequest();
            } elseif ($routerMode === 'static') {
                $this->entity->generateAllSql();
                include_once Define::ROUTES_FILE;
                $this->router->routeRequest();
            } elseif ($routerMode === 'dynamic') {
                $this->entity->generateAllSql();
                $this->router->dynamicRouteRequest();
            } else {
                throw new \Exception('Invalid router mode');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    protected function handleErrors(): void
    {
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', Define::ERROR_LOG_FILE);
        error_reporting(E_ALL);
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            date_default_timezone_set('Europe/Paris');
            $time = date('d-M-Y H:i:s e', time());
            $message = "[{$time}] Erreur : [$errno] $errstr dans $errfile à la ligne $errline\n";
            error_log($message, 3, Define::ERROR_LOG_FILE);
            $frontMessage = 'Consultez le journal des logs pour plus de détails.';
            include Define::DIRECTORIES['views'] . 'error500.php';
            exit;
        });
        set_exception_handler(function (\Exception $e) {
            date_default_timezone_set('Europe/Paris');
            $time = date('d-M-Y H:i:s e', time());
            $message = "[{$time}] Exception : {$e->getMessage()}. File : {$e->getFile()} line {$e->getLine()}\n";
            error_log($message, 3, Define::EXCEPTION_LOG_FILE);
            $frontMessage = 'Consultez le journal des logs pour plus de détails.';
            include Define::DIRECTORIES['views'] . 'error500.php';
            exit;
        });
    }
}