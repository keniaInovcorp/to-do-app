# Aplicação To-Do

Aplicação web para gestão de tarefas desenvolvida com Laravel 12 e Tailwind CSS. Sistema intuitivo e responsivo para organização de atividades diárias com funcionalidades completas de CRUD, filtros e priorização de tarefas.

## Descrição do Projeto

Esta aplicação oferece uma solução eficiente para gestão de tarefas pessoais, permitindo aos utilizadores criar, editar, visualizar e eliminar tarefas com diferentes níveis de prioridade e estados de conclusão. O sistema inclui autenticação de utilizadores e interface totalmente responsiva.

## Tecnologias Utilizadas

### Backend
- PHP 8.2
- Laravel 12
- Laravel Jetstream 5.4
- Laravel Sanctum 4.0
- Livewire 3.6

### Frontend
- Tailwind CSS 3.4
- Vue.js 3.5
- Vite 7.0
- Axios 1.11

### Base de Dados
- MySQL / SQLite
- Eloquent ORM

## Funcionalidades Principais

### Gestão de Tarefas
- Criação de tarefas com título e descrição
- Definição de data de vencimento
- Atribuição de prioridade (alta, média, baixa)
- Edição completa de tarefas existentes
- Marcação de tarefas como concluídas
- Exclusão de tarefas individuais

### Visualização e Organização
- Listagem completa de todas as tarefas
- Filtragem por estado (pendente, concluída, todas)
- Filtragem por prioridade
- Filtragem por data de vencimento
- Visualização detalhada de cada tarefa
- Indicação visual de tarefas atrasadas

### Autenticação e Segurança
- Sistema de autenticação de utilizadores
- Registo e login seguro
- Gestão de perfil de utilizador
- Autorização baseada em políticas
- Proteção de rotas autenticadas

### Interface Responsiva
- Design adaptável para desktop, tablet e mobile
- Interface limpa e minimalista
- Componentes reutilizáveis do Tailwind CSS
- Feedback visual para ações do utilizador

## Requisitos do Sistema

- PHP 8.2
- Composer 2.x
- Node.js 18.x
- NPM ou Yarn
- MySQL 8.0
- Extensões PHP: PDO, Mbstring, OpenSSL, Tokenizer, XML, Ctype, JSON

## Como Utilizar

### Acesso à Aplicação

1. Aceder à página inicial da aplicação
2. Realizar registo de novo utilizador ou login
3. Após autenticação, será redirecionado para o dashboard

### Gestão de Tarefas

**Criar Nova Tarefa:**
- Clicar no botão "Nova Tarefa"
- Preencher título (obrigatório)
- Adicionar descrição (opcional)
- Selecionar prioridade (alta, média ou baixa)
- Definir data de vencimento (opcional)
- Guardar tarefa

**Editar Tarefa:**
- Clicar na tarefa desejada
- Modificar informações necessárias
- Guardar alterações

**Marcar como Concluída:**
- Clicar no checkbox ou botão de conclusão
- A tarefa será automaticamente marcada como concluída

**Eliminar Tarefa:**
- Clicar no botão de eliminar
- Confirmar ação

**Filtrar Tarefas:**
- Utilizar filtros disponíveis para visualizar:
  - Todas as tarefas
  - Apenas pendentes
  - Apenas concluídas
  - Por prioridade
  - Por data de vencimento

## Testes

A aplicação possui uma suite completa de testes unitários e de integração que podem ser executados através do PHPUnit ou utilizando os scripts disponíveis no Composer.

### Cobertura de Testes

A aplicação inclui testes para:
- Funcionalidades de autenticação
- CRUD de tarefas
- Políticas de autorização
- Validações de formulários
- Gestão de perfil de utilizador

## Estrutura do Projeto

O projeto segue a estrutura padrão do Laravel, organizado da seguinte forma:

**Controllers:** Responsáveis pela lógica de controlo da aplicação (DashboardController, TaskController)

**Models:** Representação das entidades de dados (Task, User)

**Policies:** Regras de autorização para acesso aos recursos (TaskPolicy)

**Migrations:** Estrutura da base de dados versionada

**Factories:** Geradores de dados para testes

**Views:** Templates Blade para renderização das páginas

**Components:** Componentes Vue.js reutilizáveis

**Routes:** Definição das rotas da aplicação


### Código
- Seguimento das convenções do Laravel
- Utilização de Models, Controllers e Migrations
- Implementação de Policies para autorização
- Código organizado e modular
- Validação de dados no backend
- Uso de Factory para testes

### Frontend
- Componentes reutilizáveis
- Classes utilitárias do Tailwind CSS
- Estrutura semântica de HTML
- JavaScript modular
- Responsividade mobile-first

### Segurança
- Proteção CSRF
- Validação de inputs
- Autorização baseada em políticas
- Hash seguro de passwords
- Sanitização de dados

## Desenvolvimento

O projeto inclui scripts automatizados para facilitar o desenvolvimento, permitindo iniciar todos os serviços necessários simultaneamente (servidor Laravel, queue worker, log viewer e Vite dev server).

### Ferramentas Disponíveis

**Code Style:** Verificação e correção automática do estilo de código utilizando Laravel Pint

**Testes:** Suite completa de testes com PHPUnit

**Hot Reload:** Atualização automática durante o desenvolvimento com Vite

## Licença

Este projeto foi desenvolvido em contexto académico.
