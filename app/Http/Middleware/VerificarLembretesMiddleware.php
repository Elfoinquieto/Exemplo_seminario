<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerificarLembretesMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Otimização: Executa no máximo 1 vez a cada 30 minutos por usuário 
        // para evitar gargalos de desempenho no servidor.
        if (auth()->check()) {
            $cacheKey = 'verificar-lembretes-user-' . auth()->id();

            if (!Cache::has($cacheKey)) {
                // Dispara o comando em segundo plano ou executa diretamente
                try {
                    Artisan::call('compromissos:enviar-lembretes');
                } catch (\Exception $e) {
                    // Trata caso ocorra algum erro para não quebrar a navegação do usuário
                }

                // Guarda no cache por 30 minutos
                Cache::put($cacheKey, true, now()->addMinutes(5));
            }
        }

        return $next($request);
    }
}