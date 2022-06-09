<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class OutputController extends Controller
{
    public function usersFood(Request $request, Response $response):Response
    {
        $userId = $this->ci->get('session')->get('userId');

        $sql = "SELECT nahrungID, Name, `Energie (cal)`, amount, stime FROM user_nahrung LEFT JOIN nahrung ON nahrung.nahrungId = user_nahrung.u_n_nahrungId AND user_nahrung.u_n_userId = '".$userId."'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $entries = $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $entries =json_encode($error);

        }
        ////
        /// hier möchtest du die beiden werte miteinander verrechnen (den rohen db-output nochmal bearbeiten...)
        /// du möchtest also eine 'middleware' wie im beispiel, die db-daten processen kann
        ///
        $setData = $this->ci->get('dbrenderer')->setDbdata($entries);
        $processed = $this->ci->get('dbrenderer')->renderdata();
        return $this->render($response, 'usersFood.html', ["nahrungsmittel" => $processed,
            'user' => $this->ci->get('session')->get('user'),
            'userId' => $this->ci->get('session')->get('userId'),
            'token' => $this->ci->get('session')->get('token')
            ]);
    }

    public function details(Request $request, Response $response, $args=[]):Response
    {
        $error = false;
        $sql = "SELECT nahrungID, Name FROM nahrung WHERE nahrungID = '".$args['id']."'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $nahrungsmittel = $stmt->fetchAll();


        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $nahrungsmittel =json_encode($error);

        }
        // deshalb
        if($error){
            throw new HttpNotFoundException($request, $response);
        }

        return $this->render($response, 'details.html', ['nahrungsmittel' => $nahrungsmittel,
            'user' => $this->ci->get('session')->get('user'),
            'userId' => $this->ci->get('session')->get('userId'),
            'token' => $this->ci->get('session')->get('token')]); //array index of false ist das erste element
    }
}