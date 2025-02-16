<?php
// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}
/**
 * Verifica que exista una sesión activa de no ser así redirecciona al root
 *
 */
function isAuth() {
    if(!isset($_SESSION['login'])){
        if($_SERVER['REQUEST_URI'] !== '/')
            header('Location: /');
    }else{
        if(isset($_SESSION['admin'])) {
            header('Location: /admin');
        }else{
            if($_SERVER['REQUEST_URI'] !== '/cita')
                header('Location: /cita');
        }
    }
}

function isAdmin(){
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}


function esUltimo(string $actual, string $siguiente) : bool{
    if($actual !== $siguiente)
        return true;
    
    return false;
}
