<?php

// Mostrar el formulario para ingresar una nueva categoria

require '../config/config.php';

/*
Verifica si no está establecida la sesión de usuario o si el tipo de usuario no es 'admin'. 
Si no cumple estas condiciones, redirige a index.php y termina la ejecución del script.
*/
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Incluye el archivo de encabezado (header.php)
require '../header.php';

?>
<main>
    <div class="container-fluid px-3">
        <h3 class="mt-2">Nueva categoría</h3>

        <!-- Formulario para ingresar una nueva categoría -->
        <form action="guarda.php" method="post" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>