<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
require ROOT_DIR .'library/PHPMailer/PHPMailer.php';
require ROOT_DIR .'library/PHPMailer/SMTP.php';
require ROOT_DIR .'library/PHPMailer/Exception.php';

class Mailer {
    private $mailer;

    public function __construct($to, $subject = 'Nuevo Pedido') {
        $this->mailer = new PHPMailer(true);
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = 'smtp.gmail.com';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'mipymessalesmanager@gmail.com'; // tu gmail
            $this->mailer->Password = 'lyxj jall tsiu ityv'; // generada en Gmail
            $this->mailer->SMTPSecure = 'tls';
            $this->mailer->Port = 587;

            $this->mailer->setFrom('mipymessalesmanager@gmail.com', 'LosEspartanos');
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
        } catch (Exception $e) {
            throw new Exception("Error al configurar el correo: " . $e->getMessage());
        }
    }

    public function enviarPedido($htmlContenido) {
        $this->mailer->Body = $htmlContenido;
        return $this->mailer->send();
    }
}
?>
