<?php

$this->router->addRoute('/', 'homeAction', 'Home', 'GET', 'home', null);
$this->router->addRoute('/auth', 'loginAction', 'Auth', 'GET', 'login', null);
$this->router->addRoute('/auth/signup', 'registerAction', 'Auth', 'GET', 'signup', null);
$this->router->addRoute('/user/{id}', 'indexAction', 'User', 'GET', 'user', null);
$this->router->addRoute('/user/{id}/update', 'updateAction', 'User', 'POST', null, null);