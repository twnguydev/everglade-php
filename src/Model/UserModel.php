<?php

namespace App\Model;

use Core\ORM;
use Core\Request;
use App\Entity\User;

class UserModel extends ORM
{
    public function createUserInstance(): User
    {
        return new User();
    }

    public function checkRegistration(array $data): bool
    {
        date_default_timezone_set('Europe/Paris');

        $user = $this->createUserInstance();
    
        $email = $data['email'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $firstname = $data['firstname'] ?? null;
        $lastname = $data['lastname'] ?? null;
        $birthdate = $data['birthdate'] ?? null;
        $phone = $data['phone'] ?? null;
        $genre = $data['genre'] ?? null;

        if (!$email || !$username || !$password || !$firstname || !$lastname || !$birthdate || !$phone || !$genre) {
            return false;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setBirthdate($birthdate);
        $user->setPhone($phone);
        $user->setToken(null);
        $user->setGender($genre);
        $user->setRole('USER_ROLE');
        $user->setStatus('active');
        $user->setCreatedAt(date('Y-m-d'));
        $user->setUpdatedAt(date('Y-m-d'));

        return $this->save('user', $user);
    }
    
    public function checkLogin(object $userObject, string $password): bool
    {
        $request = new Request();
    
        if ($userObject && password_verify($password, $userObject->getPassword())) {
            $token = bin2hex(random_bytes(32));
            $userObject->setToken($token);
            $request->setCookie('token', $token, 3600 * 24 * 7);
            $request->setCookie('user_id', $userObject->getId(), 3600 * 24 * 7);
            $request->setCookie('username', $userObject->getUsername(), 3600 * 24 * 7);
            $request->setSession('user', $userObject);
            $request->setSession('isUserLoggedIn', true);
            return $this->update('user', $userObject);
        }

        return false;
    }

    public function checkUpdate(object $userObject, array $data): bool
    {
        date_default_timezone_set('Europe/Paris');
        
        $email = $data['email'] ?? $userObject->getEmail();
        $username = $data['username'] ?? $userObject->getUsername();
        $phone = $data['phone'] ?? $userObject->getPhone();
        $password = $data['password'] ?? null;
        $newPassword = $data['confirmPassword'] ?? null;

        if ($password !== null && !password_verify($password, $userObject->getPassword())) {
            return false;
        }

        if ($email !== null) {
            $userObject->setEmail($email);
        }
        if ($username !== null) {
            $userObject->setUsername($username);
        }
        if ($phone !== null) {
            $userObject->setPhone($phone);
        }
        if ($newPassword !== null) {
            $userObject->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
        }

        $userObject->setUpdatedAt(date('Y-m-d'));

        return $this->update('user', $userObject);
    }
}