<?php
namespace Model;

class Usuario extends ActiveRecord {
    //base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = 
                ['id',
                'nombre',
                'apellido',
                'email',
                'password',
                'telefono',
                'admin',
                'confirmado',
                'token'];
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'EL nombre es obligatorio';
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = 'EL apellido es obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'EL email es obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'EL password es obligatorio';
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function existeUsuario() :bool {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1;";
        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya existe';
            return true;
        }
        
        return false;
    }

    function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordYConfirmacion($password){

        $verificarPwd = password_verify($password, $this->password);

        if(!$this->confirmado || !$verificarPwd) {
            self::$alertas['error'][] = 'Password incorrecto o tu cuenta no ha sido confirmada';
            return false;
        }
        
        return true;
        
    }

    public function validarLogin() {
        if(!$this->email){
            self::$alertas['error'][] = 'Ingrese un email válido';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'Ingrese un password válido';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email){
            self::$alertas['error'][] = 'Ingrese un email válido';
        }

        return self::$alertas;
    }

    public function validarPassword() : bool {
        if(!$this->password){
            self::$alertas['error'][] = 'Ingrese una nueva constraseña';
            return false;
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La contraseña debe contener un mínimo de 6 caracteres';
            return false;
        }

        return true;
    }
}
