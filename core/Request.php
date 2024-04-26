<?php

namespace Core;

class Request
{
    protected array $get;
    protected array $post;
    protected array $server;

    public function __construct()
    {
        $this->get = $this->sanitize($_GET);
        $this->post = $this->sanitize($_POST);
        $this->server = $this->sanitize($_SERVER);
    }

    public function get(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function server(string $key, $default = null)
    {
        return $this->server[$key] ?? $default;
    }

    public function getAllPost(): array
    {
        return $this->post;
    }

    public function getAllGet(): array
    {
        return $this->get;
    }

    public function getAllServer(): array
    {
        return $this->server;
    }

    protected function sanitize(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = htmlspecialchars(stripslashes(strip_tags(trim($value))));
            }
        }

        return $sanitized;
    }

    public function sanitizeJson($data): string
    {
        if (is_string($data) && (json_decode($data) !== null)) {
            $data = json_decode($data, true);
        }

        if (is_array($data)) {
            $sanitizedData = $this->sanitize($data);
        } else {
            return '{}';
        }

        header('Content-Type: application/json');
        return json_encode($sanitizedData);
    }

    public function setSession(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function getSession(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function unsetSession(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroySession(): void
    {
        session_destroy();
    }

    public function setCookie(string $key, $value, int $expiration): void
    {
        setcookie($key, $value, time() + $expiration, '/');
    }

    public function getCookie(string $key, $default = null)
    {
        return $_COOKIE[$key] ?? $default;
    }

    public function unsetCookie(string $key): void
    {
        setcookie($key, '', time() - 3600, '/');
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }
}