<?php

namespace App\Controller;

use Core\Request;
use App\Middleware\Auth;

class User extends \Core\Controller
{
    public Request $request;
    public Auth $auth;

    public function __construct()
    {
        $this->request = new Request();
        $this->auth = new Auth();
    }

    /**
     * @view
     * @route /user/{id}
     * @component profile
     * @middleware Auth
     * @method GET
     */
    public function indexAction(int $id)
    {
        $user = $this->getModel('User')->findOneBy('user', ['id' => $id]);

        if (!$user) {
            $this->redirect('/myapp/404');
        }

        $history = $this->getModel('History')->findAll('history', ['id_user' => $id]);

        $historyData = [];
        foreach ($history as $item) {
            $movie = $this->getModel('Movie')->findOneBy('movie', ['id' => $item->id_movie]);
            $historyData[] = [
                'id' => $item->id,
                'title' => $movie->title,
                'genre' => $this->getModel('MovieGenre')->findOneBy('movie_genre', ['id' => $movie->id_genre])->name,
                'director' => $movie->director,
                'release_date' => date_format(date_create($movie->release_date), 'd/m/Y'),
                'date' => date('d/m/Y H:i', strtotime($item->date)),
            ];
        }

        return [
            'access' => $this->auth->isUserLogged($id)->getId() === $user->getId(),
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'birthdate' => $user->getBirthdate(),
            'history' => $historyData,
        ];
    }

    /**
     * @data
     * @route /user/{id}/update
     * @middleware Auth
     * @method POST
     */
    public function updateAction(int $id)
    {
        if ($this->request->server('REQUEST_METHOD') === 'POST') {
            $errors = [];
            $username = $this->request->post('username', null);
            $email = $this->request->post('email', null);
            $password = $this->request->post('password', null);
            $confirmPassword = $this->request->post('confirmPassword', null);
            $phone = $this->request->post('phone', null);

            $user = $this->getModel('User')->findOneBy('user', ['id' => $id]);
            $authenticatedUserId = $this->auth->isUserLogged($id)->getId();

            if ($id !== intval($authenticatedUserId)) {
                $errors[] = 'Vous n\'avez pas les droits pour effectuer cette action.';
            }

            if ($this->request->post('username') !== null) {
                $username = $this->request->post('username');

                if (strlen($username) < 3) {
                    $errors[] = 'Le pseudonyme est incorrect.';
                } else {
                    $existingUserByUsername = $this->getModel('User')->findOneBy('user', ['username' => $username]);

                    if ($existingUserByUsername && $existingUserByUsername->getId() !== $id) {
                        $errors[] = 'Le pseudonyme est déjà pris.';
                    }
                }
            }

            if ($this->request->post('email') !== null) {
                $email = $this->request->post('email');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'L\'adresse email est incorrecte.';
                } else {
                    $existingUserByEmail = $this->getModel('User')->findOneBy('user', ['email' => $email]);

                    if ($existingUserByEmail && $existingUserByEmail->getId() !== $id) {
                        $errors[] = 'L\'adresse email est déjà prise.';
                    }
                }
            }

            if ($this->request->post('confirmPassword') !== null) {
                $confirmPassword = $this->request->post('confirmPassword');

                if (strlen($confirmPassword) < 8) {
                    $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
                }
            }

            if ($this->request->post('phone') !== null) {
                $phone = $this->request->post('phone');

                if (!preg_match('/^0[1-9]([-. ]?[0-9]{2}){4}$/', $phone)) {
                    $errors[] = 'Le numéro de téléphone est incorrect. Format valide : 0123456789.';
                }
            }

            if (empty($errors)) {
                $registration = $this->getModel('user')->checkUpdate($user, [
                    'id' => $id,
                    'email' => $email,
                    'username' => $username,
                    'phone' => $phone,
                    'password' => $password,
                    'confirmPassword' => $confirmPassword,
                ]);

                if ($registration) {
                    $this->request->unsetCookie('username');
                    $this->request->setCookie('username', $username ?? $user->getUsername(), 3600 * 24 * 7);
                    echo $this->request->sanitizeJson([
                        'status' => 'success',
                        'message' => 'Votre compte a été modifié avec succès.',
                        'redirect' => '/myapp/user/' . $id
                    ]);
                } else {
                    echo $this->request->sanitizeJson([
                        'status' => 'error',
                        'message' => 'Une erreur est survenue lors de la modification de votre compte.',
                    ]);
                }
            } else {
                echo $this->request->sanitizeJson([
                    'status' => 'error',
                    'message' => 'Une erreur est survenue lors de la modification de votre compte.',
                    'errors' => $errors
                ]);
            }
        }
    }
}
