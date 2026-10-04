<?php

namespace App\Notifications;

use App\Models\Compromisso;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompromissoLembrete extends Notification
{
    use Queueable;

    public $compromisso;

    public function __construct(Compromisso $compromisso)
    {
        $this->compromisso =$compromisso;
    }

    /**
     * Define os canais de envio: E-mail e Banco de Dados.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Template do E-mail enviado.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $dataFormatted =$this->compromisso->data_compromisso->format('d/m/Y \à\s H:i');

        return (new MailMessage)
            ->subject('Lembrete: Compromisso amanhã!')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Você tem um compromisso agendado para amanhã:')
            ->line('**Título:** ' . $this->compromisso->titulo)
            ->line('**Data e Hora:** ' . $dataFormatted)
            ->line('**Descrição:** ' . ($this->compromisso->descricao ?? 'Sem descrição.'))
            ->action('Ver Compromisso', url("http://127.0.0.1:8000"))
            ->line('Fique atento para não perder o horário!');
    }

    /**
     * Estrutura salva no banco de dados (tabela `notifications`).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'compromisso_id'   => $this->compromisso->id,
            'titulo'           => $this->compromisso->titulo,
            'data_compromisso' => $this->compromisso->data_compromisso->toDateTimeString(),
            'mensagem'         => 'Lembrete: Seu compromisso "' . $this->compromisso->titulo . '" é amanhã!',
        ];
    }
}