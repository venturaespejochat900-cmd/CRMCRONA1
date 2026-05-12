<?php

namespace App\Mail;

use App\Http\Controllers\PedidoController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EnviarFicha extends Mailable
{
    use Queueable, SerializesModels;

    public $pedidoCorreo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pedidoCorreo)
    {
        $this->pedidoCorreo = $pedidoCorreo;      
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {      
        return $this->subject('Ficha Técnica')->view('mail.ficha');
        //$correo = self::enviarEmail($this->pedidoCorreo);
        //return $correo;
        //var_dump($this->pedidoCorreo);
    }

    public static function enviarEmail($ficha){
        
        $email_correcto = false; 
        
        $message = '<p>'.strtoupper('Ficha Técnica').'</p>          
          <p>Fecha: '.date('d-m-Y').'</p>';

        $cssToInlineStyles = new CssToInlineStyles();         

        $cuerpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">        
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>'.htmlspecialchars('Ficha Técnica').'</title>
            <script src="https://kit.fontawesome.com/2a20cc777c.js" crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
                                              
            
        </head>
        <body>
            '.$message.'
        </body>
        </html>';

         $cssToInlineStyles->convert(
          $cuerpo
        );
            
        $transport = (new \Swift_SmtpTransport('smtp.venturaespejo.com', 587))
            ->setUsername('conectormagento@venturaespejo.com')
            ->setPassword('749_fltmv');

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $correo = (new \Swift_Message('Ficha Técnica'))
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setFrom(['conectormagento@venturaespejo.com' => 'Ventura espejo'])
            ->setTo([$ficha['correo']]);
        
        

        $correo->setBody($cssToInlineStyles);
        

        // Send the message
        $result = $mailer->send($correo);
        if($result == true) {
            $email_correcto = true;
        }

        return $email_correcto;

    }
}
