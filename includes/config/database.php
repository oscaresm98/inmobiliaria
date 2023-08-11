<?php

function conectarDB() : mysqli {
    $db = new mysqli('localhost', 'root', 'root', 'bienesraices_crud');
    if(!$db) {
        echo "Error en la conexion";
        exit;
    }
    return $db;
}