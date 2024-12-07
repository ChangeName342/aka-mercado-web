<?php

// Funciones para el admin

// Verifica si alguno de los parámetros está vacío o contiene solo espacios en blanco
function esNulo($parametos)
{
    foreach ($parametos as $parameto) {
        if (strlen(trim($parameto)) < 1) {
            return true; // Retorna true si encuentra algún parámetro nulo
        }
    }
    return false; // Retorna false si todos los parámetros son válidos
}

// Valida si dos contraseñas son idénticas
function validaPassword($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true; // Retorna true si las contraseñas son iguales
    }
    return false; // Retorna false si las contraseñas son diferentes
}

// Verifica si existe un usuario con el nombre proporcionado en la base de datos
function usuarioExiste($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0) {
        return true; // Retorna true si encuentra un usuario con ese nombre
    }
    return false; // Retorna false si no encuentra ningún usuario
}

// Verifica si existe un cliente con el correo electrónico proporcionado en la base de datos
function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0) {
        return true; // Retorna true si encuentra un cliente con ese correo electrónico
    }
    return false; // Retorna false si no encuentra ningún cliente
}

// Muestra mensajes de alerta basados en un array de errores
function mostrarMensajes($errors = [])
{
    if (!empty($errors)) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>'; // Imprime cada error como un elemento de lista
        }
        echo '<ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

// Valida un token específico para activar la cuenta de un usuario
function validaToken($id, $token, $con)
{
    $msg = "";
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token LIKE ? LIMIT 1");
    $sql->execute([$id, $token]);
    if ($sql->fetchColumn() > 0) {
        if (activarUsuario($id, $con)) {
            $msg = "Cuenta activada."; // Mensaje si la cuenta se activa correctamente
        } else {
            $msg = "Error al activar cuenta."; // Mensaje si hay un error al activar la cuenta
        }
    } else {
        $msg = "No existe el registro del cliente."; // Mensaje si no se encuentra el usuario con el token proporcionado
    }
    return $msg; // Retorna el mensaje 
}

// Activa la cuenta de un usuario en la base de datos
function activarUsuario($id, $con)
{
    $sql = $con->prepare("UPDATE usuarios SET activacion = 1, token = '' WHERE id = ?");
    return $sql->execute([$id]); // Retorna true si la actualización es exitosa, false si hay algún error
}

// Verifica las credenciales de inicio de sesión de un administrador
function login($usuario, $password, $con)
{
    $sql = $con->prepare("SELECT id, usuario, password, nombre FROM admin WHERE usuario LIKE ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            $_SESSION['user_type'] = 'admin';
            header("Location: inicio.php"); // Redirige al usuario a la página de inicio si las credenciales son correctas
            exit;
        }
    }
    return 'El usuario y/o contraseña son incorrectos'; // Mensaje de error si las credenciales son incorrectas
}

// Genera un token único y actualiza el campo token_password y password_request para solicitar cambio de contraseña
function solicitaPassword($userId, $con)
{
    $token = generarToken(); // Función para generar un token único

    $sql = $con->prepare("UPDATE usuarios SET token_password=?, password_request=1 WHERE id = ?");
    if ($sql->execute([$token, $userId])) {
        return $token; // Retorna el token generado si la actualización es exitosa
    } 
    return null; // Retorna null si hay algún error
}

// Verifica si un token específico para solicitud de cambio de contraseña es válido
function verificaTokenRequest($userId, $token, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token_password LIKE ? AND password_request=1 LIMIT 1");
    $sql->execute([$userId, $token]);
    if ($sql->fetchColumn() > 0) {
        return true; // Retorna true si el token es válido para la solicitud de cambio de contraseña
    }
    return false; // Retorna false si el token no es válido
}

// Actualiza la contraseña de un usuario en la base de datos
function actualizaPassword($userId, $password, $con)
{
    $sql = $con->prepare("UPDATE usuarios SET password=?, token_password = '', password_request = 0 WHERE id = ?");
    if ($sql->execute([$password, $userId])) {
        return true; // Retorna true si la actualización de la contraseña es exitosa
    }
    return false; // Retorna false si hay algún error al actualizar la contraseña
}

// Actualiza la contraseña de un administrador en la base de datos
function actualizaPasswordAdmin($userId, $password, $con)
{
    $sql = $con->prepare("UPDATE admin SET password=?, token_password = '', password_request = 0 WHERE id = ?");
    if ($sql->execute([$password, $userId])) {
        return true; // Retorna true si la actualización de la contraseña del administrador es exitosa
    }
    return false; // Retorna false si hay algún error al actualizar la contraseña del administrador
}

// Crea una URL amigable a partir de una cadena de texto
function crearTituloURL($cadena) {
    // Convertir la cadena a minúsculas y reemplazar caracteres especiales y espacios con guiones
    $url = strtolower($cadena);
    $url = preg_replace('/[^a-z0-9\-]/', '-', $url);
    $url = preg_replace('/-+/', "-", $url); // Reemplazar múltiples guiones con uno solo
    $url = trim($url, '-'); // Eliminar guiones al principio y al final
    
    return $url; // Retorna la URL amigable generada
}
