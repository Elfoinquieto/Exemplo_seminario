<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BIBLIOTECAPP')</title>


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
            BIBLIOTECAPP
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="fw-bold small">{{ auth()->user()->username }}</span>
            <a href="{{ route('listDeletedBooks') }}" class="btn btn-sm btn-dark text-warning fw-bold mx-1">
                <i class="fa-regular fa-trash-can"></i>
            </a>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-dark text-warning fw-bold">Sair</a>
        </div>
    </header>


    <main class="flex-grow-1">
        @yield('content')
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>