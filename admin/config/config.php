<?php

// Parametros de configuracion

$path = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$basePath = dirname($path, 2);

require_once $basePath . '/config/database.php';

// Sesión para panel de administración
session_name('admin_session');
session_start();

// URL de la tienda
define('SITE_URL', 'http://localhost/aka_mercado/');

// URL del panel de administración
define('ADMIN_URL', SITE_URL . 'admin/');

//  Contraseña para cifrado
define("KEY_CIFRADO", "ABCD.1234-");

/**
 * Metodo de cifrado OpenSSL.
 *
 *  Fuente: https://www.php.net/manual/es/function.openssl-get-cipher-methods.php
 */
define("METODO_CIFRADO", "aes-128-cbc");
