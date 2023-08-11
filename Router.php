<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fun)
    {
        $this->rutasGET[$url] = $fun;
    }
    public function post($url, $fun)
    {
        $this->rutasPOST[$url] = $fun;
    }
    
    public function comprobarRutas()
    {
        session_start();
        $auth = $_SESSION['login'] ?? false;
        //Arreglos de rutas Protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        // $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $urlActual = strtok($_SERVER['PATH_INFO']) ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];
        if ($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        }else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // PROTEGER RUTAS
        if (in_array($urlActual, $rutas_protegidas) && !$auth) {
            header('Location: /');
        }


        if ($fn) {
            //LA URL EXISTE Y HAY UNA FUNCION ASOCIADA;
            // debuguear($this);
            call_user_func($fn, $this); // Manda a llamar a la funcion fn y manda e this como argumento a esa funcion
        }else {
            echo 'Pagina No Encontrada';
        }
    }

    //MUESTRA UNA VITA
    public function render($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }
        ob_start();//Inicia alamacenamiento en memoria durante un momento
        include __DIR__ . "/views/$view.php";
        // En contenido se almacena todo lo que esta en memoria desde ob_start() hasta ob_get_clean() que en este caso es esta vista include __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // ob_get_clean() Limpia memoria, el buffer
        include __DIR__ . "/views/layout.php";
    }
}