# Documentação do SocialHub

## 📋 Visão Geral

O **SocialHub** é uma rede social desenvolvida em PHP com MySQL, que permite aos utilizadores criar publicações, seguir outros utilizadores, comentar, gostar de publicações e trocar mensagens privadas em tempo real.

---

## 📁 Estrutura de Ficheiros e Pastas

```
socialhub/
├── index.php                  # Página principal (feed de publicações)
├── login.php                  # Página de autenticação (entrar)
├── register.php               # Página de registo de novo utilizador
├── logout.php                 # Terminar sessão (logout)
├── profile.php                # Página de perfil de um utilizador
├── post.php                   # Página de detalhe de uma publicação
├── add.php                    # Página para criar nova publicação
├── chat.php                   # Página de lista de conversas
├── chatpage.php               # Página de conversa individual (chat)
├── settings.php               # Página de definições do utilizador
├── README.md                  # Ficheiro de apresentação do projeto
├── documentacao.md            # Esta documentação
│
├── api/                       # Endpoints da API (processamento de ações)
│   ├── login.php              # Processa o login do utilizador
│   ├── register.php           # Processa o registo de novo utilizador
│   ├── create_post.php        # Processa a criação de uma publicação
│   ├── like.php               # Processa o like/unlike numa publicação
│   ├── comment.php            # Processa a criação de um comentário
│   ├── follow.php             # Processa seguir/deixar de seguir utilizador
│   ├── sendmessage.php        # Processa o envio de mensagem privada
│   └── pesquisar.php          # Processa a pesquisa de utilizadores
│
├── config/                    # Configurações da aplicação
│   ├── database.php           # Conexão à base de dados MySQL
│   ├── session.php            # Inicialização da sessão PHP
│   └── constants.php          # Constantes globais (se aplicável)
│
├── includes/                  # Componentes reutilizáveis
│   ├── auth.php               # Verificação de autenticação
│   ├── html.php               # Template HTML inicial <head>
│   ├── header.php             # Cabeçalho/navbar da aplicação
│   ├── navbar.php             # Barra de navegação adicional
│   ├── footer.php             # Rodapé da aplicação
│   ├── functions.php          # Funções auxiliares globais
│   └── flash.php              # Sistema de mensagens flash
│
└── assets/                    # Recursos estáticos
    ├── css/
    │   └── style.css          # Folha de estilo principal (CSS)
    ├── js/                    # Ficheiros JavaScript (futuros)
    ├── images/
    │   └── search-interface-symbol.png  # Ícone da pesquisa
    └── icons/                 # Ícones diversos
```

---

## 🧩 Funcionalidades da Aplicação

### 1. **Autenticação de Utilizadores**

- **Registo** (`register.php` + `api/register.php`): Permite criar uma nova conta com username, nome completo, email e password.
- **Login** (`login.php` + `api/login.php`): Autenticação com username e password (passwords encriptadas com `password_hash`/`password_verify`).
- **Logout** (`logout.php`): Destroi a sessão e redireciona para o login.
- **Proteção de Rotas** (`includes/auth.php`): Verifica se o utilizador está autenticado; redireciona para o login se não estiver.

### 2. **Feed de Publicações** (`index.php`)

- Mostra as publicações dos utilizadores que o utilizador segue.
- Ordenação por data de criação (mais recentes primeiro).
- Cada publicação mostra:
  - Avatar (primeira letra do username)
  - Nome do autor
  - Conteúdo do texto
  - Data/hora da publicação
  - Botão de **like** com contagem
  - Botão de **comentário** com contagem
- Barra lateral com resumo do perfil do utilizador logado.

### 3. **Publicações** (`post.php`)

- Página de detalhe de uma publicação específica.
- Exibe o conteúdo completo, incluindo imagem (se houver).
- Secção de **comentários** com avatar, username e data.
- Formulário fixo no rodapé para adicionar novos comentários.

### 4. **Criar Publicação** (`add.php`)

- Formulário para criar uma nova publicação (apenas texto).
- Envio para `api/create_post.php` que insere na base de dados.

### 5. **Perfil do Utilizador** (`profile.php`)

- Exibe informações do perfil:
  - Avatar (primeira letra do username)
  - Nome de utilizador
  - Biografia (`bio`)
  - Estatísticas: Publicações, Seguidores, A seguir
- **Ações disponíveis**:
  - **Editar Perfil** (apenas para o próprio perfil)
  - **Seguir / Deixar de Seguir** (para perfis de outros utilizadores)
  - **Mandar Mensagem** (para utilizadores que segue)
- Grelha de publicações do utilizador com likes e comentários.

### 6. **Sistema de Seguir** (`api/follow.php`)

- Alterna entre seguir e deixar de seguir um utilizador.
- Utiliza a tabela `followers` com `follower_id` e `following_id`.

### 7. **Sistema de Likes** (`api/like.php`)

- Alterna entre gostar e remover gosto de uma publicação.
- Contagem de likes atualizada em tempo real na interface.

### 8. **Sistema de Comentários** (`api/comment.php`)

- Adiciona comentários a publicações existentes.
- Associa o comentário ao utilizador autenticado.

### 9. **Chat / Mensagens Privadas**

- **Lista de Conversas** (`chat.php`):
  - Mostra todos os utilizadores com quem já trocou mensagens.
  - Ordenado pela última mensagem recebida.
  - Mensagem de boas-vindas se não houver conversas.
- **Conversa Individual** (`chatpage.php`):
  - Mostra o histórico de mensagens entre dois utilizadores.
  - Mensagens próprias alinhadas à direita.
  - Mensagens do outro utilizador alinhadas à esquerda.
  - Formulário fixo no rodapé para enviar novas mensagens.
