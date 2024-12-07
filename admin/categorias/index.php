<?php

// Mostrar listado de categorias

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

// Prepara una consulta SQL para seleccionar todas las categorías activas.
$sql = "SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);

// Obtiene todas las categorías como un array asociativo.
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Incluye el archivo de encabezado (header.php)
require '../header.php';

?>
<main>
    <div class="container-fluid px-3">
        <h3 class="mt-2">Categorías</h3>

        <!-- Enlace para agregar nueva categoría -->
        <a class="btn btn-primary" href="nuevo.php">Agregar</a>

        <hr>

        <!-- Tabla para mostrar las categorías -->
        <table id="datatablesSimple" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th style="width: 5%" data-sortable="false"></th>
                    <th style="width: 5%" data-sortable="false"></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($categorias as $categoria) { ?>
                    <tr>
                        <!-- Muestra el ID y nombre de cada categoría -->
                        <td><?php echo $categoria['id']; ?></td>
                        <td><?php echo $categoria['nombre']; ?></td>
                        <!-- Botón de editar que redirige a edita.php con el ID de la categoría como parámetro -->
                        <td>
                            <a class="btn btn-warning btn-sm" href="edita.php?id=<?php echo $categoria['id']; ?>">
                                <i class="fas fa-pen"></i> Editar
                            </a>
                        </td>
                        <!-- Botón de eliminar que abre un modal para confirmar la acción -->
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-id="<?php echo $categoria['id']; ?>">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal para confirmar eliminación de categoría -->
<div class="modal fade" id="eliminaModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Alerta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar este registro?
            </div>
            <div class="modal-footer">
                <!-- Formulario para enviar la solicitud de eliminación a elimina.php -->
                <form action="elimina.php" method="post">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Elimina</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para manejar el ID de la categoría en el modal de eliminación -->
<script>
    let eliminaModal = document.getElementById('eliminaModal')
    eliminaModal.addEventListener('show.bs.modal', function(event) {

        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let modalInputId = eliminaModal.querySelector('.modal-footer input')
        modalInputId.value = id
    })
</script>

<?php require '../footer.php'; ?>