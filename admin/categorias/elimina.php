<?php

// Eliminar categoria

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

// Conexión a la bd
$con = $db->conectar();

// Obtiene el ID de la categoría que se desea eliminar desde $_POST.
$id = $_POST['id'];

// Prepara una consulta SQL para actualizar la categoría estableciendo 'activo' a 0 en lugar de eliminar físicamente el registro.
$sql = $con->prepare("UPDATE categorias SET activo=0 WHERE id=?");

// Ejecuta la consulta preparada con el ID
$sql->execute([$id]);

// Después de dar de baja la categoría, redirige de nuevo a index.php.
header('Location: index.php');
