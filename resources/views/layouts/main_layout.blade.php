<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AGENDAPP')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #18181b;
            color: #f4f4f5;
        }
    </style>
</head>

<body class="bg-dark text-white min-vh-100 d-flex flex-column">

    <header class="bg-warning text-dark py-3 px-4 d-flex justify-content-between align-items-center shadow">
        <a href="{{ route('home') }}" class="h4 fw-bold text-dark text-decoration-none mb-0 tracking-wider">
            AGENDAPP
        </a>
        <div class="d-flex align-items-center gap-3">

            @auth
                <span class="fw-bold small">{{ auth()->user()->username }}</span>

                <!-- Central de Notificações (Sininho) -->
                <div class="dropdown">
                    <button class="btn btn-dark text-warning position-relative btn-sm" type="button"
                        id="dropdownNotification" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->unreadNotifications->count() }}
                                <span class="visually-hidden">notificações não lidas</span>
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown das Notificações -->
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg p-2" aria-labelledby="dropdownNotification"
                        style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <li
                            class="dropdown-header d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <strong class="text-dark">Notificações</strong>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-decoration-none small p-0 border-0">
                                        Marcar todas como lidas
                                    </button>
                                </form>
                            @endif
                        </li>

                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <li class="mb-2">
                                <a class="dropdown-item rounded p-2 text-wrap {{ $loop->last ? '' : 'border-bottom' }}"
                                    href="{{ $notification->data['link'] ?? '#' }}">
                                    <div class="fw-bold text-dark">{{ $notification->data['titulo'] ?? 'Notificação' }}</div>
                                    <div class="small text-muted mb-1">{{ $notification->data['mensagem'] ?? '' }}</div>
                                    <div class="text-end" style="font-size: 0.75rem;">
                                        <span class="text-secondary">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="dropdown-item text-center text-muted small py-3">
                                Nenhuma notificação não lida.
                            </li>
                        @endforelse
                    </ul>
                </div>
                <a href="{{ route('listDeletedCompromissos') }}" class="btn btn-sm btn-dark text-warning fw-bold mx-1">
                    <i class="fa-regular fa-trash-can"></i>
                </a>
                <a href="{{ route('logout') }}" class="btn btn-sm btn-dark text-warning fw-bold">Sair</a>
            @endauth

        </div>
    </header>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>