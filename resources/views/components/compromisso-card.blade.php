@props(['compromisso'])

<div class="card bg-dark text-white border-secondary h-100 shadow-sm">
    <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
            <!-- Título e Ações (Editar / Deletar) -->
            <div class="d-flex justify-content-between align-items-start mb-2">
                <a href="{{ route('detalhesCompromisso', $compromisso->id) }}" class="text-warning text-decoration-none fw-bold h5 mb-0 text-truncate me-2" title="{{ $compromisso->titulo }}">
                    {{ $compromisso->titulo }}
                </a>
                
                <div class="d-flex gap-1">
                    <a href="{{ route('editarCompromisso', ['id' => \App\Services\Operations::encryptId($compromisso->id)]) }}" class="btn btn-sm btn-outline-warning px-2 py-0" title="Editar">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('deletarCompromisso', ['id' => \App\Services\Operations::encryptId($compromisso->id)]) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja apagar este compromisso?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-0" title="Apagar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Descrição Curta (se houver) -->
            @if($compromisso->descricao)
                <p class="text-secondary small mb-3 text-truncate" title="{{ $compromisso->descricao }}">
                    {{ $compromisso->descricao }}
                </p>
            @endif
        </div>

        <!-- Data do Compromisso -->
        <div class="pt-2 border-top border-secondary mt-2">
            <p class="text-warning small mb-0 fw-bold">
                <i class="fa-regular fa-calendar-days me-1"></i>
                {{ \Carbon\Carbon::parse($compromisso->data_compromisso)->format('d/m/Y \à\s H:i') }}
            </p>
        </div>
    </div>
</div>