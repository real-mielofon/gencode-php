<?php

namespace Api;

use \Slim\Slim;
use \Exception;




class Authenticate extends \Slim\Middleware
{
    public function call()
    {
        // Get reference to application
        $app = $this->app;

        if (!isset($_SESSION['user'])) {
            $app->redirect('/#/login');
        } else {
            $this->next->call();
        };
    }
}

//    private $authenticate = function () {
//        return function () use ($app) {
//            if (!isset($_SESSION['user'])) {
//                $app = \Slim\Slim::getInstance();
//                $app->redirect('/login');
//            };
//        };
//    }



