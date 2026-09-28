<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthController;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [MainController::class, 'index'])->name('home');

    Route::get('/autores', [AuthorController::class, 'listAuthors'])->name('listarAutores');
    Route::get('/autores/cadastrar', [AuthorController::class, 'formAuthor'])->name('cadastroAutor');
    Route::post('/autores/salvar', [AuthorController::class, 'saveAuthor'])->name('salvarAutor');
    Route::get('/autores/editar/{id}', [AuthorController::class, 'editAuthor'])->name('editarAutor');
    Route::put('/autores/atualizar/', [AuthorController::class, 'updateAuthor'])->name('atualizarAutor');
    Route::get('/autores/{id}', [AuthorController::class, 'showAuthor'])->name('detalhesAutor');
    Route::delete('/autores/deletar/{id}', [AuthorController::class, 'deletarAutor'])->name('deletarAutor');


    Route::get('/livros', [MainController::class, 'listBooks'])->name('listarLivros');
    Route::get('/livros/cadastrar', [MainController::class, 'formBook'])->name('cadastroLivro');
    Route::post('/livros/salvar', [MainController::class, 'saveBook'])->name('salvarLivro');
    Route::get('/livros/editar/{id}', [MainController::class, 'editBook'])->name('editarLivro');
    Route::put('/livros/atualizar/', [MainController::class, 'updateBook'])->name('atualizarLivro');
    Route::get('/livros/{id}', [MainController::class, 'showBook'])->name('detalhesLivro');
    Route::delete('/livros/deletar/{id}', [MainController::class, 'deletarLivro'])->name('deletarLivro');
    Route::get('/listDeletedBooks', [MainController::class, 'listDeletedBooks'])->name('listDeletedBooks');
    Route::get('/hardDelete-book/{id}', [MainController::class, 'hardDeleteBook'])->name('hardDelete');
    Route::get('/restore-book/{id}', [MainController::class, 'restoreBook'])->name('restore');
});