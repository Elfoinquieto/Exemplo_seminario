<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class MainController extends Controller
{
     public function index()
    {
        $books = Auth::user()->books;

        return view('home', [

            'books' => $books
        ]);
    }

    public function formBook()
    {
        $authors = Auth::user()->authors;
        return view('books.form', ['authors' => $authors]);
    }

    public function saveBook(Request $request)
    {
        $request->validate([
            'title' => 'required|min:2|max:200',
            'author_id' => 'required|exists:authors,id',
            'isbn' => 'required|string|max:13',
            'genre' => ['required', Rule::in(Book::GENRES)],
            'description' => 'required|max:2000',
        ], [
            'title.required' => 'O título do livro é obrigatório.',
            'title.min' => 'O título deve ter pelo menos :min caracteres.',
            'title.max' => 'O título não pode passar de :max caracteres.',
            'author_id.required' => 'O autor do livro é obrigatório.',
            'author_id.exists' => 'O autor selecionado é inválido.',
            'isbn.required' => 'O código ISBN do livro é obrigatório.',
            'genre.required' => 'O gênero do livro é obrigatório.',
            'genre.in' => 'Selecione um gênero válido da lista.',
            'description.required' => 'A descrição do livro é obrigatório.',
            'description.max' => 'A descrição não pode ultrapassar :max caracteres.',
        ]);

        $book = new Book();
        $book->user_id = Auth::id();
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->isbn = $request->isbn;
        $book->genre = $request->genre;
        $book->description = $request->description;

        $book->save();

        return redirect()->route('home')->with('success', 'Livro cadastrado com sucesso!');
    }

    public function editBook($id)
    {
        $decrypted_id = Operations::decryptId($id);
        $book = Auth::user()->books()->findOrFail($decrypted_id);
        $authors = Auth::user()->authors;

        return view('books.form', ['book' => $book, 'authors' => $authors]);
    }

    public function updateBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'title' => 'required|min:2|max:200',
            'author_id' => 'required|exists:authors,id',
            'isbn' => 'required|string|max:13',
            'genre' => ['required', Rule::in(Book::GENRES)],
            'description' => 'required|max:2000',
        ], [
            'title.required' => 'O título do livro é obrigatório.',
            'title.min' => 'O título deve ter pelo menos :min caracteres.',
            'title.max' => 'O título não pode passar de :max caracteres.',
            'author_id.required' => 'O autor do livro é obrigatório.',
            'author_id.exists' => 'O autor selecionado é inválido.',
            'isbn.required' => 'O código ISBN do livro é obrigatório.',
            'genre.required' => 'O gênero do livro é obrigatório.',
            'genre.in' => 'Selecione um gênero válido da lista.',
            'description.required' => 'A descrição do livro é obrigatório.',
            'description.max' => 'A descrição não pode ultrapassar :max caracteres.',
        ]);

        $id = Operations::decryptId($request->book_id);

        $book = Auth::user()->books()->find($id);

        if (!$book) {
            return redirect()->route('home')->with('error', 'Livro não encontrado.');
        }

        $book->update([
            'title' => $request->title,
            'author_id' => $request->author_id,
            'isbn' => $request->isbn,
            'genre' => $request->genre,
            'description' => $request->description,
        ]);

        return redirect()->route('home')->with('success', 'Livro atualizado com sucesso!');
    }

    public function showBook($id)
    {
        $book = Auth::user()->books()->findOrFail($id);

        return view('books.show', ['book' => $book]);
    }

    public function deletarLivro($id)
    {
        $decrypted_id = Operations::decryptId($id);

        $book = Auth::user()->books()->findOrFail($decrypted_id);
        if (!$book) {
            return redirect()->route('home');
        }
        $book->delete();
        return redirect()->route('home');
    }

    public function listDeletedBooks()
    {
        $listaExcluidos = Book::onlyTrashed()->get();
        return view('books.list_deleted', ['listaExcluidos' => $listaExcluidos]);
    }

    public function hardDeleteBook($id)
    {
        $bookId = Operations::decryptId($id);
        $book = Book::withTrashed()->find($bookId);
        if (!$book) {
            return redirect()->route('home');
        }
        $book->forceDelete();
        return redirect()->route('home');
    }

    public function restoreBook($id)
    {
        $bookId = Operations::decryptId($id);
        $book = Book::withTrashed()->find($bookId);
        if (!$book) {
            return redirect()->route('home');
        }
        $book->restore();
        return redirect()->route('home');
    }
}
