<?php

// Modificar la categoria


require '../config/config.php';

/* Verifica si no está establecida la sesión de usuario o si el tipo de usuario no es 'admin'. 
Si no cumple estas condiciones, redirige a index.php y termina la ejecución del script.
*/
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Crea una instancia de la clase Database (una clase que maneja la conexión a la base de datos).
$db = new Database();

// Conexión a la  bd
$con = $db->conectar();

// Obtiene y limpia el nombre de la categoría desde $_POST. También obtiene el ID de la categoría desde $_POST.
$nombre = trim($_POST['nombre']);
$id = $_POST['id'];

// Prepara una consulta SQL para actualizar la tabla 'categorias' estableciendo el nuevo nombre donde el ID coincida.
$sql = $con->prepare("UPDATE categorias SET nombre=? WHERE id=?");

// Ejecuta la consulta preparada con los valores nombre e id
$sql->execute([$nombre, $id]);

// Después de actualizar la categoría, redirige de nuevo a index.php.
header('Location: index.php');
