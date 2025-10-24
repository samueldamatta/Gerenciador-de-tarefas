<template>
  <div class="task-form">
    <button @click="showForm = !showForm" class="btn-toggle">
      {{ showForm ? '✕ Cancelar' : '➕ Nova Tarefa' }}
    </button>
    
    <form v-if="showForm" @submit.prevent="handleSubmit" class="form">
      <div class="form-group">
        <label for="titulo">Título *</label>
        <input
          id="titulo"
          v-model="formData.titulo"
          type="text"
          placeholder="Digite o título da tarefa..."
          required
        />
      </div>
      
      <div class="form-group">
        <label for="descricao">Descrição</label>
        <textarea
          id="descricao"
          v-model="formData.descricao"
          placeholder="Descrição opcional..."
          rows="3"
        ></textarea>
      </div>
      
      <button type="submit" class="btn-submit" :disabled="loading">
        {{ loading ? 'Criando...' : 'Criar Tarefa' }}
      </button>
      
      <p v-if="error" class="error">{{ error }}</p>
    </form>
  </div>
</template>

<script>
import { ref, reactive } from 'vue';
import { taskService } from '../services/api';

export default {
  name: 'TaskForm',
  
  props: {
    projectId: {
      type: Number,
      required: true
    }
  },
  
  emits: ['task-created'],
  
  setup(props, { emit }) {
    const showForm = ref(false);
    const loading = ref(false);
    const error = ref('');
    
    // reactive() para objetos (como ref() mas para objetos complexos)
    const formData = reactive({
      titulo: '',
      descricao: ''
    });
    
    const handleSubmit = async () => {
      loading.value = true;
      error.value = '';
      
      try {
        const response = await taskService.create({
          titulo: formData.titulo,
          descricao: formData.descricao,
          project_id: props.projectId,
          status: 'Pendente' // Status inicial
        });
        
        // Emite evento com a nova tarefa
        emit('task-created', response.data);
        
        // Reseta o formulário
        formData.titulo = '';
        formData.descricao = '';
        showForm.value = false;
      } catch (err) {
        console.error('Erro ao criar tarefa:', err);
        if (err.response?.data?.errors) {
          const errors = err.response.data.errors;
          error.value = Object.values(errors).flat().join(', ');
        } else {
          error.value = 'Erro ao criar tarefa';
        }
      } finally {
        loading.value = false;
      }
    };
    
    return {
      showForm,
      loading,
      error,
      formData,
      handleSubmit
    };
  }
};
</script>

<style scoped>
.task-form {
  background: white;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-toggle {
  width: 100%;
  padding: 0.75rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-toggle:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.form {
  margin-top: 1rem;
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #333;
  font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 0.95rem;
  font-family: inherit;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #667eea;
}

.form-group textarea {
  resize: vertical;
}

.btn-submit {
  width: 100%;
  padding: 0.75rem;
  background: #10b981;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-submit:hover:not(:disabled) {
  background: #059669;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error {
  color: #e74c3c;
  font-size: 0.85rem;
  margin-top: 0.75rem;
  margin-bottom: 0;
}
</style>