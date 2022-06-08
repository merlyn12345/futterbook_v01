<?php

use \Psr\Container\ContainerInterface;

return function (ContainerInterface $container){
    $container->set('settings', function(){
        return [
            'displayErrorDetails' => true,
            'logErrors' => true,
            'logErrorDetails' => true,
            'dbHost' => '127.0.0.1',
            'dbUser' => 'georg',
            'dbPass' => '1dwidz2',
            'dbName' => 'slimtut'
        ];
    });
};