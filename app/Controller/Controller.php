<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;

abstract class Controller {
    protected ContainerInterface $ci;

    public function __construct(ContainerInterface $ci) 
    {
        $this->ci = $ci;
    }


    protected function Render(Response $response, $template, $data=[]){
        $html = $this->ci->get('templating')->render($template, $data);
        $response->getBody()->write($html);
        return $response;
    }

}
