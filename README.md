# Guia Prático: Implementando Notificações no Laravel (`Notification`)

> **Módulo 1: Seminário Técnico de Funcionalidades Laravel**  
> **Disciplina:** Desenvolvimento Web 2 — IFPR Campus Curitiba  
> **Professor:** Jair José Ferronato  
> **Tema 09:** Notificações no Sistema e E-mail (`Illuminate\Notifications\Notification`)  
> **Dupla:** Cauan de Souza Valter Stocco e João Pedro

---

## 1. O que são e por que usar Laravel Notifications?

O recurso de **Notifications** do Laravel é uma abstração desenhada para disparar mensagens através de **múltiplos canais de comunicação** (E-mail, Banco de Dados, SMS, Broadcast/WebSockets, Slack, etc.) utilizando uma **única classe responsável**.

### Notifications vs. Mailables
- **`Mailable`:** Utilizado para e-mails pontuais com layouts HTML complexos e exclusivos (ex: faturas e relatórios).
- **`Notification`:** Ideal para alertas de sistema que precisam notificar o usuário por mais de um meio simultaneamente (ex: gravar um alerta no ícone de "sino" do site **E** enviar um e-mail de aviso).

---

## 2. Requisitos e Configuração do Ambiente

Para utilizar os canais de **Banco de Dados** (`database`) e **E-mail** (`mail`), siga o passo a passo de configuração abaixo em seu projeto:

### 2.1 Gerar a Tabela de Notificações
Execute no terminal os comandos para criar a migration nativa da tabela `notifications` e aplicá-la no banco de dados:

```bash
php artisan notification:table
php artisan migrate
```
> *A tabela `notifications` armazenará os alertas gerados para exibição na interface do sistema (`database`).*

### 2.2 Configurar o Provedor SMTP no `.env`
No arquivo `.env`, defina o fuso horário e as credenciais do seu serviço de e-mail. Recomenda-se utilizar o **Brevo (SMTP Relay)** na porta 587 para evitar erros de autenticação (`535 BadCredentials`) comuns no Gmail:

```env
APP_TIMEZONE=America/Sao_Paulo

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME="seu_login_smtp_brevo"
MAIL_PASSWORD="sua_chave_smtp_brevo"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="seu_email@dominio.com"
MAIL_FROM_NAME="SeuSistema"
```

---

## 3. Estrutura da Classe Notification

### 3.1 Criar a Notificação via Artisan
Rode o comando de criação no terminal:

```bash
php artisan make:notification ExemploAlertaNotification
```
> *A classe gerada ficará localizada em `app/Notifications/ExemploAlertaNotification.php`.*

### 3.2 Estruturação do Código
Abaixo está o exemplo de uma classe de notificação configurada para enviar por e-mail e gravar no banco de dados:

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ExemploAlertaNotification extends Notification
{
    use Queueable;

    public $dados;

    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    // 1. Define os canais de entrega ativos para esta notificação
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // 2. Formatação do conteúdo para o canal E-mail ('mail')
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Aviso: Você tem uma nova atualização!')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Resumo da atualização: ' . $this->dados->titulo)
            ->action('Visualizar no Sistema', url('/dashboard'))
            ->line('Obrigado por usar nossa aplicação!');
    }

    // 3. Formatação dos dados gravados no banco para o canal 'database' (tabela 'notifications')
    public function toArray(object $notifiable): array
    {
        return [
            'item_id' => $this->dados->id,
            'titulo' => $this->dados->titulo,
            'mensagem' => 'Sua tarefa "' . $this->dados->titulo . '" expira em breve.',
        ];
    }
}
```

---

## 4. Como Disparar as Notificações

O Model que receberá os alertas (geralmente `App\Models\User`) **deve possuir a trait `Notifiable`**:

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable; // Permite executar $user->notify(...)
}
```

### Método 1: Disparo Direto pelo Usuário
```php
$user = User::find(1);
$user->notify(new ExemploAlertaNotification($registro));
```

### Método 2: Disparo em Lote usando a Facade
```php
use Illuminate\Support\Facades\Notification;

$usuarios = User::all();
Notification::send($usuarios, new ExemploAlertaNotification($registro));
```

---

## 5. Lendo Notificações no Blade (`database`)

Ao salvar as notificações no banco, o Laravel injeta automaticamente métodos de consulta no usuário autenticado:

### 5.1 Buscar Notificações no Controller
```php
// Retorna apenas as notificações NÃO LIDAS
$naoLidas = auth()->user()->unreadNotifications;

// Retorna TODAS as notificações do usuário
$todas = auth()->user()->notifications;
```

### 5.2 Renderizar na View (Sino de Notificações)
```blade
{{-- Contador de notificações não lidas --}}
<span class="badge bg-danger">
    {{ auth()->user()->unreadNotifications->count() }}
</span>

{{-- Listagem dos alertas --}}
@foreach(auth()->user()->unreadNotifications as $notification)
    <div class="alert alert-info">
        <p>{{ $notification->data['mensagem'] }}</p>
        <small>{{ $notification->created_at->diffForHumans() }}</small>
    </div>
@endforeach
```

### 5.3 Marcar Notificações como Lidas
```php
// Marcar TODAS as notificações do usuário como lidas
auth()->user()->unreadNotifications->markAsRead();

// Marcar UMA notificação específica como lida pelo ID
auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
```

---

## 6. Automação do Disparo (Artisan Command)

Você pode encadear buscas de banco com o envio das notificações criando um **Comando Personalizado**:

```bash
php artisan make:command DispararLembretesDiarios
```

No método `handle()` do arquivo criado em `app/Console/Commands/`:

```php
public function handle()
{
    $amanha = \Carbon\Carbon::tomorrow();

    // Busca registros cuja data seja amanhã e ainda não notificados
    $itens = MeuModel::whereDate('data_agendada', $amanha)
        ->whereNull('notificado_em')
        ->get();

    foreach ($itens as $item) {
        $item->user->notify(new ExemploAlertaNotification($item));
        $item->update(['notificado_em' => now()]);
    }

    $this->info('Notificações enviadas com sucesso!');
}
```

---

## 7. Boas Práticas e Pegadinhas Técnicas

1. **Ajuste de Fuso Horário (`Timezone`):**  
   Ao trabalhar com comparações de data (`Carbon::tomorrow()`), garanta que o arquivo `config/app.php` esteja configurado com `'timezone' => 'America/Sao_Paulo'` para evitar que as buscas fiquem desalinhadas com o horário local. Execute `php artisan config:clear` após a alteração.

2. **Comparação de Colunas `DATETIME` no Eloquent:**  
   Colunas do tipo `DATETIME` que armazenam horas/minutos (`YYYY-MM-DD HH:MM:SS`) falham em queries com `where()` exato. Prefira usar `whereDate('coluna', Carbon::tomorrow())` ou `whereRaw('DATE(coluna) = ?', [$data])`.

3. **Performance com Envio Assíncrono (`Queue`):**  
   Para evitar que o envio de e-mails trave a navegação da página do usuário, adicione a interface `ShouldQueue` na sua classe de Notificação:
   ```php
   use Illuminate\Contracts\Queue\ShouldQueue;

   class ExemploAlertaNotification extends Notification implements ShouldQueue
   {
       use Queueable;
       // ...
   }
   ```
