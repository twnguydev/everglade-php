<?php

namespace App\Controller;

use Core\Request;

class Auth extends \Core\Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @view
     * @route /auth
     * @component login
     * @method GET
     */
    public function loginAction()
    {
        return [];
    }

    /**
     * @view
     * @route /auth/signup
     * @component signup
     * @method GET
     */
    public function signupAction()
    {
        $existingUserEmail = $this->getModel('User')->findOneBy('user', ['email' => 'tanguy.gibrat@epitech.eu']);
        $existingUserUsername = $this->getModel('User')->findOneBy('user', ['username' => 'twnguyy']);

        return [];
    }

    /**
     * @view
     * @route /auth/logout
     * @component login
     * @method GET
     */
    public function logout()
    {
        $userId = $this->request->getSession('user')->getId();
        $user = $this->getModel('User')->findOneBy('user', ['id' => $userId]);
        $user->setToken(null);
        $save = $this->getModel('User')->update('user', $user);

        if ($save) {
            $this->request->unsetCookie('token');
            $this->request->unsetCookie('user_id');
            $this->request->unsetCookie('username');
            $this->request->unsetSession('user');
            $this->request->unsetSession('isUserLoggedIn');

            $this->request->redirect('/myapp/auth');
        }

        return [];
    }

    /**
     * @data
     * @route /auth/login/register
     * @method POST
     */
    public function login()
    {
        if ($this->request->server('REQUEST_METHOD') === 'POST') {
            $usernameOrEmail = $this->request->post('usernameOrEmail');
            $password = $this->request->post('password');

            $existingUser = null;

            $existingUserEmail = $this->getModel('User')->findOneBy('user', ['email' => $usernameOrEmail]);
            if ($existingUserEmail) {
                $existingUser = $existingUserEmail;
            }

            if (!$existingUser) {
                $existingUserUsername = $this->getModel('User')->findOneBy('user', ['username' => $usernameOrEmail]);
                if ($existingUserUsername) {
                    $existingUser = $existingUserUsername;
                }
            }

            if (!$existingUser) {
                echo $this->request->sanitizeJson([
                    'status' => 'error',
                    'message' => 'Aucun compte n\'a été trouvé avec cet email ou ce nom d\'utilisateur.'
                ]);
                return;
            }

            $login = $this->getModel('User')->checkLogin($existingUser, $password);

            if ($login) {
                $this->request->setSession('user', $existingUser);
                $this->request->setSession('isUserLoggedIn', true);

                echo $this->request->sanitizeJson([
                    'status' => 'success',
                    'message' => 'Vous êtes connecté.',
                    'redirect' => '/myapp/'
                ]);
            } else {
                echo $this->request->sanitizeJson([
                    'status' => 'error',
                    'message' => 'Identifiants incorrects.'
                ]);
            }
        } else {
            echo $this->request->sanitizeJson([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de la connexion.'
            ]);
        }
    }

    /**
     * @data
     * @route /auth/signup/register
     * @method POST
     */
    public function signup()
    {
        if ($this->request->server('REQUEST_METHOD') === 'POST') {
            $genre = $this->request->post('genre');
            $email = $this->request->post('email');
            $username = $this->request->post('username');
            $phone = $this->request->post('phone');
            $password = $this->request->post('password');
            $confirmPassword = $this->request->post('confirmPassword');
            $firstname = $this->request->post('firstname');
            $lastname = $this->request->post('lastname');
            $birthdate = $this->request->post('birthdate');

            $errors = [];

            if ($password !== $confirmPassword) {
                $errors[] = 'Le mot de passe et la confirmation du mot de passe ne correspondent pas.';
            }

            $existingUserEmail = $this->getModel('User')->findOneBy('user', ['email' => $email]);
            $existingUserUsername = $this->getModel('User')->findOneBy('user', ['username' => $username]);

            if ($existingUserEmail) {
                $errors[] = 'Un compte existe déjà avec cet email.';
            }
            if ($existingUserUsername) {
                $errors[] = 'Un compte existe déjà avec ce nom d\'utilisateur.';
            }

            if (empty($errors)) {
                $registration = $this->getModel('user')->checkRegistration([
                    'genre' => $genre,
                    'email' => $email,
                    'username' => $username,
                    'phone' => $phone,
                    'password' => $password,
                    'confirmPassword' => $confirmPassword,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'birthdate' => $birthdate
                ]);

                if ($registration) {
                    echo $this->request->sanitizeJson([
                        'status' => 'success',
                        'message' => 'Votre compte a été créé avec succès.',
                        'redirect' => '/myapp/auth'
                    ]);
                }
            } else {
                echo $this->request->sanitizeJson([
                    'status' => 'error',
                    'message' => 'Une erreur est survenue lors de la création de votre compte.',
                    'errors' => $errors
                ]);
            }
        }
    }
}
