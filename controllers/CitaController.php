<?php

namespace Controllers;

use MVC\Router;

class CitaController {
    public static function index(Router $router) {
        session_start();
        isAuth();

        $router->render('cita/index', 
            [
                'nombre' => $_SESSION['nombre'] ?? null,
                'id' => $_SESSION['id'] ?? null
            ]);
    }
}