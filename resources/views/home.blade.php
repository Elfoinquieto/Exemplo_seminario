@extends('layouts.main_layout')

@section('content')
<div class="container py-4">

    <!-- Cabeçalho Limpo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-warning fw-bold mb-0">COMPROMISSOS</h2>
            <p class="text-secondary small mb-0">Seus Compromissos Agendados:</p>
        </div>

        <a href="{{ route('cadastroCompromisso') }}" class="btn btn-warning fw-bold">
            <i class="fa-solid fa-plus me-1"></i> ADICIONAR COMPROMISSO
        </a>
    </div>

    <!-- Grid de Compromissos -->
    <div class="row">
        @forelse($compromissos as $compromisso)
            <div class="col-md-3 col-sm-6 mb-4">
                <x-compromisso-card :compromisso="$compromisso" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-dark text-secondary text-center border-secondary py-5 mb-0">
                    <i class="fa-solid fa-calendar-xmark fa-2x mb-3 text-warning"></i>
                    <p class="mb-2">Nenhum compromisso encontrado.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection