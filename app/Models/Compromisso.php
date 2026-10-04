<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compromisso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'data_compromisso',
        'notificacao_enviada_em',
    ];

    /**
     * Casts para garantir que os campos de data sejam tratados como instâncias do Carbon.
     */
    protected $casts = [
        'data_compromisso' => 'datetime',
        'notificacao_enviada_em' => 'datetime',
    ];

    /**
     * Relacionamento: O compromisso pertence a um Usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}