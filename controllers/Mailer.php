<?php
class Mailer {
    private $to;
    private $subject;
    private $headers;

    public function __construct($to, $subject = 'Nuevo Pedido') {
        $this->to = $to;
        $this->subject = $subject;
        $this->headers  = "MIME-Version: 1.0" . "\r\n";
        $this->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $this->headers .= "From: mipymessales@gmail.com\r\n"; // Cambia esto por un correo válido
    }

    public function enviarPedido($htmlContenido) {
        return mail($this->to, $this->subject, $htmlContenido, $this->headers);
    }
}
?>