<template>
  <div class="task-column">
    <div class="column-header">
      <h3>
        <span class="status-icon">{{ getStatusIcon(status) }}</span>
        {{ status }}
      </h3>
      <span class="task-counter">{{ tasks.length }}</span>
    </div>
    
    <div class="column-content">
      <div v-if="tasks.length === 0" class="empty-column">
        <p>Nenhuma tarefa</p>
      </div>
      
      <TaskCard
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        :statuses="statuses"
        @update-status="handleUpdateStatus"
        @delete="handleDelete"
      />
    </div>
  </div>
</template>

<script>
import TaskCard from './TaskCard.vue';

export default {
  name: 'TaskColumn',
  
  components: {
    TaskCard
  },
  
  props: {
    status: {
      type: String,
      required: true
    },
    tasks: {
      type: Array,
      required: true
    }
  },
  
  emits: ['update-task', 'delete-task'],
  
  setup(props, { emit }) {
    // Status disponíveis para mover tarefas
    const statuses = ['Pendente', 'Em Andamento', 'Concluída'];
    
    // Retorna emoji baseado no status
    const getStatusIcon = (status) => {
      const icons = {
        'Pendente': '📝',
        'Em Andamento': '⚡',
        'Concluída': '✅'
      };
      return icons[status] || '📋';
    };
    
    const handleUpdateStatus = (taskId, newStatus) => {
      emit('update-task', taskId, newStatus);
    };
    
    const handleDelete = (taskId) => {
      emit('delete-task', taskId);
    };
    
    return {
      statuses,
      getStatusIcon,
      handleUpdateStatus,
      handleDelete
    };
  }
};
</script>

<style scoped>
.task-column {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  min-height: 400px;
  max-height: calc(100vh - 250px);
}

.column-header {
  padding: 1.25rem;
  border-bottom: 2px solid #f0f0f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 12px 12px 0 0;
}

.column-header h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #333;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.status-icon {
  font-size: 1.3rem;
}

.task-counter {
  background: #667eea;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 600;
}

.column-content {
  padding: 1rem;
  overflow-y: auto;
  flex: 1;
}

.empty-column {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 200px;
  color: #ccc;
  font-size: 0.95rem;
}
</style>