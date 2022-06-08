<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{

    public function login(Request $request, Response $response):Response
    {

        if($request->isPost()){
            $userarray = $this->authenticateUser($request);
            if(count($userarray) == 1){
                $this->ci->get('session')->set('user', $request->getParam('username'));
                $this->ci->get('session')->set('userId', $userarray[0]['userId']);
                $this->ci->get('session')->set('token', $userarray[0]['token']);
                return $response->withRedirect('/secure');
            }

        }
        return $this->render($response, 'login.html');
    }


    public function logout(Request $request, Response $response):Response
    {
        $this->ci->get('session')->delete('user');
        $this->ci->get('session')->delete('userId');
        $this->ci->get('session')->delete('token');
        return $response->withRedirect('/');
    }


    private function authenticateUser(Request $request):Array
    {
        $userarray=[];
        /**
         *  i did not find any information on how slim treats security on parameters in requests
         *  trying to mitigate here at least little
         */
        $username = filter_var($request->getParam('username'), FILTER_SANITIZE_STRING);

        $sql="SELECT userId, username, token FROM user WHERE username='$username'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $userarray = $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );


        }
        return $userarray;
    }
}