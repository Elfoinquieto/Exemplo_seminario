<?php

namespace App\Console\Commands;

use App\Models\Compromisso;
use App\Notifications\CompromissoLembrete;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnviarLembretesCompromissos extends Command
{
    /**
     * Nome do comando que será rodado no terminal ou pelo scheduler.
     */
    protected $signature = 'compromissos:enviar-lembretes';

    protected $description = 'Verifica compromissos agendados para o dia seguinte e envia as notificações por e-mail e banco de dados.';

    public function handle()
    {
        // Busca compromissos que acontecem amanhã e ainda NÃO receberam notificação
        $compromissos = Compromisso::whereDate('data_compromisso', \Carbon\Carbon::tomorrow())
            ->whereNull('notificacao_enviada_em')
            ->get();

        $count = 0;

        foreach ($compromissos as $compromisso) {
            // Dispara as notificações para o dono do compromisso
            $compromisso->user->notify(new CompromissoLembrete($compromisso));

            // Marca o compromisso para não enviar notificação duplicada
            $compromisso->update([
                'notificacao_enviada_em' => now(),
            ]);

            $count++;
        }

        $this->info("Lembretes enviados com sucesso para {$count} compromisso(s).");
    }
}