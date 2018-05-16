<?php

namespace ABASTV2\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class CuentaReestablecida extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $password, $nombre_usuario)
    {
        $this->content = $content;
        $this->password = $password;
        $this->nombre_usuario = $nombre_usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->markdown('emails.verifica')->with('content',$this->content)->with('password', $this->password)->with('nombre_usuario', $this->nombre_usuario);
    }

}