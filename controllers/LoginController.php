<?php
namespace Controllers;

use MVC\Router;
use Model\Admin;

class LoginController {

    public static function login(Router $router)
    {
        $errores = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Admin($_POST);
            $errores = $auth->validar();
            if (empty($errores)) {
                // VERIFICAR SI EXISTE EL USUARIO
                $resultado = $auth->existeUsuario();
                if(!$resultado) {
                    //No existe el usuario
                    $errores = Admin::getErrores();
                }else {
                    // VERIFICAR EL PASSWORD
                    $autenticado = $auth->comprobarPassword($resultado);
                    if($autenticado){
                        //AUTENTICAR AL USUARIO
                        $auth->autenticar();

                    }else {
                        // Pasword incorrecto
                        $errores = Admin::getErrores();
                    }
                    

                    
                }

                
            }
    
            // $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
            // $password = mysqli_real_escape_string($db, $_POST['password']);    
    
        }
        $router->render('auth/login', [
            'errores'=>$errores
        ]);
    }
    public static function logout(Router $router)
    {
        session_start();
        $_SESSION=[];
        header('location: /');
    }
}