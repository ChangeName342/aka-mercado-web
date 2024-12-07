<?php

// Solicitud para consultar los datos de la compra

require '../config/config.php';

/* Verifica si no está establecida la sesión de usuario o si el tipo de usuario no es 'admin'. 
Si no cumple estas condiciones, redirige a index.php y termina la ejecución del script.
*/
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Se obtiene el parámetro 'orden' enviado por POST
$orden = $_POST['orden'] ?? null;

if ($orden == null) {
    exit; // Si no hay orden específica, se detiene la ejecución
}

// Crea una instancia de la clase Database (una clase que maneja la conexión a la base de datos).
$db = new Database();

// Conexión a la  bd
$con = $db->conectar();

// Consulta para obtener los detalles de la compra según la orden especificada
$sqlCompra = $con->prepare("SELECT compra.id, id_transaccion, fecha, total, CONCAT(nombres,' ',apellidos) AS cliente
FROM compra
INNER JOIN clientes ON compra.id_cliente = clientes.id
WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);

if (!$rowCompra) {
    exit;
}

$idCompra = $rowCompra['id'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('d-m-Y H:i');

$sqlDetalle = $con->prepare("SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
$sqlDetalle->execute([$idCompra]);

$html = '<p><strong>Fecha: </strong>' . $fecha . '</p>';
$html .= '<p><strong>Orden: </strong>' . $rowCompra['id_transaccion'] . '</p>';
$html .= '<p><strong>Total: </strong>' . number_format($rowCompra['total'], 2, '.', ',') . '</p>';

$html .= '<table class="table">
<thead>
<tr>
<th>Producto</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Subtotal</th>
</tr>
</thead>';

$html .= '<tbody>';

while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
    $precio = $row['precio'];
    $cantidad = $row['cantidad'];
    $subtotal = $precio * $cantidad;
    $html .= '<tr>';
    $html .= '<td>' . $row['nombre'] . '</td>';
    $html .= '<td>' . number_format($precio, 2, '.', ',') . '</td>';
    $html .= '<td>' . $cantidad . '</td>';
    $html .= '<td>' . number_format($subtotal, 2, '.', ',') . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
