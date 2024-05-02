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
    }
}