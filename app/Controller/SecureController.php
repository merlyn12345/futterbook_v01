<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SecureController extends Controller
{
    public function start(Request $request, Response $response)
    {
        return $this->render($response, 'start.html', [
            'user' => $this->ci->get('session')->get('user'),
            'userId' => $this->ci->get('session')->get('userId'),
            'token' => $this->ci->get('session')->get('token')
        ]);
    }

    public function  status(Request $request, Response $response)
    {
        return $this->render($response, 'status.html', [
            'user' => $this->ci->get('session')->get('user'),
            'userId' => $this->ci->get('session')->get('userId'),
            'token' => $this->ci->get('session')->get('token')
        ]);
    }
}