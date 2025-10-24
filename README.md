# 📋 Gerenciador de Tarefas - Laravel + Vue.js

Sistema SPA para gerenciamento de projetos e tarefas com visualização Kanban.

## 🚀 Tecnologias

- **Backend:** Laravel 10.x
- **Frontend:** Vue.js 3 (Composition API)
- **Banco de Dados:** Sqlite
- **Build Tool:** Vite
- **HTTP Client:** Axios

## 📦 Instalação

### Pré-requisitos

- PHP 8.1+
- Composer
- Node.js 16+
- MySQL

### Passo a Passo

1. **Clone o repositório**
```bash
git clone <seu-repositorio>
cd task-manager
```

2. **Instale as dependências do PHP**
```bash
composer install
```

3. **Instale as dependências do Node**
```bash
npm i
```

4. **Configure o arquivo .env**
```bash
cp .env.example .env
```

Edite o `.env` com suas credenciais de banco:
```env
DB_DATABASE=task_manager
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. **Gere a chave da aplicação**
```bash
php artisan key:generate
```

6. **Crie seu banco de dados de preferênica**

7. **Execute as migrations**
```bash
php artisan migrate
```

8. **Inicie os servidores**

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

9. **Acesse a aplicação**
```
http://localhost:8000
```

## 📐 Estrutura da API

### Projetos

- `GET /api/projects` - Lista todos os projetos
- `POST /api/projects` - Cria novo projeto
```json
  {
    "name": "Nome do Projeto"
  }
```
- `GET /api/projects/{id}/tasks` - Lista tarefas de um projeto
- 'PUT /api/projects/{project}' - Atualiza um projeto
- 'DELETE api/projects/{project}' - Deleta um projeto

### Tarefas

- `POST /api/tasks` - Cria nova tarefa
```json
  {
    "titulo": "Título da Tarefa",
    "descricao": "Descrição opcional",
    "project_id": 1,
    "status": "Pendente"
  }
```
- `PUT /api/tasks/{id}` - Atualiza tarefa
```json
  {
    "status": "Em Andamento"
  }
