<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class InputController extends Controller
{


    public function getKategorien(Request $request, Response $response):Response
    {
        $kategorien = [];
        $sql = "SELECT DISTINCT kategorie FROM nahrung ORDER BY kategorie ASC";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $kategorien = $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $kategorien =json_encode($error);

        }
        return $this->render($response, 'submit.html', ["kategorien" => $kategorien,
            'user' => $this->ci->get('session')->get('user'),
            'userId' => $this->ci->get('session')->get('userId'),
            'token' => $this->ci->get('session')->get('token')
        ]);
    }


    public function getItems(Request $request, Response $response, $args=[]):Response
    {
        $items = [];
        $sql = "SELECT nahrungId, Name  FROM nahrung WHERE kategorie = '".$args['kategorie']."'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $items= $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $items =json_encode($error);

        }
        return $this->render($response, 'items.html', ["items" => $items]);
    }

    public function submit(Request $request, Response $response):Response
    {
        $recieved = $request->getParsedBody();  // da könnnen noch ein paar checks hin

        $userId = $this->ci->get('session')->get('userId');
        if(count($recieved) != 3){
            $response->write('Error parsing data'.' userid '.$userId);
            return $response->withHeader('Content-Type', 'text/html');
        }
        $userId = $this->ci->get('session')->get('userId');
        $sql = "INSERT INTO user_nahrung (u_n_userId, u_n_nahrungId, amount, ltime, stime) VALUES ('".$userId."', '".$recieved['item']."', '".$recieved['quantity']."', NOW(), NOW())";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $ergebnis = json_encode(array('Status' => 'OK.'));

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $ergebnis =json_encode($error);

        }
       return $this->render($response, 'ergebnis.html', ["ergebnis" => $ergebnis,
           'user' => $this->ci->get('session')->get('user'),
           'userId' => $this->ci->get('session')->get('userId'),
           'token' => $this->ci->get('session')->get('token')
           ]);  // Fehler- oder success mitgeben
    }
}