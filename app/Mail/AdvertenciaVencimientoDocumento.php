<?php

namespace ABASTV2\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class AdvertenciaVencimientoDocumento extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $nombre, $apellido, $documento)
    {
        $this->content = $content;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->documento = $documento;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->markdown('emails.vencimientodocumento15')->with('content',$this->content)->with('nombre', $this->nombre)->with('apellido', $this->apellido)->with('documento',$this->documento);
    }

}