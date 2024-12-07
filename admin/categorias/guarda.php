<?php

// Guardar categoria

require '../config/config.php';

/*
Verifica si no está establecida la sesión de usuario o si el tipo de usuario no es 'admin'. 
Si no cumple estas condiciones, redirige a index.php y termina la ejecución del script.
*/
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Crea una instancia de la clase Database (una clase que maneja la conexión a la base de datos).
$db = new Database();

// Conexión a la bd
$con = $db->conectar();

// Obtiene y limpia el nombre de la nueva categoría desde $_POST.
$nombre = trim($_POST['nombre']);

/* Prepara una consulta SQL para insertar una nueva categoría en la tabla 'categorias' con el nombre proporcionado 
y establece 'activo' a 1 por defecto.
*/
$sql = $con->prepare("INSERT INTO categorias (nombre, activo) VALUES (?, 1)");

// Ejecuta la consulta preparada con el nombre de la categoría
$sql->execute([$nombre]);

// Después de guardar la categoría, redirige de nuevo a index.php.
header('Location: index.php');