```
- `DELETE /api/tasks/{id}` - Deleta tarefa

## 🎯 Funcionalidades

- ✅ Criar e listar projetos
- ✅ Criar tarefas vinculadas a projetos
- ✅ Visualização Kanban (Pendente, Em Andamento, Concluído)
- ✅ Mover tarefas entre status
- ✅ Excluir tarefas
- ✅ Contador de tarefas por projeto
- ✅ Interface responsiva
- ✅ Validações no frontend e backend
- ✅ Tratamento de erros

## 💭 Respostas de Reflexão

### Pergunta 1: Qual foi a maior dificuldade que você encontrou neste desafio e como você a resolveu?

A maior dificuldade foi **gerenciar o estado reativo entre componentes** no Vue 3. Especificamente, manter a lista de tarefas sincronizada após operações CRUD (criar, atualizar, deletar).

**Solução:** Implementei um padrão de comunicação clara entre componentes usando:
- **Props** para passar dados do pai para filhos (top-down)
- **Emits** para notificar o pai sobre mudanças (bottom-up)
- **Manipulação do array reativo** no componente TaskBoard, que centraliza o estado das tarefas

Isso garantiu uma única fonte de verdade (single source of truth), evitando inconsistências na UI.

Além disso, tive que lidar com a **sincronização entre frontend e backend**, garantindo que após cada operação (criar, atualizar, deletar) a interface refletisse imediatamente o estado correto. Para isso:
- Utilizei `refresh()` e `load()` do Eloquent para recarregar dados atualizados
- Implementei tratamento de erros consistente em todas as requisições HTTP
- Adicionei loading states para melhorar a experiência do usuário durante operações assíncronas

### Pergunta 2: Se você tivesse mais 4 horas para trabalhar neste projeto, o que você melhoraria ou adicionaria?

Priorizaria as seguintes melhorias:

1. **Drag & Drop entre colunas** (2h)
   - Implementaria usando a biblioteca `vue-draggable-next`
   - Permitiria arrastar tarefas entre as colunas para mudar o status de forma mais intuitiva
   - Melhoraria significativamente a UX do board Kanban

2. **Edição inline de tarefas** (1h)
   - Permitir editar título e descrição clicando diretamente no card
   - Modal ou formulário inline sem necessidade de recarregar a página
   - Validação em tempo real

3. **Sistema de notificações (Toast)** (30min)
   - Feedback visual para todas as ações (criar, atualizar, deletar)
   - Substituir `alert()` por notificações elegantes
   - Notificações de sucesso, erro e informação

4. **Filtros e busca** (30min)
   - Busca de tarefas por título/descrição
   - Filtros por status, data de criação
   - Ordenação customizável

**Melhorias técnicas:**
- Implementar testes automatizados (PHPUnit no backend, Vitest no frontend)
- Adicionar paginação para projetos/tarefas
- Loading skeletons em vez de spinners simples
- Sistema de autenticação (Laravel Sanctum)
- Dark mode
- Implementação do bônus (status dinâmicos com tabela no banco)
- Deploy com Docker para facilitar distribuição

### Pergunta 3: Qual abordagem você usou para gerenciar o estado no Vue e por que você escolheu essa abordagem?

Optei por **gerenciamento de estado local com Composition API** sem usar Pinia/Vuex, pelos seguintes motivos:

**Abordagem escolhida:**
- Estado armazenado no componente mais alto que precisa dele:
  - `App.vue` gerencia a lista de projetos e qual projeto está selecionado
  - `TaskBoard.vue` gerencia a lista de tarefas do projeto atual
- Comunicação via props (dados fluem de pai para filho)
- Comunicação via emits (eventos sobem de filho para pai)
- Uso de `ref()` para valores primitivos (números, strings, booleans)
- Uso de `reactive()` para objetos complexos (formulários)

**Estrutura de fluxo de dados:**
```
App.vue
  ├─> projects (ref)           // Estado: lista de projetos
  ├─> selectedProjectId (ref)  // Estado: projeto selecionado
  │
  └─> TaskBoard.vue
      ├─> tasks (ref)          // Estado: lista de tarefas
      └─> showTaskForm (ref)   // Estado: visibilidade do form
```

**Justificativa da escolha:**

1. **Simplicidade e Clareza**
   - Para uma aplicação de pequeno/médio porte (2 entidades principais), um store global adiciona complexidade desnecessária
   - O fluxo de dados é previsível e fácil de debugar
   - Menos boilerplate code

2. **Colocation (Proximidade)**
   - Estado fica próximo dos componentes que o usam
   - Facilita entendimento: basta olhar o componente para saber o que ele gerencia
   - Manutenção mais fácil

3. **Performance**
   - Menos overhead de bibliotecas externas
   - Re-renderizações mais previsíveis e controladas
   - Componentes se atualizam apenas quando seus dados próprios mudam

4. **Demonstração de Conhecimento**
   - Mostra compreensão profunda dos fundamentos do Vue
   - Entendimento de reatividade, props, emits e lifecycle hooks
   - Capacidade de tomar decisões arquiteturais adequadas ao contexto

**Quando usaria store global (Pinia):**
- Aplicação com múltiplas rotas/páginas compartilhando estado
- Estado complexo com muitas interações entre entidades
- Necessidade de persistência (localStorage, sessionStorage)
- Time grande que se beneficiaria de padronização rigorosa
- Features como histórico de ações (undo/redo)
- Necessidade de acessar estado em qualquer lugar da aplicação

**Alternativa considerada:**
Poderia ter usado o padrão "Provide/Inject" do Vue para evitar prop drilling, mas neste caso não foi necessário pois a hierarquia de componentes é rasa (máximo 3 níveis).

## 📝 Estrutura de Componentes
```
App.vue (root)
├── ProjectForm.vue         # Formulário para criar novos projetos
├── ProjectList.vue         # Lista lateral de projetos (sidebar)
└── TaskBoard.vue           # Board Kanban principal
    ├── TaskForm.vue        # Formulário para criar novas tarefas
    └── TaskColumn.vue      # Coluna do Kanban (x3: Pendente, Em Andamento, Concluído)
        └── TaskCard.vue    # Card individual de tarefa (xN)
