@extends('layouts.main_layout')

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm text-white">
            <i class="fa-solid fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="row">
        <!-- Coluna Principal: Título e Descrição -->
        <div class="col-md-8 mb-4">
            <div class="card bg-dark text-white border-secondary h-100 shadow">
                <div class="card-body p-4">
                    <h2 class="text-warning fw-bold mb-3">{{ $compromisso->titulo }}</h2>
                    <hr class="border-secondary mb-4">
                    <h5 class="fw-bold text-secondary mb-2">DESCRIÇÃO / OBSERVAÇÕES</h5>
                    <p class="text-light" style="white-space: pre-line;">{{ $compromisso->descricao ?? 'Nenhuma descrição informada.' }}</p>
                </div>
            </div>
        </div>

        <!-- Coluna Lateral: Informações e Ações -->
        <div class="col-md-4 mb-4">
            <div class="card bg-dark text-white border-secondary shadow mb-3">
                <div class="card-header border-secondary fw-bold text-warning">
                    INFORMAÇÕES
                </div>
                <ul class="list-group list-group-flush bg-dark">
                    <li class="list-group-item bg-dark text-white border-secondary">
                        <strong class="text-secondary d-block small">DATA E HORA</strong>
                        <span class="text-warning fw-bold">
                            <i class="fa-regular fa-calendar-days me-1"></i>
                            {{ \Carbon\Carbon::parse($compromisso->data_compromisso)->format('d/m/Y \à\s H:i') }}
                        </span>
                    </li>
                    <li class="list-group-item bg-dark text-white border-secondary">
                        <strong class="text-secondary d-block small">CRIADO EM</strong>
                        <span>{{ \Carbon\Carbon::parse($compromisso->created_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Botões de Ação -->
            <div class="d-flex gap-2">
                <a href="{{ route('editarCompromisso', ['id' => \App\Services\Operations::encryptId($compromisso->id)]) }}" class="btn btn-warning fw-bold flex-fill">
                    <i class="fa-solid fa-pen-to-square me-1"></i> EDITAR
                </a>
                <form action="{{ route('deletarCompromisso', ['id' => \App\Services\Operations::encryptId($compromisso->id)]) }}" method="POST" class="flex-fill" onsubmit="return confirm('Deseja apagar este compromisso?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger fw-bold w-100">
                        <i class="fa-solid fa-trash me-1"></i> APAGAR
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection