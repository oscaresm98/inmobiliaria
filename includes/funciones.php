<?php

define ('TEMPLATE_URL', __DIR__ . '/templates');
define ('FUNCIONES_URL', __DIR__ . 'funciones.php');
define ('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/');

// Crear una carpeta
$carpetaImagenes = '../../imagenes/';
if(!is_dir($carpetaImagenes)) {
    mkdir($carpetaImagenes);
}

function incluirTemplate ( string $nombre , bool $inicio = false) {
    include TEMPLATE_URL . "/{$nombre}.php";
}

function estaAutenticado () {
    session_start();
    if(!$_SESSION['login']) {
        header('location: /bienesraicesPOO/');
    }
}

function debuguear($instancia) {
    echo '<pre>';
    var_dump($instancia);
    echo '</pre>';
    exit;
}

//ESCAPA / Satinizar EL HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//VALIDAR TIPO DE CONTENIDO
function validarTipoContenido($tipo)
{
    $tipos = ['propiedad', 'vendedor'];
    return in_array($tipo, $tipos);
}

//MUESTRA LOS MENSAJES
function mostrarNotificacion($codigo)
{
    $mensaje = '';
    switch ($codigo) {
        case 1:
            $mensaje = 'Creado Correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado Correctamente';
            break;
        default:
            $mensaje = false;
            break;
    }
    return $mensaje;
}

//Validar id
function validarORedireccionar(string $url)
{
    // validar por id valido
    $id =  $_GET['id'];
    $id =  filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header("Location: {$url}");
    }
    return $id;
}