```

**Responsabilidades de cada componente:**

- **App.vue**: Orquestrador principal, gerencia projetos e projeto selecionado
- **ProjectForm.vue**: Entrada de dados para novos projetos, emite evento quando criado
- **ProjectList.vue**: Exibe lista de projetos, destaca o selecionado, emite evento de seleção
- **TaskBoard.vue**: Gerencia todas as tarefas do projeto selecionado, orquestra CRUD
- **TaskForm.vue**: Entrada de dados para novas tarefas, validação local
- **TaskColumn.vue**: Agrupa tarefas por status, exibe contador
- **TaskCard.vue**: Exibe detalhes da tarefa, menu de ações (mover/deletar)

## 🎨 Padrões de Código Utilizados

### Backend (Laravel)

**Arquitetura MVC com camadas de abstração:**
- **Controllers magros**: Apenas recebem requisições e delegam lógica
- **Form Requests**: Validação isolada e reutilizável
- **Models eloquent**: Relacionamentos e lógica de negócio
- **Route Model Binding**: Simplifica controllers e melhora legibilidade

**Exemplos de boas práticas aplicadas:**
```php
// ✅ Controller magro
public function store(StoreProjectRequest $request): JsonResponse
{
    $project = Project::create($request->validated());
    return response()->json($project, 201);
}

// ✅ Eager Loading para evitar N+1
$projects = Project::withCount('tasks')->get();

// ✅ Relacionamentos Eloquent
public function tasks() {
    return $this->hasMany(Task::class);
}
```

### Frontend (Vue.js)

**Composition API com padrão de organização clara:**
- **Setup function**: Centraliza toda lógica reativa do componente
- **Componentes pequenos e focados**: Single Responsibility Principle
- **Props tipadas**: Validação de tipos e valores default
- **Emits declarados**: Documentação explícita de eventos
- **Estilos scoped**: Evita conflitos de CSS

**Exemplos de boas práticas aplicadas:**
```javascript
// ✅ Props com validação
props: {
  projectId: {
    type: Number,
    required: true
  }
}

// ✅ Computed para valores derivados
const otherStatuses = computed(() => {
  return availableStatuses.filter(s => s !== task.status);
});

