<template>
  <div 
    class="task-card"
    draggable="true"
    @dragstart="handleDragStart"
    @dragend="handleDragEnd"
  >
    <div class="task-header">
      <h4>{{ task.titulo }}</h4>
      <button 
        class="delete-btn" 
        @click="$emit('delete', task.id)"
        title="Excluir tarefa"
      >
        ×
      </button>
    </div>
    <p v-if="task.descricao" class="task-description">
      {{ task.descricao }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'TaskCard',
  
  props: {
    task: {
      type: Object,
      required: true
    }
  },
  
  emits: ['delete'],
  
  setup(props, { emit }) {
    const handleDragStart = (event) => {
      // Armazena o ID da tarefa e o status atual
      event.dataTransfer.effectAllowed = 'move';
      event.dataTransfer.setData('taskId', props.task.id);
      event.dataTransfer.setData('currentStatus', props.task.status);
      
      // Adiciona classe visual durante o arraste
      event.target.classList.add('dragging');
    };
    
    const handleDragEnd = (event) => {
      // Remove classe visual após o arraste
      event.target.classList.remove('dragging');
    };
    
    return {
      handleDragStart,
      handleDragEnd
    };
  }
};
</script>

<style scoped>
.task-card {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 0.75rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  cursor: move;
  transition: all 0.2s ease;
}

.task-card:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  transform: translateY(-2px);
}

.task-card.dragging {
  opacity: 0.5;
  cursor: grabbing;
}

.task-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.5rem;
}

.task-header h4 {
  margin: 0;
  font-size: 1rem;
  color: #333;
  flex: 1;
}

.delete-btn {
  background: transparent;
  border: none;
  color: #999;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: all 0.2s ease;
}

.delete-btn:hover {
  background: #fee;
  color: #f44;
}

.task-description {
  font-size: 0.9rem;
  color: #666;
  margin: 0;
  line-height: 1.4;
}
</style>