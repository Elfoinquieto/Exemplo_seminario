<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Marca uma notificação específica como lida.
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->unreadNotifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back();
    }

    /**
     * Marca todas as notificações não lidas como lidas.
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}