<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BIBLIOTECAPP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-900 text-zinc-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-zinc-800 p-8 rounded-lg shadow-2xl border border-zinc-700">
        
       
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-yellow-400 tracking-wider uppercase">BIBLIOTECAPP</h1>
            <p class="text-zinc-400 text-sm mt-1">Informe suas credenciais para acessar</p>
        </div>

       
        @if(session('login_error'))
            <div class="bg-red-500/10 border border-red-500 text-red-400 p-3 rounded text-sm mb-6">
                {{ session('login_error') }}
            </div>
        @endif

        
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="text_username" class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-2">Usuário</label>
                <input type="text" 
                       id="text_username" 
                       name="text_username" 
                       value="{{ old('text_username') }}"
                       class="w-full bg-zinc-900 border border-zinc-700 rounded px-4 py-2.5 text-zinc-100 focus:outline-none focus:border-yellow-400 transition-colors"
                       placeholder="Digite seu usuário">
                @error('text_username')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

           
            <div>
                <label for="text_password" class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-2">Senha</label>
                <input type="password" 
                       id="text_password" 
                       name="text_password" 
                       class="w-full bg-zinc-900 border border-zinc-700 rounded px-4 py-2.5 text-zinc-100 focus:outline-none focus:border-yellow-400 transition-colors"
                       placeholder="••••••••">
                @error('text_password')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            
            <button type="submit" 
                    class="w-full bg-yellow-400 hover:bg-yellow-500 text-zinc-900 font-bold py-3 rounded uppercase tracking-wider transition-colors mt-2">
                Entrar
            </button>
        </form>

    </div>

</body>
</html>