<?php
namespace Model;

class Servicio extends ActiveRecord 
{
    //BD
    protected static $tabla = 'servicios';
    protected static $columnasDB = [
        'id',
        'nombre',
        'precio'
    ];
    public $id, $nombre, $precio;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar() {
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        if(!$this->precio){
            self::$alertas['error'][] = 'Debe ingresar un precio';
        }

        if(!is_numeric($this->precio)){
            self::$alertas['error'][] = 'El precio contiene un valor no válido';
        }

        return self::$alertas;
    }
}
