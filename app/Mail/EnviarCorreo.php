<?php

namespace App\Mail;

use App\Http\Controllers\PedidoController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EnviarCorreo extends Mailable
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
        return $this->subject('Pedido Web')->view('mail.pedido');
    }

    public static function enviarEmail($pedido){
        $email_correcto = false; 
        
        $message = '<p>'.strtoupper('Pedido Web').'</p>          
          <p>Fecha: '.$pedido['cabecera'][0]->FechaPedido.'</p>';

        $cssToInlineStyles = new CssToInlineStyles();       
        $css = file_get_contents(__DIR__ . './../../public/css/bootstrap.min.css');  
        $css2 = file_get_contents(__DIR__ . './../../public/css/pedidos/bootstrap/dist/css/bootstrap.min.css');  
        $css3 = file_get_contents(__DIR__ . './../../public/css/pedidos/propios.css');  

        $cuerpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">        
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>'.htmlspecialchars('Pedido Web').'</title>
            <script src="https://kit.fontawesome.com/2a20cc777c.js" crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
            <link rel="stylesheet" href="../../public/css/pedidos/bootstrap/dist/css/bootstrap.min.css">                                   
            <style type="text/css">
              '.$css.$css2.$css3.'
              .pop-up {
                margin-left: 1%;
                max-height: 350px;
                background-color: rgba(48, 48, 48, 0.1);
                overflow-y: scroll;
                cursor: pointer;
              }
          
              #close {
                  color: red;
                  cursor: pointer;
              }
          
              .info {
                  color: #0a53be;
              }
          
              .invoice {
                  position: relative;
                  background-color: #FFF;
                  min-height: 680px;
                  padding: 15px
              }
          
              .invoice header {
                  padding: 10px 0;
                  margin-bottom: 20px;
                  border-bottom: 1px solid #3989c6
              }
          
              .invoice .company-details {
                  text-align: right
              }
          
              .invoice .company-details .name {
                  margin-top: 0;
                  margin-bottom: 0
              }
          
              .invoice .contacts {
                  margin-bottom: 20px
              }
          
              .invoice .invoice-to {
                  text-align: left
              }
          
              .invoice .invoice-to .to {
                  margin-top: 0;
                  margin-bottom: 0
              }
          
              .invoice .invoice-details {
                  text-align: right
              }
          
              .invoice .invoice-details .invoice-id {
                  margin-top: 0;
                  color: #3989c6
              }
          
              .invoice main {
                  padding-bottom: 50px
              }
          
              .invoice main .thanks {
                  margin-top: -100px;
                  font-size: 2em;
                  margin-bottom: 50px
              }
          
              .invoice main .notices {
                  padding-left: 6px;
                  border-left: 6px solid #3989c6
              }
          
              .invoice main .notices .notice {
                  font-size: 1.2em
              }
          
              .invoice table {
                  width: 100%;
                  border-collapse: collapse;
                  border-spacing: 0;
                  margin-bottom: 20px
              }
          
              .invoice table td,
              .invoice table th {
                  padding: 15px;
                  background: #eee;
                  border-bottom: 1px solid #fff
              }
          
              .invoice table th {
                  white-space: nowrap;
                  font-weight: 400;
                  font-size: 16px
              }
          
              .invoice table td h3 {
                  margin: 0;
                  font-weight: 400;
                  color: #3989c6;
                  font-size: 1.2em
              }
          
              .invoice table .qty,
              .invoice table .total,
              .invoice table .unit {
                  text-align: right;
                  font-size: 1.2em
              }
          
              .invoice table .no {
                  color: #fff;
                  font-size: 1.6em;
                  background: #3989c6
              }
          
              .invoice table .unit {
                  background: #ddd
              }
          
              .invoice table .total {
                  background: #3989c6;
                  color: #fff
              }
          
              .invoice table tbody tr:last-child td {
                  border: none
              }
          
              .invoice table tfoot td {
                  background: 0 0;
                  border-bottom: none;
                  white-space: nowrap;
                  text-align: right;
                  padding: 10px 20px;
                  font-size: 1.2em;
                  border-top: 1px solid #aaa
              }
          
              .invoice table tfoot tr:first-child td {
                  border-top: none
              }
          
              .invoice table tfoot tr:last-child td {
                  color: #3989c6;
                  font-size: 1.4em;
                  border-top: 1px solid #3989c6
              }
          
              .invoice table tfoot tr td:first-child {
                  border: none
              }
          
              .invoice footer {
                  width: 100%;
                  text-align: center;
                  color: #777;
                  border-top: 1px solid #aaa;
                  padding: 8px 0
              }
          
              .alfondo {
                  position: relative;
                  bottom: 0px;
              }

            </style>
        </head>
        <body>
            '.$message.$pedido['cuerpo'].'
        </body>
        </html>';

         $cssToInlineStyles->convert(
          $cuerpo,
          $css,
          $css2,
          $css3,
        );
            
        $transport = (new \Swift_SmtpTransport('smtp.venturaespejo.com', 587))
            ->setUsername('conectormagento@venturaespejo.com')
            ->setPassword('749_fltmv');

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $correo = (new \Swift_Message('Pedido Web'))
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setFrom(['conectormagento@venturaespejo.com' => 'DRD'])
            ->setTo([$pedido['correos']]);
        
        

        $correo->setBody($cssToInlineStyles);
        

        // Send the message
        $result = $mailer->send($correo);
        if($result == true) {
            $email_correcto = true;
        }

        return $email_correcto;

    }
}
