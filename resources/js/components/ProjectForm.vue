<template>
 <div class="project-form">
  <h3> Novo Projeto </h3>
  <form @submit.prevent="handleSubmit">
   <input
    v-model="projectName"
    type="text"
    placeholder="Nome do projeto"
    class="form-input"
    required
 />
 <button type="submit" class="btn-primary" :disabled="loading">
    {{ loading ? 'Criando...' : 'Criar Projeto' }}
 </button>
 <p v-if="error" class="error-message">{{ error }}</p>
</form>
</div>
</template>

<script>
import { ref } from 'vue';
import { projectService } from '../services/api';

export default {
    name: 'ProjectForm',

    emits: ['project-created'],

    setup(props, { emit }) {
    const projectName = ref('');
    const loading = ref(false);
    const error = ref('');
    
    const handleSubmit = async () => {
      if (!projectName.value.trim()) {
        error.value = 'Digite um nome para o projeto';
        return;
      }
      
      loading.value = true;
      error.value = '';
      
      try {
        const response = await projectService.create({
          name: projectName.value
        });
        
        // Emite evento para o componente pai
        emit('project-created', response.data);
        
        // Limpa o formulário
        projectName.value = '';
      } catch (err) {
        if (err.response?.data?.errors?.name) {
          error.value = err.response.data.errors.name[0];
        } else {
          error.value = 'Erro ao criar projeto';
        }
      } finally {
        loading.value = false;
      }
    };
    
    return {
      projectName,
      loading,
      error,
      handleSubmit
    };
  }
};
</script>

<style scoped>
.project-form {
  padding: 1.5rem;
  border-bottom: 2px solid #f0f0f0;
}

.project-form h3 {
  margin: 0 0 1rem 0;
  font-size: 1.1rem;
  color: #333;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 0.95rem;
  margin-bottom: 0.75rem;
  transition: border-color 0.3s;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
}

.btn-primary {
  width: 100%;
  padding: 0.75rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-message {
  color: #e74c3c;
    font-size: 0.85rem;
  margin-top: 0.5rem;
  margin-bottom: 0;
}
</style>