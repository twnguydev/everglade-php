<?php

namespace App\Middleware;

use Core\Request;
use Core\Controller;
use App\Entity\User;

class Auth
{
    protected Request $request;
    protected Controller $controller;

    public function __construct(string $methodBypass = null)
    {
        $this->request = new Request();
        $this->controller = new Controller();

        switch ($methodBypass) {
            case 'protectedRoute':
                $this->handle('protectedRoute');
                break;
            case 'protectedRouteAdmin':
                $this->handle('protectedRouteAdmin');
                break;
            default:
                $this->handle();
                break;
        }
    }

    public function handle(string $method = null): bool
    {
        $token = $this->request->getCookie('token');
        $userId = $this->request->getCookie('user_id');
        $username = $this->request->getCookie('username');

        if ((!$token || !$userId || !$username) && $method === 'protectedRoute') {
            $this->request->redirect('/auth');
            return false;
        }

        $user = $this->controller->getModel('User')->findOneBy('user', ['id' => $userId, 'token' => $token, 'username' => $username]);

        if (!$user && $method === 'protectedRoute') {
            $this->request->redirect('/auth');
            return false;
        }
        if ((!$user || $user->getRole() !== 'ROLE_ADMIN') && $method === 'protectedRouteAdmin') {
            $this->request->redirect('/');
            return false;
        }
        if (!$user) {
            if ($this->request->getSession('user')) {
                $this->request->unsetSession('user');
            }
            if ($this->request->getSession('isUserLoggedIn')) {
                $this->request->unsetSession('isUserLoggedIn');
            }
        } else {
            $this->request->setSession('user', $user);
            $this->request->setSession('isUserLoggedIn', true);
        }

        return true;
    }

    public function isUserLogged(int $id): ?object
    {
        $user = $this->controller->getModel('User')->findOneBy('user', ['id' => $id]);
        $token = $this->request->getCookie('token');
        $username = $this->request->getCookie('username');
        $user_id = $this->request->getCookie('user_id');

        if ($user && $user->getToken() === $token && $user->getUsername() === $username && $user->getId() === $user_id) {
            return $user;
        }

        return null;
    }
}