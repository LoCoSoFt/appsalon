<?php
namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;
use MVC\Router;

class APIController
{
    public static function index(Router $router) {
        $servicios = Servicio::all();
        // header('Content-type: application/json; charset=utf-8');
        echo json_encode($servicios);
        
    }

    public static function guardar() {
        //guarda la cita y retorna ID cita
        $cita = new Cita($_POST);
        
        $resultado = $cita->guardar();
        // $resultado = ['resultado' => false, 'servicios' => 0];
        //almacena los servicios asociados a la cita
        $idServicios = explode(',', $_POST['servicios']);

        if($resultado['resultado']){
            foreach ($idServicios as $idServicio) {
                $args = [
                    'citaId' => $resultado['id'],
                    'servicioId' => $idServicio
                ];

                $citaServicio = new CitaServicio($args);
                $citaServicio->guardar();
            }
        }
        //retorna respuesta
        $respuesta = [
            'resultado' => $resultado['resultado'],
            'servicios' => $idServicios
        ];

        echo json_encode($respuesta);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        
    }    
}
