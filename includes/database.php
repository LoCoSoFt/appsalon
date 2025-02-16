<?php

try {
    $db = new mysqli(
        $_ENV['DB_HOST'], 
        $_ENV['DB_USER'], 
        $_ENV['DB_PASS'], 
        $_ENV['DB_NAME']
    );

    $db->set_charset('utf8mb4');
    if(!$db) {
        exit;
    }
    return $db;
} catch (Exception $e) {
    echo "No se pudo conectar a la BD. Error de depuración " . mysqli_connect_errno();
    error_log('database::conectarDB()->' . $e->getMessage() );
    return null;
}