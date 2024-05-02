<?php

namespace Core;

class App
{
    public string $name;
    public string $description;
    public string $keywords;
    public string $author;
    public string $url;
    public string $address;
    public string $phone;
    public string $email;

    protected Dotenv $dotenv;

    public function __construct()
    {
        $this->dotenv = new Dotenv(Define::ENV_FILE);

        $this->name = $this->dotenv->get('APP_NAME');
        $this->description = $this->dotenv->get('APP_DESCRIPTION');
        $this->keywords = $this->dotenv->get('APP_KEYWORDS');
        $this->author = $this->dotenv->get('APP_AUTHOR');
        $this->url = $this->dotenv->get('APP_URL');
        $this->address = $this->dotenv->get('APP_ADDRESS');
        $this->phone = $this->dotenv->get('APP_PHONE');
        $this->email = $this->dotenv->get('APP_EMAIL');

        $this->handleErrors();
    }

    private function handleErrors()
    {
        if ($this->name === '') {
            throw new \Exception('APP_NAME is not defined in the .env file');
        }
        if ($this->description === '') {
            throw new \Exception('APP_DESCRIPTION is not defined in the .env file');
        }
        if ($this->keywords === '') {
            throw new \Exception('APP_KEYWORDS is not defined in the .env file');
        }
        if ($this->author === '') {
            throw new \Exception('APP_AUTHOR is not defined in the .env file');
        }
        if ($this->url === '') {
            throw new \Exception('APP_URL is not defined in the .env file');
        }
        if ($this->address === '') {
            throw new \Exception('APP_ADDRESS is not defined in the .env file');
        }
        if ($this->phone === '') {
            throw new \Exception('APP_PHONE is not defined in the .env file');
        }
        if ($this->email === '') {
            throw new \Exception('APP_EMAIL is not defined in the .env file');
        }
    }
}