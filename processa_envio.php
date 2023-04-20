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
        public $status = array('codigo_status' => null, 'descricao_status' => '');

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

/* print_r($mensagem);

    if(!$mensagem->mensagemValida()) {
        echo 'Mensagem inválida'; 
} */
 
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug  = false;                                     
    $mail->isSMTP();                                               
    $mail->Host       = 'smtp.gmail.com';                    
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'username';                             // ATENÇÃO 
    $mail->Password   = 'password';                             // ATENÇÃO           
    $mail->SMTPSecure = 'tls';           
    $mail->Port       = 587;                                    

    //Recipients
    $mail->setFrom('from@email.com', 'Remetente');
    $mail->addAddress($mensagem->__get('para'));  
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    

    //Content
    $mail->isHTML(true);                               
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessário utilizar um client que suporte HTML.';

    $mail->send();

    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'Email enviado com sucesso!';

} catch (Exception $e) {

    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'Não foi possível enviar a mensagem, tente novamente mais tarde. Detalhes sobre o erro:' . $mail->ErrorInfo;
}


?>

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Send Mail App</title>
</head>
<body>
    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
            <h2>Send Mail</h2>
            <p class="lead">Seu app de envio de e-mails particular!</p>
        </div>
        <div class="row">
        <div class="row">
    <div class="col-md-12"></div>

        <?php if($mensagem->status['codigo_status'] == 1){ ?>
            <div class="container">
                <h1 class="display-4 text-success">Sucessada</h1>
                <p><?php echo $mensagem->status['descricao_status'] ?></p>
                <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Retornar</a>
            </div>
        <?php } ?>

        <?php if($mensagem->status['codigo_status'] == 2){ ?>
            <div class="container">
                <h1 class="display-4 text-danger">Deu ruim</h1>
                <p><?php echo($mensagem->status['descricao_status']) ?></p>
                <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Tente Novamente</a>
            </div>
        <?php } ?>
</div>
        </div>
    </div>
</body>
</html>