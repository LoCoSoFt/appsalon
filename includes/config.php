<?php

setlocale( LC_TIME, 'es', 'spa', 'es_PE' );
date_default_timezone_set('America/Lima');

error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE); // always use TRUE
ini_set('display_errors', TRUE); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment
ini_set('log_errors', TRUE);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/..' . '/php-error.log');