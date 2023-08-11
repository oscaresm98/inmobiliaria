<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController
{

    public static function index(Router $router)
    {
        $resultado = $_GET['resultado'] ?? null; // si no existe le asigna null
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }

    public static function crear(Router $router)
    {
        $propiedad = new Propiedad();
        $vendedores =  Vendedor::all();
        // Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Crea una nueva instancia
            $propiedad = new Propiedad($_POST['propiedad']);

            // Generar un nobre unico para imagenen
            $nombreImagen =  md5(uniqid(rand(), true)) . ".jpg";
            //Setea la imagen
            // Realiza un resize a la imagen con intervation
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            //VALIDAR
            $errores = $propiedad->validar();

            // Revisar que el arreglo este vacio
            if (empty($errores)) {

                //CREA CARPETA IMAGENES
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }
                //GUARDAR IMAGEN EN EL SERVIDOR
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                //GUARDAR EN  BASE DE DATOS
                $propiedad->guardar();
            }
        }
        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores,
        ]);
    }


    public static function actualizar(Router $router)
    {
        $id = validarORedireccionar("/admin");
        $propiedad = Propiedad::find($id);
        $vendedores =  Vendedor::all();
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Asigna atriutos
            $args = $_POST['propiedad'];
            $propiedad->sincronizar($args);
            
            //VALIDAR
            $errores = $propiedad->validar();
    
            // SUBIDA DE ARCHIVOS
            // Generar un nobre unico para imagenen
            $nombreImagen =  md5(uniqid(rand(), true)) . ".jpg";
            //Setea la imagen
            // Realiza un resize a la imagen con intervation
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }
            // Revisar que el arreglo este vacio
            if(empty($errores)){
                //GUARDAR IMAGEN EN EL SERVIDOR
                if($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                // Insertar a base de datos 
                $propiedad->guardar();
            }
        }

        $router->render('propiedades/actualizar', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores,
        ]);
    }

    public static function eliminar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Validar id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if ($id) {
                $tipo = $_POST['tipo'];
                if (validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }  
            }  
        }

    }
}
