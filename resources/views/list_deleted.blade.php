@extends('layouts.main_layout')

@section('content')
    <div class="container py-4">
        @if(count($listaExcluidos) === 0)
            <div class="mb-3 text-end">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm text-white">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>
            <div class="row my-5">
                <div class="col text-center">
                    <h2 class="text-warning fw-bold mb-0">Você não possui compromissos deletados!</h2>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-warning fw-bold mb-0">Compromissos Deletados</h2>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm text-white">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <div class="row g-3">
                @foreach ($listaExcluidos as $excluido)
                    <div class="col-12">
                        @include('deleted', ['excluido' => $excluido])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection