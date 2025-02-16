<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        //si ya esta loggeado...
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            session_start();
            isAuth(); //redirecciona
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();
            if(empty($alertas)) {
                //comprobar que exista el usuario
                $usuario = Usuario::where('email', "'" . $auth->email . "'");
                
                if($usuario){
                    if($usuario->comprobarPasswordYConfirmacion($auth->password)){
                        // if(!session_status() === PHP_SESSION_ACTIVE ){
                            session_start();
                            error_log('iniciando sesion en login');
                        // }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //rediccionamiento
                        if($usuario->admin){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            error_log('LoginController::login-> login exitoso');
                            header('Location: /cita');
                        }
                        exit;
                    }
                    
                }else{
                    Usuario::setAlerta('error', 'Usuario no está registrado.');
                    error_log('LoginController::login-> login fallido');
                }

                $alertas = Usuario::getAlertas();
            }
        }
        $router->render('auth/login', 
            [
                'alertas' => $alertas
            ]);
    }

    public static function logout(Router $router){
            session_start();            
            $_SESSION = [];
            session_destroy();

            error_log('LoginController::logout-> sesión cerrada');
            header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', "'" . $auth->email . "'");
                
                if($usuario && $usuario->confirmado){
                    $usuario->crearToken();
                    $usuario->guardar();

                    $mail = new Email();
                    if ($mail->enviarInstrucciones($usuario->nombre, $usuario->email, $usuario->token))
                        Usuario::setAlerta('exito', 'Se ha enviado un email a ' . $usuario->email . ' con las instrucciones para reestablecer tu contraseña');
                    
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no lo ha confirmado');
                }

            }
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', ['alertas' => $alertas]);
    }

    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        //obtiene el token y ubica al usuario según su token
        $token = s($_GET['token']) ?? '';
        $token = "'" . $token ."'";
        $usuario = Usuario::where('token', $token);
        
        if (empty($usuario)) {
            $error = true;
            Usuario::setAlerta('error', 'Usuario no válido');
            error_log('Usuario::recuperar-> Usuario con token ' . $token . ' no se pudo validar');
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nuevoPassword = new Usuario($_POST);
            
            if($nuevoPassword->validarPassword()){
                //reemplaza valores en password u elimina el token
                $usuario->password = null;
                $usuario->password = $nuevoPassword->password;
                $usuario->token = null;
                $usuario->hashPassword();

                if ($usuario->guardar()) {
                    error_log('LoginController::recuperar-> se recuperó contraseña');
                    header('Location: /');
                }else{
                    error_log('LoginController::recuperar-> No se pudo recuperar contraseña');
                    return;
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', 
            [
                'alertas' => $alertas,
                'error' => $error
            ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario();

        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)){
                if ($usuario->existeUsuario()){
                    $alertas = Usuario::getAlertas();
                }else{
                    //hashear pw
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    
                    $email = new Email();

                    $email->enviarConfirmacion($usuario->nombre, $usuario->email, $usuario->token);
                    $resultado = $usuario->guardar();
                    if ($resultado['resultado']){
                        header('Location: /mensaje');
                    }
                }
            }
        }


        $router->render('auth/crear-cuenta', 
            [
                'usuario' => $usuario,
                'alertas' => $alertas
            ]
        );
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $token = "'" . $token . "'";

        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //setear confirmacion = 1 y token = null, eliminar token 6792bed775cc6
            $usuario->confirmado = 1;
            $usuario->token = null;
            if ($usuario->guardar()){
                Usuario::setAlerta('exito', 'Cuenta comprobada correctamente.');
                $mail = new Email();
                $mail->enviarBienvenida($usuario->nombre, $usuario->email);
            }
            
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', 
            [
                'alertas' => $alertas
            ]);
    }
}