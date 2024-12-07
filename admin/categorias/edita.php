<?php

// Editar categoria

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

// Conexión a la  bd
$con = $db->conectar();

// Obtiene el ID de la categoría que se desea editar desde $_GET.
$id = $_GET['id'];

// Prepara una consulta SQL para seleccionar la categoría específica por su ID.
$sql = $con->prepare("SELECT id, nombre FROM categorias WHERE id = ? LIMIT 1");

// Ejecuta la consulta preparada con el id
$sql->execute([$id]);

// Obtiene la categoría de la base de datos como un array asociativo.
$categoria = $sql->fetch(PDO::FETCH_ASSOC);

// Incluye el archivo de encabezado (header.php)
require '../header.php';

?>
<main>
    <div class="container-fluid px-3">
        <h3 class="mt-2">Modificar categoría</h3>

        <!-- Formulario para modificar la categoría -->
        <form action="actualiza.php" method="post" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <!-- Campo de entrada para el nombre de la categoría, con el valor inicial obtenido de la base de datos -->
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $categoria['nombre']; ?>" required autofocus>
            </div>
            <!-- Botón para guardar los cambios -->
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

    </div>
</main>


<?php 
// Incluye el archivo del pié de página
require '../footer.php'; 
?>