<template>
  <div class="task-card">
    <div class="card-header">
      <h4 class="task-title">{{ task.titulo }}</h4>
      <button @click="handleDelete" class="btn-delete" title="Excluir tarefa">
        🗑️
      </button>
    </div>
    
    <p v-if="task.descricao" class="task-description">
      {{ task.descricao }}
    </p>
    
    <div class="card-footer">
      <select 
        v-model="selectedStatus" 
        @change="handleStatusChange"
        class="status-select"
      >
        <option v-for="status in statuses" :key="status" :value="status">
          {{ status }}
        </option>
      </select>
      
      <span class="task-date" :title="formatDate(task.created_at)">
        {{ formatDateRelative(task.created_at) }}
      </span>
    </div>
  </div>
</template>

<script>
import { ref, watch } from 'vue';

export default {
  name: 'TaskCard',
  
  props: {
    task: {
      type: Object,
      required: true
    },
    statuses: {
      type: Array,
      required: true
    }
  },
  
  emits: ['update-status', 'delete'],
  
  setup(props, { emit }) {
    const selectedStatus = ref(props.task.status);
    
    // Watch: observa mudanças na prop task.status
    watch(() => props.task.status, (newStatus) => {
      selectedStatus.value = newStatus;
    });
    
    const handleStatusChange = () => {
      if (selectedStatus.value !== props.task.status) {
        emit('update-status', props.task.id, selectedStatus.value);
      }
    };
    
    const handleDelete = () => {
      emit('delete', props.task.id);
    };
    
    // Formata data completa
    const formatDate = (dateString) => {
      const date = new Date(dateString);
      return date.toLocaleString('pt-BR');
    };
    
    // Formata data relativa (ex: "2 horas atrás")
    const formatDateRelative = (dateString) => {
      const date = new Date(dateString);
      const now = new Date();
      const diffInSeconds = Math.floor((now - date) / 1000);
      
      if (diffInSeconds < 60) return 'agora';
      if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m atrás`;
      if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h atrás`;
      if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d atrás`;
      
      return date.toLocaleDateString('pt-BR');
    };
    
    return {
      selectedStatus,
      handleStatusChange,
      handleDelete,
      formatDate,
      formatDateRelative
    };
  }
};
</script>

<style scoped>
.task-card {
  background: #ffffff;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
  transition: all 0.3s;
}

.task-card:hover {
  border-color: #667eea;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  transform: translateY(-2px);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.75rem;
}

.task-title {
  margin: 0;
  font-size: 1rem;
  color: #333;
  flex: 1;
  line-height: 1.4;
}

.btn-delete {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  opacity: 0.5;
  transition: all 0.3s;
  padding: 0;
  margin-left: 0.5rem;
}

.btn-delete:hover {
  opacity: 1;
  transform: scale(1.2);
}

.task-description {
  color: #666;
  font-size: 0.9rem;
  line-height: 1.5;
  margin: 0 0 1rem 0;
  white-space: pre-wrap;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.75rem;
  padding-top: 0.75rem;
  border-top: 1px solid #f0f0f0;
}

.status-select {
  flex: 1;
  padding: 0.5rem;
  border: 2px solid #e0e0e0;
  border-radius: 6px;
  font-size: 0.85rem;
  cursor: pointer;
  transition: border-color 0.3s;
  background: white;
}

.status-select:focus {
  outline: none;
  border-color: #667eea;
}

.task-date {
  font-size: 0.75rem;
  color: #999;
  white-space: nowrap;
}
</style>