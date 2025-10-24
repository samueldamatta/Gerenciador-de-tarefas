<template>
  <div class="app-container">
    <!-- Cabeçalho -->
    <header class="app-header">
      <h1>📋 Gerenciador de Tarefas</h1>
    </header>

    <div class="app-content">
      <!-- Sidebar com lista de projetos -->
      <aside class="sidebar">
        <ProjectForm @project-created="handleProjectCreated" />
        <ProjectList 
          :projects="projects"
          :selected-project-id="selectedProjectId"
          @select-project="handleSelectProject"
        />
      </aside>

      <!-- Área principal com o board de tarefas -->
      <main class="main-content">
        <TaskBoard 
          v-if="selectedProjectId"
          :project-id="selectedProjectId"
          :key="selectedProjectId"
        />
        <div v-else class="empty-state">
          <p>👈 Selecione um projeto para ver suas tarefas</p>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { projectService } from './services/api';
import ProjectList from './components/ProjectList.vue';
import ProjectForm from './components/ProjectForm.vue';
import TaskBoard from './components/TaskBoard.vue';

export default {
  name: 'App',
  
  components: {
    ProjectList,
    ProjectForm,
    TaskBoard
  },
  
  setup() {
    // Estado reativo
    const projects = ref([]); // Lista de projetos
    const selectedProjectId = ref(null); // ID do projeto selecionado
    
    // Carrega projetos ao montar o componente
    const loadProjects = async () => {
      try {
        const response = await projectService.getAll();
        projects.value = response.data;
      } catch (error) {
        console.error('Erro ao carregar projetos:', error);
        alert('Erro ao carregar projetos. Verifique se o backend está rodando.');
      }
    };
    
    // Handler: quando criar novo projeto
    const handleProjectCreated = (newProject) => {
      projects.value.push(newProject);
      selectedProjectId.value = newProject.id;
    };
    
    // Handler: quando selecionar um projeto
    const handleSelectProject = (projectId) => {
      selectedProjectId.value = projectId;
    };
    
    // Lifecycle hook: executa quando o componente é montado
    onMounted(() => {
      loadProjects();
    });
    
    // Expõe para o template
    return {
      projects,
      selectedProjectId,
      handleProjectCreated,
      handleSelectProject
    };
  }
};
</script>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #f5f5f5;
}

.app-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: purple;
  padding: 1.5rem 2rem;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.app-header h1 {
  margin: 0;
  font-size: 1.8rem;
  color: white;
}

.app-content {
  display: flex;
  flex: 1;
  overflow: hidden;
}

.sidebar {
  width: 300px;
  background: white;
  border-right: 1px solid #e0e0e0;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  overflow-y: auto;
  padding: 2rem;
}

.empty-state {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  font-size: 1.2rem;
  color: #999;
}
</style>