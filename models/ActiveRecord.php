<?php
namespace Model;
use Exception;

class ActiveRecord {
    
    protected static $db;
    protected static $columnasDB = [];

    public $id;

    protected static $tabla='';

    //errores
    protected static $alertas = [];
    
    /**
     * Inicializa los atributos del objeto
     *
     * @param array $arg son los datos que contiene la superglobal POST
     */
    public function __construct($arg = [])
    {

    }

    public static function setDB($database){
        self::$db = $database;
    }

    public function guardar() {
        if(isset($this->id) && $this->id != '') {
            //actualizando           
            return $this->actualizar();
        }else{
            //creando
            return $this->crear();
        }
    }

    public function actualizar() {
        
        $atributos = $this->sanitizarAtributos();

        $cadena = '';

        foreach ($atributos as $key => $value) {
            if($cadena === '') {
                $cadena .= "$key = ?";
                continue;
            }
            $cadena .= ", $key = ?";
        }
        
        $params = str_repeat('s', count($atributos));
        //param s para el id y el valor id extraido del objeto actual
        $params .= 's';
        $atributos['id'] = $this->id;        
        
        $query = "UPDATE " . static::$tabla . " SET $cadena WHERE id = ? LIMIT 1";

        $datos = array_values($atributos); 

        $statement = self::$db->prepare($query);
        $statement->bind_param($params, ...$datos);
        $statement->execute();

        error_log(static::class . '::Actualizar->rows updated ' . $statement->affected_rows);
        if ($statement->affected_rows > 0){
            return ['resultado' => true];
        }
        return ['resultado' => false];
    }   

    /**
     * Esta funcion se usa para insertar datos sanitizados en la tabla propiedades
     * 
     * @return array Luego de ejecutarla sentencia Insert verifica la propiedades affected_rows retornando un arreglo asociativo donde true si se logró insertar además del id
     */
    public function crear() {
        //sanitizar datos
        $atributos = $this->sanitizarAtributos();       

        $campos = join(', ', array_keys($atributos) );
        $placeHolders = (str_repeat('?, ', count($atributos) - 1)) . '?';
        $params = str_repeat('s', count($atributos));

        //TODO: se puede mejorar usando gettype() y asi asignar un placeholder ideal (s ó i)
        $query = "INSERT INTO " . static::$tabla . " ($campos) VALUES ($placeHolders)";
        // error_log($query);
        $datos = array_values($atributos);

        try {
            $statement = self::$db->prepare($query);
            $statement->bind_param($params, ...$datos);
    
            $statement->execute();   
        } catch (Exception $e) {
            error_log('ActiveRecord::Crear->' . $e->getMessage() );
            return [
                    'resultado' => false
                ];
        }
        
        error_log(static::class . '::Crear->rows inserted ' . $statement->affected_rows);
        if ($statement->affected_rows > 0){
            return [
                    'resultado' => true,
                    'id' => self::$db->insert_id
                ];
        }

        return [
            'resultado' => false
        ];
    }

    /**
     * Elimina un registro de la BD
     *
     * @return bool Retorna true si tuvo éxito, false si falló
     */
    public function eliminar(){
        $query = "DELETE FROM " . static::$tabla . " WHERE id = ? LIMIT 1";
        try {
            $statement = self::$db->prepare($query);
            $statement->bind_param('i', $this->id);
    
            $statement->execute();
            
            if ($statement->affected_rows > 0){
                error_log(static::class . "::eliminar->rows deleted " . $statement->affected_rows);
                return true;
            }
            error_log(self::class . '::eliminar -> Falló la eliminación de un registro en la BD');
            
        } catch (Exception $e) {
            
            error_log(self::class . '::eliminar -> Error al eliminar registro: ' . $e->getMessage());
            return false;        
        }
        return false;        
    }

    /**
     * Identifica y une los atributos de la BD
     *
     * @return atributos retorna el arreglo de atributos mapeado con la BD
     */
    public function atributos() {
        $atributos = [];
        
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
                // $atributos[$columna] = $this->$columna ?? '';
                $atributos[$columna] = $this->$columna;
        }   

        return $atributos;
    }

    /**
     * Sanitiza los valores contenidos en el atributo[]
     *
     * @return array $sanitizado contiene los valores pasados por la funcion real_escape
     */
    public function sanitizarAtributos () {
        //FIXME: al sanitizar convierte los saltos de linea en /n
        $atributos = $this->atributos();        
        $sanitizado = [];

        foreach($atributos as $key=>$value) {
            //se cambió la linea $sanitizado[$key] = self::$db->real_escape_string($value ?? '')
            //por estas debido a que cambiaba los null por cadenas vacías
            if(!is_null($value)) {
                $sanitizado[$key] = self::$db->real_escape_string($value);
            }else{
                $sanitizado[$key] = null;
            }
        }
        
        return $sanitizado;
    }

    /**
     * Retorna el array de errores
     *
     * @return $alertas Almacena todos los errores en la validación
     * si está vacío es porque no hay errores
     */
    public static function getAlertas() {
        return static::$alertas;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    public function validar() {
        static::$alertas = []; //limpia errores anteriores 
        return static::$alertas;
    }

    public static function all() : array{
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla .  " WHERE id = $id";
        $resultado = self::consultarSQL($query);
       
        //como se espera solo 1 registro entonces usarmos array:shift        
        return array_shift($resultado);
    }

    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla .  " WHERE " . $columna . " = " . $valor . " LIMIT 1";

        $resultado = self::consultarSQL($query);
        //como se espera solo 1 registro entonces usarmos array:shift        
        return array_shift($resultado);
    }

    /**
     * Consulta plana de SQL: Usalo cuando los métodos del modelo no son suficiente
     *
     * @param string $query
     * @return array
     */
    public static function SQL(string $query) : array {
        $resultado = self::consultarSQL($query);
        //como se espera solo 1 registro entonces usarmos array:shift        
        return $resultado;
    }


    public static function consultarSQL($query) : array {
        //consultar BD
        $resultado = self::$db->query($query);
        //iterar
        $array = [];

        while($registro = $resultado->fetch_assoc() ){
            $array[] = static::crearObjeto($registro);
        }        

        //liberar la memoria
        $resultado->free();

        //retornar resultados
        return $array;
    }

    /**
     * Retorna un límite de registros especificados en el argumento $cantidad
     *
     * @param int $cantidad Limite max de registros a retornar
     * @return $resultado array de objetos conteniendo la información solicitada
     */
    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla .  " LIMIT " . $cantidad;
        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach ($registro as $llave=>$valor) {
            if(property_exists($objeto, $llave)){
                $objeto->$llave = $valor;
            }

        }        
        return $objeto;
    }

    //sincroniza el objeto con los datos actualizados por el usuario
    public function sincronizar($arg) {

        foreach($arg as $key=>$value){
            if(property_exists($this, $key)&& !is_null($value)){
                $this->$key = $value;
            }
        }
    }

}