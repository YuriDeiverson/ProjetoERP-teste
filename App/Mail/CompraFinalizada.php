<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompraFinalizada extends Mailable
{
    use Queueable, SerializesModels;

    public $endereco;

    /**
     * Cria uma nova instância da mensagem.
     */
    public function __construct($endereco)
    {
        $this->endereco = $endereco;
    }

    /**
     * Constrói o e-mail.
     */
    public function build()
    {
        return $this->subject('Nova Compra Finalizada')
                    ->markdown('emails.compra')
                    ->with('endereco', $this->endereco);
    }
}