// ✅ Nomenclatura clara de handlers
const handleProjectCreated = (newProject) => {
  projects.value.push(newProject);
};
```

**Padrão de nomenclatura:**
- Componentes: PascalCase (TaskCard.vue)
- Funções: camelCase (handleSubmit)
- Constantes: UPPER_CASE
- Props/Emits: kebab-case no template, camelCase no script

## 🔒 Segurança e Validação

### Backend
- ✅ Validação de todos os inputs (Form Requests)
- ✅ CORS configurado adequadamente
- ✅ Unique constraints no banco (nome de projeto)
- ✅ Foreign keys com cascade/restrict
- ✅ Mass assignment protection ($fillable)

### Frontend
- ✅ Validação local antes de enviar ao servidor
- ✅ Tratamento de erros HTTP
- ✅ Feedback visual para todas as ações
- ✅ Confirmação antes de ações destrutivas (deletar)

## 🐛 Troubleshooting

### Erro: "CORS Policy blocked"
**Causa:** Backend não está permitindo requisições do frontend.
**Solução:** 
```php
// config/cors.php
'allowed_origins' => ['http://localhost:5173'],
```

### Erro: "Connection Refused" ao chamar API
**Causa:** Servidor Laravel não está rodando.
**Solução:** Execute `php artisan serve` em um terminal separado.

### Erro: "npm ERR! missing script: dev"
**Causa:** Dependências do Node não foram instaladas.
**Solução:** Execute `npm install` e depois `npm run dev`.

### Erro: "SQLSTATE[HY000] [1045] Access denied"
**Causa:** Credenciais incorretas no arquivo `.env`.
**Solução:** Verifique DB_USERNAME e DB_PASSWORD no arquivo `.env`.

### Erro: "Base table or view not found"
**Causa:** Migrations não foram executadas.
**Solução:** Execute `php artisan migrate`.

### Erro: "Call to undefined method"
**Causa:** Cache do Laravel desatualizado.
**Solução:** 
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Frontend não atualiza após mudanças no código
**Causa:** Vite não está observando arquivos.
**Solução:** Reinicie `npm run dev` e limpe o cache do navegador (Ctrl+Shift+R).

## 🧪 Testando a Aplicação

### Testes Manuais

**Fluxo básico de teste:**

1. **Criar Projeto**
   - Abra a aplicação
   - Digite um nome no campo "Novo Projeto"
   - Clique em "Criar Projeto"
   - Verifique se aparece na lista lateral

2. **Criar Tarefa**
   - Selecione um projeto na lista
   - Clique em "➕ Nova Tarefa"
   - Preencha título e descrição
   - Clique em "Criar Tarefa"
   - Verifique se aparece na coluna "Pendente"

3. **Mover Tarefa**
   - Clique no menu (⋮) de uma tarefa
   - Clique em "Em Andamento"
   - Verifique se a tarefa mudou de coluna

4. **Excluir Tarefa**
   - Clique no menu (⋮) de uma tarefa
   - Clique em "🗑️ Excluir Tarefa"
   - Confirme a exclusão
   - Verifique se a tarefa foi removida

### Testes com cURL
```bash
# Criar projeto
curl -X POST http://localhost:8000/api/projects \
  -H "Content-Type: application/json" \
  -d '{"name":"Projeto Teste"}'

# Listar projetos
curl http://localhost:8000/api/projects

# Criar tarefa
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"titulo":"Minha Tarefa","project_id":1,"status":"Pendente"}'

# Atualizar tarefa
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"status":"Em Andamento"}'

# Deletar tarefa
curl -X DELETE http://localhost:8000/api/tasks/1
```

## 📚 Recursos e Documentação

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/guide/introduction.html)
- [Vite Documentation](https://vitejs.dev/guide/)
- [Axios Documentation](https://axios-http.com/docs/intro)
- [Tailwind CSS](https://tailwindcss.com/docs) (se implementado)

## 🎯 Objetivos de Aprendizado Alcançados

Este projeto demonstra conhecimento em:

### Backend
- ✅ Criação de APIs RESTful com Laravel
- ✅ Relacionamentos Eloquent (1:N)
- ✅ Migrations e estruturação de banco de dados
- ✅ Validação com Form Requests
- ✅ Route Model Binding
- ✅ CORS e comunicação frontend-backend

### Frontend
- ✅ Vue 3 Composition API
- ✅ Gerenciamento de estado reativo
- ✅ Comunicação entre componentes (props/emits)
- ✅ Requisições HTTP assíncronas
- ✅ Lifecycle hooks
- ✅ Componentização e reutilização

### Boas Práticas
- ✅ Versionamento com Git (commits semânticos)
- ✅ Separação de responsabilidades
- ✅ Código limpo e legível
- ✅ Tratamento de erros
- ✅ Validação em múltiplas camadas
- ✅ Documentação clara

## 📄 Licença

Este projeto foi desenvolvido como desafio técnico para fins de avaliação.

## 👨‍💻 Autor

Desenvolvido como parte de um processo seletivo.

---

**Desenvolvido com ❤️ usando Laravel e Vue.js**