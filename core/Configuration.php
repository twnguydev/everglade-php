<?php

namespace Core;

class Configuration
{
    protected array $config = [];

    protected Dotenv $dotenv;

    public function __construct()
    {
        $this->dotenv = new Dotenv(Define::ENV_FILE);
        $this->config['router_mode'] = $this->dotenv->get('ROUTER_MODE');
        $this->config['debug_mode'] = $this->dotenv->get('DEBUG_MODE');
    }

    public function getRouterMode(): string
    {
        return $this->config['router_mode'];
    }

    public function getDebugMode(): bool
    {
        return $this->config['debug_mode'] === 'true';
    }
}