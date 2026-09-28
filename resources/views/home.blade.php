@extends('layouts.main_layout')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-warning fw-bold mb-0">LIVROS</h2>
            <p class="text-secondary small mb-0">Seus Livros: </p>
        </div>
        <a href="{{ route('cadastroLivro') }}" class="btn btn-warning fw-bold">
            <i class="fa-solid fa-plus me-1"></i> ADICIONAR LIVRO
        </a>
    </div>
    <div class="row">
        @forelse($books as $book)
            <div class="col-md-3 col-sm-6 mb-4">
                <x-book-card :book="$book" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-dark text-secondary text-center border-secondary py-5 mb-0">
                    <i class="fa-solid fa-book-open fa-2x mb-3 text-warning"></i>
                    <p class="mb-2">Nenhum livro encontrado.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection