<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    private $mail;

    // public $email;
    // public $nombre;

    public function __construct() {
        //crear el objeto email
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['EMAIL_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Port = $_ENV['EMAIL_PORT'];
        $this->mail->Username = $_ENV['EMAIL_USER'];
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Password = $_ENV['EMAIL_PASS'];        
    }
    
    public function enviarConfirmacion($nombre, $email, $token) {
        $this->mail->setFrom('cuentas@appsalon.com');
        $this->mail->addAddress($email, $nombre);
        $this->mail->Subject = 'Confirma tu cuenta';

        $this->mail->isHTML();
        $this->mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $nombre . '</strong> Has creado tu cuenta en App Salón, solo debes confirmarla presionando el siguiente enlace</p>';
        $contenido .= "<p>Presiona aquí: <a href=" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $token . "'>Confirmar cuenta</a></p>";        
        $contenido .= "<p>Si no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $this->mail->Body = $contenido;

        //enviar email
        if($this->mail->send()){
            error_log('Email::enviarConfirmacion-> se envio email de confirmación del usuario ' . $email);
        }else{
            error_log('Email::enviarConfirmacion-> NO se pudo enviar email de confirmación del usuario ' . $email);
        }
    }

    public function enviarBienvenida($nombre, $email){
        $this->mail->setFrom('cuentas@appsalon.com');
        $this->mail->addAddress($email, $nombre);
        $this->mail->Subject = 'Bienvenido a AppSalon';

        $this->mail->isHTML();
        $this->mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Bienvenido ' . $nombre . '</strong> a AppSalón donde podrás agendar tus citas con nuestros expertos en la belleza masculina</p>';
        $contenido .= "<p>Ingresa hoy mismo y agenda tu primera cita: <a href=" . $_ENV['APP_URL'] . "</a></p>";        
        $contenido .= "</html>";

        $this->mail->Body = $contenido;

        //enviar email
        if($this->mail->send()){
            error_log('Email::enviarBienvenida-> se envio email de bienvenida a ' . $email);
            return true;
        }else{
            error_log('Email::enviarBienvenida-> NO se pudo enviar email de bievenida a ' . $email);
            return false;
        }        
    }

    public function enviarInstrucciones($nombre, $email, $token) {
        $this->mail->setFrom('cuentas@appsalon.com');
        $this->mail->addAddress($email, $nombre);
        $this->mail->Subject = 'Reestablecer contraseña';

        $this->mail->isHTML();
        $this->mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $nombre . '</strong>, has solicitado reestablecer tu contraseña en App Salón. Da click en el siguiente enlace para hacerlo.</p>';
        $contenido .= "<p>Haz click aquí: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $token . "'>Reestablecer constraseña</a></p>";        
        $contenido .= "<p>Si no solicitaste esta cuenta, puedes ignorar el mensaje</p>";        

        $contenido .= '</html>';

        $this->mail->Body = $contenido;

        //enviar email
        if($this->mail->send()){
            error_log('Email::enviarInstrucciones-> se envio email con instrucciones a ' . $email);
        }else{
            error_log('Email::enviarInstrucciones-> NO se pudo enviar email con instrucciones a ' . $email);
            return false;
        }       

        return true;
    }
}
