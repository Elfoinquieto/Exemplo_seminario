@extends('layouts.main_layout')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="row mb-3">
                    <div class="col">
                        <p class="display-6 text-warning mb-0 fw-bold">
                            {{ isset($compromisso) ? 'EDITAR COMPROMISSO' : 'NOVO COMPROMISSO' }}
                        </p>
                    </div>
                    <div class="col text-end">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-danger">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>

                <form action="{{ isset($compromisso) ? route('atualizarCompromisso') : route('salvarCompromisso') }}" method="post">
                    @csrf
                    @if(isset($compromisso))
                        @method('PUT')
                        <input type="hidden" name="compromisso_id" value="{{ \App\Services\Operations::encryptId($compromisso->id) }}">
                    @endif

                    <!-- Título do Compromisso -->
                    <div class="mb-3">
                        <label class="form-label text-light">Título do Compromisso</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" name="titulo"
                            value="{{ old('titulo', $compromisso->titulo ?? '') }}" placeholder="Ex: Reunião de Projeto">
                        @error('titulo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Data e Hora do Compromisso -->
                    <div class="mb-3">
                        <label class="form-label text-light">Data e Hora do Compromisso</label>
                        <input type="datetime-local" class="form-control bg-dark text-white border-secondary" name="data_compromisso"
                            value="{{ old('data_compromisso', isset($compromisso->data_compromisso) ? \Carbon\Carbon::parse($compromisso->data_compromisso)->format('Y-m-d\TH:i') : '') }}">
                        @error('data_compromisso')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descrição / Observações -->
                    <div class="mb-3">
                        <label class="form-label text-light">Descrição / Observações</label>
                        <textarea class="form-control bg-dark text-white border-secondary" name="descricao"
                            rows="4" placeholder="Detalhes ou pauta do compromisso...">{{ old('descricao', $compromisso->descricao ?? '') }}</textarea>
                        @error('descricao')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botões de Ação -->
                    <div class="row mt-4">
                        <div class="col text-end">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary px-4 me-2">
                                <i class="fa-solid fa-ban me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold">
                                <i class="fa-regular fa-circle-check me-1"></i> Salvar
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection