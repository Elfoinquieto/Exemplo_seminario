<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\Compromisso;
use App\Services\Operations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        $compromissos = Auth::user()->compromissos;

        return view('home', [
            'compromissos' => $compromissos
        ]);
    }

    public function formCompromisso()
    {
        return view('form');
    }

    public function saveCompromisso(Request $request)
    {
        $request->validate([
            'titulo' => 'required|min:2|max:200',
            'descricao' => 'required|max:2000',
            'data_compromisso' => 'required|date|after:now',
        ], [
            'titulo.required' => 'O título do compromisso é obrigatório.',
            'titulo.min' => 'O título deve ter pelo menos :min caracteres.',
            'titulo.max' => 'O título não pode passar de :max caracteres.',
            'descricao.required' => 'A descrição do compromisso é obrigatória.',
            'descricao.max' => 'A descrição não pode ultrapassar :max caracteres.',
            'data_compromisso.required' => 'A data do compromisso é obrigatória.',
            'data_compromisso.date' => 'Informe uma data e hora válidas.',
            'data_compromisso.after' => 'A data do compromisso deve ser no futuro.',
        ]);

        $compromisso = new Compromisso();
        $compromisso->user_id = Auth::id();
        $compromisso->titulo = $request->titulo;
        $compromisso->descricao = $request->descricao;
        $compromisso->data_compromisso = $request->data_compromisso;

        $compromisso->save();

        return redirect()->route('home')->with('success', 'Compromisso cadastrado com sucesso!');
    }

    public function editCompromisso($id)
    {
        $decrypted_id = Operations::decryptId($id);
        $compromisso = Auth::user()->compromissos()->findOrFail($decrypted_id);

        return view('form', ['compromisso' => $compromisso]);
    }

    public function updateCompromisso(Request $request)
    {
        $request->validate([
            'compromisso_id' => 'required',
            'titulo' => 'required|min:2|max:200',
            'descricao' => 'required|max:2000',
            'data_compromisso' => 'required|date',
        ], [
            'compromisso_id.required' => 'ID do compromisso inválido.',
            'titulo.required' => 'O título do compromisso é obrigatório.',
            'titulo.min' => 'O título deve ter pelo menos :min caracteres.',
            'titulo.max' => 'O título não pode passar de :max caracteres.',
            'descricao.required' => 'A descrição do compromisso é obrigatória.',
            'descricao.max' => 'A descrição não pode ultrapassar :max caracteres.',
            'data_compromisso.required' => 'A data do compromisso é obrigatória.',
            'data_compromisso.date' => 'Informe uma data e hora válidas.',
        ]);

        $id = Operations::decryptId($request->compromisso_id);

        $compromisso = Auth::user()->compromissos()->find($id);

        if (!$compromisso) {
            return redirect()->route('home')->with('error', 'Compromisso não encontrado.');
        }

        $compromisso->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data_compromisso' => $request->data_compromisso,
        ]);

        return redirect()->route('home')->with('success', 'Compromisso atualizado com sucesso!');
    }

    public function showCompromisso($id)
    {
        $compromisso = Auth::user()->compromissos()->findOrFail($id);

        return view('show', ['compromisso' => $compromisso]);
    }

    public function deletarCompromisso($id)
    {
        $decrypted_id = Operations::decryptId($id);

        $compromisso = Auth::user()->compromissos()->findOrFail($decrypted_id);
        if (!$compromisso) {
            return redirect()->route('home');
        }
        $compromisso->delete();
        return redirect()->route('home');
    }

    public function listDeletedCompromissos()
    {
        $listaExcluidos = Compromisso::onlyTrashed()->where('user_id', Auth::id())->get();
        return view('list_deleted', ['listaExcluidos' => $listaExcluidos]);
    }

    public function hardDeleteCompromisso($id)
    {
        $compromissoId = Operations::decryptId($id);
        $compromisso = Compromisso::withTrashed()->where('user_id', Auth::id())->find($compromissoId);
        if (!$compromisso) {
            return redirect()->route('home');
        }
        $compromisso->forceDelete();
        return redirect()->route('home');
    }

    public function restoreCompromisso($id)
    {
        $compromissoId = Operations::decryptId($id);
        $compromisso = Compromisso::withTrashed()->where('user_id', Auth::id())->find($compromissoId);
        if (!$compromisso) {
            return redirect()->route('home');
        }
        $compromisso->restore();
        return redirect()->route('home');
    }
}