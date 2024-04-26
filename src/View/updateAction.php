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
        $username = null;
        $email = null;
        $password = null;
        $confirmPassword = null;
        $phone = null;

        $user = $this->getModel('User')->findOneBy('id', ['id' => $id]);
        $authenticatedUserId = $this->auth->isUserLogged($id)->getId();

        if ($id !== $authenticatedUserId) {
            $errors[] = 'Vous n\'avez pas les droits pour effectuer cette action.';
        }

        if (isset($this->request->post('username'))) {
            $username = $this->request->post('username');

            if (strlen($username) < 3) {
                $errors[] = 'Le pseudonyme est incorrect.';
            } else {
                $existingUserByUsername = $this->getModel('User')->findOneBy('username', ['username' => $username]);

                if ($existingUserByUsername && $existingUserByUsername->getId() !== $id) {
                    $errors[] = 'Le pseudonyme est déjà pris.';
                }
            }
        }

        if (isset($this->request->post('email'))) {
            $email = $this->request->post('email');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'L\'adresse email est incorrecte.';
            } else {
                $existingUserByEmail = $this->getModel('User')->findOneBy('email', ['email' => $email]);

                if ($existingUserByEmail && $existingUserByEmail->getId() !== $id) {
                    $errors[] = 'L\'adresse email est déjà prise.';
                }
            }
        }

        if (isset($this->request->post('newPassword'))) {
            $password = $this->request->post('newPassword');

            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }
        }

        if (isset($this->request->post('phone'))) {
            $phone = $this->request->post('phone');

            if (!preg_match('/^0[1-9]([-. ]?[0-9]{2}){4}$/', $phone)) {
                $errors[] = 'Le numéro de téléphone est incorrect. Format valide : 0123456789.';
            }
        }

        if (empty($errors)) {
            $registration = $this->getModel('user')->checkUpdate([
                'email' => $email,
                'username' => $username,
                'phone' => $phone,
                'password' => $password,
                'confirmPassword' => $confirmPassword,
            ]);

            if ($registration) {
                echo $this->request->sanitizeJson([
                    'status' => 'success',
                    'message' => 'Votre compte a été modifié avec succès.',
                    'redirect' => '/auth'
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
