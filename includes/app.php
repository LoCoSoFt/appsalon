<?php

use Dotenv\Dotenv;
use Model\ActiveRecord;
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'funciones.php';
require 'config.php';
require 'database.php';

// Conectarnos a la base de datos
if(isset($db))
    ActiveRecord::setDB($db);
else
    exit;