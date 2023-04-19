<?php

    require "./libs/PHPMailer/Exception.php";
    require "./libs/PHPMailer/OAuth.php";
    require "./libs/PHPMailer/PHPMailer.php";
    require "./libs/PHPMailer/POP3.php";
    require "./libs/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        public function mensagemValida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) { 
                return false;
            }
            return true;
        }
    }

$mensagem = new Mensagem();
$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);

// print_r($mensagem);

if(!$mensagem->mensagemValida()) {
    echo 'Mensagem inválida';
    die();
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                     
    $mail->isSMTP();                                          
    $mail->Host       = 'smtp.gmail.com';                    
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'username@email.com';                // ATENÇÃO 
    $mail->Password   = 'password';                          // ATENÇÃO           
    $mail->SMTPSecure = 'tls';           
    $mail->Port       = 587;                                    

    //Recipients
    $mail->setFrom('from@email.com', 'Remetente');
    $mail->addAddress('to@email.com', 'Destinatário');  
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    

    //Content
    $mail->isHTML(true);                               
    $mail->Subject = 'Oi, eu sou o assunto';
    $mail->Body    = 'Oi, sou conteudo do  <strong>email</strong>';
    $mail->AltBody = 'Oi, sou conteudo do email';

    $mail->send();
    echo 'Email foi enviado com sucesso!';
} catch (Exception $e) {
    echo "Não foi possível enviar a mensagem, tente novamente mais tarde. Detalhes do erro: {$mail->ErrorInfo}";
}

?>