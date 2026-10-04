<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Schedule;

// Agendamento automático de envio de lembretes
Schedule::command('compromissos:enviar-lembretes')->dailyAt('08:00');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [MainController::class, 'index'])->name('home');

    Route::get('/compromissos/cadastrar', [MainController::class, 'formCompromisso'])->name('cadastroCompromisso');
    Route::post('/compromissos/salvar', [MainController::class, 'saveCompromisso'])->name('salvarCompromisso');
    Route::get('/compromissos/editar/{id}', [MainController::class, 'editCompromisso'])->name('editarCompromisso');
    Route::put('/compromissos/atualizar', [MainController::class, 'updateCompromisso'])->name('atualizarCompromisso');
    Route::get('/compromissos/{id}', [MainController::class, 'showCompromisso'])->name('detalhesCompromisso');
    Route::delete('/compromissos/deletar/{id}', [MainController::class, 'deletarCompromisso'])->name('deletarCompromisso');

    Route::get('/listDeletedCompromissos', [MainController::class, 'listDeletedCompromissos'])->name('listDeletedCompromissos');
    Route::get('/hardDelete-compromisso/{id}', [MainController::class, 'hardDeleteCompromisso'])->name('hardDelete');
    Route::get('/restore-compromisso/{id}', [MainController::class, 'restoreCompromisso'])->name('restore');

    // Gestão de Notificações
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});