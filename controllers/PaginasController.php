<?php
namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {

    public static function index(Router $router)
    {
        $propiedades = Propiedad::get(3);
        $router->render('paginas/index', [
            'inicio' => true,
            'propiedades' => $propiedades,

        ]);
    }

    public static function nosotros(Router $router)
    {
        $router->render('paginas/nosotros');
    }

    public static function propiedades(Router $router)
    {
        $propiedades = Propiedad::all();
        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades,
        ]);
    }

    public static function propiedad(Router $router)
    {
        $id = validarORedireccionar('/');
        $propiedad = Propiedad::find($id);
        if(!$propiedad) {
            header('location: /');
        }        
        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad 

        ]);
    }

    public static function blog(Router $router)
    {
        $router->render('paginas/blog');
    }

    public static function entrada(Router $router)
    {
        $router->render('paginas/entrada');
    }
    public static function contacto(Router $router)
    {
        $mensaje = null;
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $respuestas = $_POST['contacto'];
            
            // Crear una instancia de PHPMAILER
            $mail = new PHPMailer();

            // Configuarar SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
            $mail->SMTPSecure = 'tls'; // Seguridad en la capa de transporte

            // Configurar contenido del email
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', ' BienesRaices.com');     //Add a recipient
            $mail->Subject = 'Tienes un nuevo mensaje';

            //Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            //Definir el contenido
            $contenido = '<html>';
            $contenido .= '<p>Tienes un nuevo de mensaje!</p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . '</p>';
            // Enviar de manera condicional algunos campos de email o telefono
            if ($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Eligió ser contactado por teléfono:</p>';
                $contenido .= '<p>Teléfono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p>Fecha Contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p>Hora Contacto: ' . $respuestas['hora'] . '</p>';
            } else {
                // Es email, entonces agregamos campos de email
                $contenido .= '<p>Eligió ser contactado por email:</p>';
                $contenido .= '<p>Email: ' . $respuestas['email'] . '</p>';
            }


            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p>Vende o Compra: ' . $respuestas['tipo'] . '</p>';
            $contenido .= '<p>Precio o Presupuesto: $' . $respuestas['precio'] . '</p>';
            $contenido .= '</html>';


            $mail->Body    = $contenido;
            $mail->AltBody = 'Esto es texto alternanivo sin html';

            if ($mail->send()) {
                $mensaje = 'Mensaje Enviado Correctamente';
            } else {
                $mensaje = 'El mesaje no se pudo enviar';
            }
            
        }
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }

}