<?php

namespace Model;

class ActiveRecord
{
    //Base de datos
    protected static $db;
    protected static $columnasDB = [];

    //Nombre de Tabla
    protected static $tabla = '';

    //Errores
    protected static $errores = [];

    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function guardar()
    {
        if (!is_null($this->id)) {
            // ACTUALIZAR
            $this->actualizar();
        } else {
            // CREAR
            $this->crear();
        }
    }

    public function crear()
    {
        //Sanitizar Datos
        $atributos = $this->sanitizarAtributos();

        // Insertar a base de datos 
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos)); //array_keys Obtiene los key del array y join los hace un string separado por ', ' Ej. 'titulo, precio, iamgen ...'
        $query .= " ) VALUES ('";
        $query .= join("', '", array_values($atributos)); //lo mismo pero con los valores
        $query .= "')";
        $resultado = self::$db->query($query);
        //MENSAJE DE EXITO
        if ($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=1'); // no funciona si hay html antes de esta funcion
        }
    }

    public function actualizar()
    {
        //Sanitizar Datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(", ", $valores);
        $query .= " WHERE id='" . self::$db->escape_string($this->id) . "' ";
        $query .= "LIMIT 1";
        $resultado = self::$db->query($query);
        if ($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=2'); // no funciona si hay html antes de esta funcion
        }
    }

    //ELIMINAR UN REGISTRO
    public function eliminar()
    {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if ($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    //Identificar y Unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna == 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    //Subida de imagen
    public function setImagen($imagen)
    {
        //Elimina imagen anterior
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }
        //Asignar al atributo imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    //ELIMINA ARCHIVO
    public function borrarImagen()
    {
        //comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen); // elimina imagen
        }
    }

    // Validaciones
    public static function getErrores()
    {
        return static::$errores;
    }

    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }

    //LISTA TODAS LOS REGISTROS
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla; // static busca el atributo en la clase que se esta heredando 
        return self::consultarSQL($query);
    }

    // OBTIENE DETERMINADO NÚMERO DE REGISTROS
    public static function get($cantidad)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad; // static busca el atributo en la clase que se esta heredando 
        return self::consultarSQL($query);
    }

    // BUSCA UNA REGISTRO POR SU ID
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    public static function consultarSQL($query)
    {
        //Consultar base de datos
        $resultado = self::$db->query($query);

        //iterar resultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        //Liberar la memoria
        $resultado->free();

        //Retornar resultados
        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;
        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) { // sese objeto tiene esa llave
                $objeto->$key =  $value;
            }
        }
        return $objeto;
    }

    //SINCRONIZA EL OBJETO EN MEMORIA CON LOS CAMBIOS REALIZADOS POR EL USUARIO
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
