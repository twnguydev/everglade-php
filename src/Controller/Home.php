<?php

namespace App\Controller;

use Core\Request;

class Home extends \Core\Controller
{
    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @view
     * @route /
     * @component home
     * @method GET
     */
    public function homeAction()
    {
        $test = 23;

        return [
            'test' => $test,
            'posts' => $this->request->server('REQUEST_METHOD'),
            'tests' => [1 => 'test', 2 => 'test2'],
        ];
    }
}