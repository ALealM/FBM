<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotifica extends Mailable
{
    use Queueable, SerializesModels;

    public $info, $meses, $dias;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $info )
    {
        $this->info = $info;
        $this->dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        $this->meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.enviar')
        ->from('no-reply@informediario.info', 'Atención Ciudadana de SLP')
        ->subject('Notificación de la Atención Ciudadana del Estado');
    }
}


// https://code.tutsplus.com/es/tutorials/how-to-send-emails-in-laravel--cms-30046

/*
        ERROR
    stream_socket_enable_crypto(): Peer certificate CN=`vps.informediario.info' did not match expected CN=`smtp.gmail.com'
    https://pcx3.com/cp/peer-certificate-did-not-match-expected-laravel-error-on-cpanel/
*/