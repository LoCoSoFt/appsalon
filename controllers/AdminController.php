<?php

namespace Controllers;

use Model\AdminCitas;
use MVC\Router;

class AdminController {
    public static function index( Router $router ){
        session_start();
        // isAuth();
        isAdmin();

        $citas=[];
        $alertas = [];
        $formato_correcto = true;
        $fecha = date('Y-m-d');

        if($_GET){

            $fecha = $_GET['fecha'] ?? date('Y-m-d');
            $fechas = explode('-', $fecha);

            foreach ($fechas as $valor) {
                if(!is_numeric($valor)){
                    AdminCitas::setAlerta('error', 'Formato de fecha incorrecto bucle.');
                    error_log('entra al bucle');
                    $formato_correcto=false;
                    break;
                }
            }
    
            if($formato_correcto){
    
                if (checkdate($fechas[1] ?? 0, $fechas[2] ?? 0, $fechas[0] ?? 00 )){
                    //consulta BD, esto debería ser una vista
                    $consulta = 
                    "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) as cliente, 
                        usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio 
                    FROM citas 
                    LEFT OUTER JOIN usuarios ON citas.usuarioid = usuarios.id 
                    LEFT OUTER JOIN citasservicios ON citasservicios.citaid = citas.id 
                    LEFT OUTER JOIN servicios ON servicios.id = citasservicios.servicioid
                    WHERE fecha = '$fecha'";
                    $citas = AdminCitas::SQL($consulta);
                }else{
                    AdminCitas::setAlerta('error', 'Formato de fecha incorrecto.');
                    error_log('Fecha incorrecta checkdate');
                }
            }
        }
        
        $alertas = AdminCitas::getAlertas();

        $router->render('admin/index', 
            [
                'nombre' => $_SESSION['nombre'] ?? null,
                'citas' => $citas,
                'fecha' => $fecha,
                'alertas' => $alertas
            ]);

    }
}