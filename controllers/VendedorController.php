<?php

namespace Controllers;

use Model\Vendedor;
use MVC\Router;

class VendedorController {

    public static function crear(Router $router)
    {
        $vendedor = new Vendedor;
        $errores = Vendedor::getErrores();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Crea una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']);
    
            //VALIDAR
            $errores = $vendedor->validar();
    
            // Revisar que el arreglo este vacio
            if(empty($errores)){
                $vendedor->guardar();
            }
        }
        $router->render('vendedores/crear', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router)
    {
        $id = validarORedireccionar("/admin");
        // Obtener los datos del vendedor
        $vendedor = Vendedor::find($id);
        // Arreglo con mensajes de errores
        $errores = Vendedor::getErrores();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Asigna atriutos
            $args = $_POST['vendedor'];
            $vendedor->sincronizar($args);
            //VALIDAR
            $errores = $vendedor->validar();
            if(empty($errores)){
                // Insertar a base de datos 
                $vendedor->guardar();
            }
        }
        $router->render('vendedores/actualizar', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function eliminar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){            // Validar id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if ($id) {
                $tipo = $_POST['tipo'];
                if (validarTipoContenido($tipo)) {
                    $vendeddor = Vendedor::find($id);
                    $vendeddor->eliminar();
                }
            }  
        }
    }

}