- **Envio de Mensagens** (`api/sendmessage.php`):
  - Insere a mensagem na tabela `messages` com `sender_id` e `receiver_id`.

### 10. **Pesquisa de Utilizadores** (`api/pesquisar.php`)

- Pesquisa por username exato.
- Redireciona para o perfil do utilizador encontrado.

---

## 🗄️ Base de Dados (Estrutura Esperada)

O projeto espera uma base de dados MySQL chamada `socialhub` com as seguintes tabelas:

### Tabela: `users`
| Coluna     | Tipo          | Descrição                    |
|------------|---------------|------------------------------|
| id         | INT (PK, AI)  | Identificador único          |
| username   | VARCHAR       | Nome de utilizador (único)   |
| nome       | VARCHAR       | Nome completo                |
| email      | VARCHAR       | Email (único)                |
| password   | VARCHAR       | Password encriptada          |
| bio        | TEXT          | Biografia do utilizador      |
| created_at | DATETIME      | Data de criação da conta     |

### Tabela: `posts`
| Coluna     | Tipo          | Descrição                    |
|------------|---------------|------------------------------|
| id         | INT (PK, AI)  | Identificador único          |
| user_id    | INT (FK)      | ID do autor da publicação    |
| content    | TEXT          | Conteúdo da publicação       |
| image      | VARCHAR       | Caminho da imagem (opcional) |
| created_at | DATETIME      | Data de criação              |

### Tabela: `likes`
| Coluna     | Tipo          | Descrição                    |
|------------|---------------|------------------------------|
| id         | INT (PK, AI)  | Identificador único          |
| user_id    | INT (FK)      | ID do utilizador que gostou  |
| post_id    | INT (FK)      | ID da publicação             |

### Tabela: `comments`
| Coluna     | Tipo          | Descrição                    |
|------------|---------------|------------------------------|
| id         | INT (PK, AI)  | Identificador único          |
| post_id    | INT (FK)      | ID da publicação             |
| user_id    | INT (FK)      | ID do autor do comentário    |
| content    | TEXT          | Conteúdo do comentário       |
| created_at | DATETIME      | Data de criação              |

### Tabela: `followers`
| Coluna       | Tipo          | Descrição                        |
|--------------|---------------|----------------------------------|
| id           | INT (PK, AI)  | Identificador único              |
| follower_id  | INT (FK)      | ID de quem segue                 |
| following_id | INT (FK)      | ID de quem está a ser seguido    |

### Tabela: `messages`
| Coluna      | Tipo          | Descrição                    |
|-------------|---------------|------------------------------|
| id          | INT (PK, AI)  | Identificador único          |
| sender_id   | INT (FK)      | ID do remetente              |
| receiver_id | INT (FK)      | ID do destinatário           |
| message     | TEXT          | Conteúdo da mensagem         |
| created_at  | DATETIME      | Data de envio                |

---

## 🎨 Design e Estilo

- Ficheiro: `assets/css/style.css`
- Paleta de cores principal:
  - **Azul Marinho** (`#0a1f44`) — cor principal da marca
  - **Azul Marinho Claro** (`#14315c`) — hover states
  - **Cinza** (`#94a3b8`) — textos secundários
  - **Branco** (`#fff`) — fundos de cards
  - **Fundo** (`#f5f6fa`) — background da página
- Layout responsivo com flexbox e grid.
- Navbar fixa no topo com navegação principal.

---

## ⚙️ Configuração

- **Base de Dados**: Configurada em `config/database.php`
  - Host: `localhost`
  - Utilizador: `root`
  - Password: `123`
  - Base de Dados: `socialhub`
- **Sessão**: Inicializada em `config/session.php`
  - Usa `session_start()` se não houver sessão ativa.

---

## 🔮 Funcionalidades Planeadas para `settings.php`

A página de definições (`settings.php`) terá as seguintes funcionalidades:

### 🎨 Modo Escuro / Claro (Dark/White Mode)
- Alternância entre tema escuro e tema claro.
- Definição guardada na sessão ou preferência do utilizador.
- Ícone de lua/sol para alternar o tema.

### 🔤 Aumentar/Diminuir Tamanho da Letra
- Controlo deslizante para ajustar o tamanho da fonte.
- Opções: Pequena, Normal, Grande, Muito Grande.
- Aplicação global em toda a interface.

### ✏️ Alterar Nome e Username
- Campos de edição para:
  - **Nome completo** (`nome`)
  - **Nome de utilizador** (`username`)
- Validação para evitar duplicação de username.
- Guarda as alterações na base de dados.

---

## 🔐 Segurança

- Passwords encriptadas com `password_hash()` (bcrypt).
- Utilização de `htmlspecialchars()` para prevenir XSS.
- Verificação de sessão em todas as páginas protegidas.
- Type casting para IDs inteiros em consultas SQL.
- Redirecionamentos após ações para evitar re-submissão de formulários.

---

## 🚀 Como Executar

1. Colocar a pasta `socialhub` no diretório do servidor web (ex: `c:/wamp64/www/PHP/`).
2. Importar a base de dados `socialhub.sql` para o MySQL.
3. Configurar a conexão em `config/database.php` se necessário.
4. Aceder a `http://localhost/PHP/socialhub/login.php` no navegador.
5. Registar uma nova conta e começar a usar!

---

## 📝 Notas Finais

- O projeto utiliza **PHP puro** sem frameworks.
- O CSS está consolidado num único ficheiro para facilitar a manutenção.
- As API calls redirecionam de volta para a página anterior após processamento.
- O sistema de chat é funcional mas não usa WebSockets (requer refresh para ver novas mensagens).

