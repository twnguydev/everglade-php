<?php

namespace Core;

class Mail
{
    protected string $host;
    protected string $port;
    protected string $username;
    protected string $password;
    protected string $from;
    protected string $fromName;

    protected Dotenv $dotenv;

    public function __construct()
    {
        $this->dotenv = new Dotenv(Define::ENV_FILE);

        $this->host = $this->dotenv->get('MAIL_HOST');
        $this->port = $this->dotenv->get('MAIL_PORT');
        $this->username = $this->dotenv->get('MAIL_USERNAME');
        $this->password = $this->dotenv->get('MAIL_PASSWORD');
        $this->from = $this->dotenv->get('MAIL_FROM');
        $this->fromName = $this->dotenv->get('MAIL_FROM_NAME');

        $this->handleErrors();
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getFromName(): string
    {
        return $this->fromName;
    }

    public function send(string $to, string $subject, string $message): bool
    {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' . $this->getFromName() . ' <' . $this->getFrom() . '>',
            'Reply-To: ' . $this->getUsername(),
            'X-Mailer: PHP/' . phpversion()
        ];

        $body = '<html><body>';
        $body .= '<h1>' . $subject . '</h1>';
        $body .= '<p>' . $message . '</p>';
        $body .= '</body></html>';

        $success = mail($to, $subject, $body, implode("\r\n", $headers));

        return $success;
    }

    private function handleErrors()
    {
        if ($this->host === '') {
            throw new \Exception('MAIL_HOST is not defined in the .env file');
        }
        if ($this->port === '') {
            throw new \Exception('MAIL_PORT is not defined in the .env file');
        }
        if ($this->username === '') {
            throw new \Exception('MAIL_USERNAME is not defined in the .env file');
        }
        if ($this->password === '') {
            throw new \Exception('MAIL_PASSWORD is not defined in the .env file');
        }
        if ($this->from === '') {
            throw new \Exception('MAIL_FROM is not defined in the .env file');
        }
        if ($this->fromName === '') {
            throw new \Exception('MAIL_FROM_NAME is not defined in the .env file');
        }
    }
}