<template>
  <div class="task-board">
    <!-- Formulário para criar nova tarefa -->
    <TaskForm 
      :project-id="projectId"
      @task-created="handleTaskCreated"
    />
    
    <!-- Loading state -->
    <div v-if="loading" class="loading">
      <p>Carregando tarefas...</p>
    </div>
    
    <!-- Board Kanban -->
    <div v-else class="board-columns">
      <TaskColumn
        v-for="status in statuses"
        :key="status"
        :status="status"
        :tasks="getTasksByStatus(status)"
        @update-task="handleUpdateTask"
        @delete-task="handleDeleteTask"
      />
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { projectService, taskService } from '../services/api';
import TaskForm from './TaskForm.vue';
import TaskColumn from './TaskColumn.vue';

export default {
  name: 'TaskBoard',
  
  components: {
    TaskForm,
    TaskColumn
  },
  
  props: {
    projectId: {
      type: Number,
      required: true
    }
  },
  
  setup(props) {
    const tasks = ref([]);
    const loading = ref(false);
    
    // Status fixos (hardcoded como pedido no desafio)
    const statuses = ['Pendente', 'Em Andamento', 'Concluído'];
    
    // Carrega tarefas do projeto
    const loadTasks = async () => {
      loading.value = true;
      try {
        const response = await projectService.getTasks(props.projectId);
        tasks.value = response.data;
      } catch (error) {
        console.error('Erro ao carregar tarefas:', error);
        alert('Erro ao carregar tarefas');
      } finally {
        loading.value = false;
      }
    };
    
    // Computed property: filtra tarefas por status
    const getTasksByStatus = (status) => {
      return tasks.value.filter(task => task.status === status);
    };
    
    // Handler: quando criar nova tarefa
    const handleTaskCreated = (newTask) => {
      tasks.value.unshift(newTask); // Adiciona no início do array
    };
    
    // Handler: quando atualizar tarefa (mudar status)
    const handleUpdateTask = async (taskId, newStatus) => {
      try {
        const response = await taskService.update(taskId, {
          status: newStatus
        });
        
        // Atualiza a tarefa no array local
        const index = tasks.value.findIndex(t => t.id === taskId);
        if (index !== -1) {
          tasks.value[index] = response.data;
        }
      } catch (error) {
        console.error('Erro ao atualizar tarefa:', error);
        alert('Erro ao atualizar tarefa');
      }
    };
    
    // Handler: quando deletar tarefa
    const handleDeleteTask = async (taskId) => {
      if (!confirm('Tem certeza que deseja excluir esta tarefa?')) {
        return;
      }
      
      try {
        await taskService.delete(taskId);
        
        // Remove a tarefa do array local
        tasks.value = tasks.value.filter(t => t.id !== taskId);
      } catch (error) {
        console.error('Erro ao deletar tarefa:', error);
        alert('Erro ao deletar tarefa');
      }
    };
    
    // Carrega tarefas quando o componente é montado
    onMounted(() => {
      loadTasks();
    });
    
    return {
      tasks,
      loading,
      statuses,
      getTasksByStatus,
      handleTaskCreated,
      handleUpdateTask,
      handleDeleteTask
    };
  }
};
</script>

<style scoped>
.task-board {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.loading {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 200px;
  color: #999;
  font-size: 1.1rem;
}

.board-columns {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-top: 1.5rem;
  flex: 1;
  overflow-x: auto;
  padding-bottom: 1rem;
}

/* Responsividade */
@media (max-width: 1024px) {
  .board-columns {
    grid-template-columns: 1fr;
  }
}
</style>