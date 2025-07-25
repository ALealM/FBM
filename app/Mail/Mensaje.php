<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Mensaje extends Mailable
{
    use Queueable, SerializesModels;

    public $aDatos = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($aDatos)
    {
        $this->aDatos = $aDatos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      return $this->markdown('emails.mensaje')->subject( (@$this->aDatos['sAsunto'] != null ? $this->aDatos['sAsunto'] : 'Mensaje del sistema FBM') )->with('aDatos',$this->aDatos);
    }
}
