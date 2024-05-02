<?php

namespace App\Controller;

use Core\Request;

class History extends \Core\Controller
{
    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @data
     * @route /history/{id}/add
     * @middleware Auth
     * @method POST
     */
    public function historyAction(int $id)
    {
        date_default_timezone_set('Europe/Paris');

        if ($this->request->server('REQUEST_METHOD') === 'POST') {
            $movieId = $this->request->post('dataId');
            $userId = $this->request->getSession('user')->getId();

            if ($id === intval($userId)) {
                $date = date('Y-m-d H:i:s');

                $registration = $this->getModel('History')->checkRegistration([
                    'id_movie' => $movieId,
                    'id_user' => $userId,
                    'date' => $date
                ]);

                if ($registration) {
                    echo $this->request->sanitizeJson([
                        'status' => 'success',
                        'message' => 'Movie added to history',
                        'redirect' => '/movie'
                    ]);
                } else {
                    echo $this->request->sanitizeJson([
                        'status' => 'error',
                        'message' => 'An error occurred while adding the movie to history',
                    ]);
                }
            } else {
                echo $this->request->sanitizeJson([
                    'status' => 'error',
                    'message' => 'Invalid user id',
                ]);
            }
        } else {
            echo $this->request->sanitizeJson([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }
    }
}
