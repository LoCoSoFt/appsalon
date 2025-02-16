<?php

namespace MVC;

class Router {
    public $rutasPOST = [];
    public $rutasGET = [];

    /**
     * Llena el arreglo de rutas GET
     *
     * @param string $url la ruta permitida en GET
     * @param string $fn el método asociado a esa ruta
     * @return void
     */
    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    /**
     * Llena el arreglo de rutas POST
     *
     * @param string $url la ruta permitida en el POST
     * @param string $fn el método asociado a esa ruta
     * @return void
     */
    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    /**
     * Verifica si la ruta solicitada es una ruta permitida, de ser así 
     * establece el método asociado de lo contrario Error 404
     *
     * @return void
     */
    public function comprobarRutas() {

/*         if(!session_id())
            session_start(); */
        $auth = $_SESSION['login'] ?? null;

        //rutas protegidas
        //TODO: Se puede cargar rutas protegidas desde un JSON
        $rutas_protegidas = 
            [      
            ];

        // $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        //strtok no detecta lo que está en el query string
        $urlActual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET'){
            $fn = $this->rutasGET[$urlActual] ?? null;
        }else{
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        //protección de rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('Location: /');
            error_log('Router::comprobarRutas-> Intentando acceder a ruta protegida');
        }

        if($fn) {
            call_user_func($fn, $this);
        }else{
            echo 'Página no encontrada';
        }
    }

    /**
     * Renderiza la vista con los datos enviados y crea una variable de variable 
     * por cada elemento del array y pueda ser visto desde el View
     *
     * @param string $view la vista a renderizar
     * @param array $datos los datos a mostrar en la vista
     * @return void
     */
    public function render($view, $datos = []){
        //crea una variable por cada elemento del array
        foreach ($datos as $key => $value) {
            $$key = $value;
        }
    //    extract($datos);

        ob_start();
        include __DIR__ . "/views/$view.php";
        
        $contenido = ob_get_clean();
        include __DIR__ . "/views/layout.php";
    }
}