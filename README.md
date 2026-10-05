AgendApp - Notificações no Sistema e E-mail (Notification)
Módulo 1: Seminário Técnico de Funcionalidades Laravel

   
PDF
+ 2

Disciplina: Desenvolvimento Web 2 — IFPR Campus Curitiba   
PDF

Professor: Jair José Ferronato   
PDF

Tema 09: Notificações no Sistema e E-mail (Notification)   
PDF
+ 1

Integrantes da Dupla: Cauan de Souza Valter Stocco e João Pedro   
PDF

📌 Contexto e Problema de Mercado
Em sistemas corporativos e plataformas de agendamento, a falta de alertas automáticos sobre compromissos gera esquecimentos e perdas de prazos importantes.

A funcionalidade de Notifications do Laravel resolve esse problema ao abstrair múltiplos canais de entrega (como e-mail e banco de dados) em uma única classe unificada. No AgendApp, a funcionalidade é aplicada para garantir que os usuários recebam lembretes automáticos de compromissos agendados para o dia seguinte, gravando um alerta na interface do sistema e enviando uma notificação por e-mail para a caixa de entrada real.   
PDF

🛠️ Recursos e Tecnologias Utilizadas
Framework: Laravel 10/11

Linguagem: PHP 8.x

Manipulação de Datas: Carbon\Carbon

Driver de E-mail / SMTP: Brevo (SMTP Relay)

Canais de Notificação: mail e database

   
PDF

📋 Requisitos para Execução e Instalação
1. Pré-requisitos
PHP >= 8.1

Composer instalado

Servidor de banco de dados (MySQL/MariaDB)

2. Passo a Passo de Configuração Local
Clonar o Repositório:

Bash
git clone https://github.com/seu-usuario/agendapp-seminario.git
cd agendapp-seminario
Instalar Dependências:

Bash
composer install
Configurar o Arquivo de Ambiente (.env):
Crie uma cópia do arquivo .env.example:

Bash
cp .env.example .env
Gerar a Chave da Aplicação:

Bash
php artisan key:generate
Configurar as Variáveis no .env:
Ajuste a timezone e insira as credenciais do servidor SMTP (Brevo Relay):

Snippet de código
APP_TIMEZONE=America/Sao_Paulo

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agendapp
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME="bc8aab001@smtp-brevo.com"
MAIL_PASSWORD="SUA_CHAVE_SMTP_DO_BREVO"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="seu_email@dominio.com"
MAIL_FROM_NAME="AgendApp"
Executar Migrations e Criar a Tabela de Notificações:

Bash
php artisan notification:table
php artisan migrate
🚀 Como Executar e Testar a Funcionalidade
1. Disparo Manual via Comando Artisan
Para executar a busca por compromissos do dia seguinte e disparar o e-mail/notificação diretamente pelo terminal:

Bash
php artisan compromissos:enviar-lembretes
2. Disparo Automático em Sessões de Usuário (Middleware)
O sistema possui o VerificarLembretesMiddleware registrado no grupo web. Ele verifica e envia os e-mails pendentes em segundo plano a cada interação do usuário no site, limitando a execução via cache (a cada 30 minutos) para manter a alta performance da aplicação.

💡 Dificuldades Encontradas e Boas Práticas (Pegadinhas Técnicas)
Durante a implementação do seminário, foram superados os seguintes pontos críticos:

Fuso Horário (Timezone):

Por padrão, a aplicação operava em UTC. Isso fazia com que o método Carbon::tomorrow() gerasse um dia incorreto em relação ao horário de Brasília.

Solução: Alteração de 'timezone' => 'America/Sao_Paulo' em config/app.php e execução de php artisan config:clear.

Comparação de Colunas DateTime no Eloquent:

O campo data_compromisso armazena data e hora (2026-10-05 16:11:00). Consultas usando where() simples falhavam por tentar comparar com 00:00:00.

Solução: Ajuste da busca para usar whereDate('data_compromisso', Carbon::tomorrow()) ou a instrução SQL whereRaw('DATE(data_compromisso) = ?', [$dataAmanha]).

Bloqueio de Autenticação SMTP no Gmail (535 BadCredentials):

O serviço do Gmail recusava a autenticação direta por senha de app.

Solução: Troca para o Brevo (SMTP Relay) na porta 587 com criptografia tls, garantindo o envio correto e a entrega na caixa de entrada do usuário.
