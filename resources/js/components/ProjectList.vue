<template>
  <div class="project-list">
    <h3>📁 Meus Projetos</h3>
    
    <div v-if="projects.length === 0" class="empty-list">
      <p>Nenhum projeto ainda.<br>Crie um acima!</p>
    </div>
    
    <ul v-else class="projects">
      <li
        v-for="project in projects"
        :key="project.id"
        :class="['project-item', { active: project.id === selectedProjectId }]"
        @click="$emit('select-project', project.id)"
      >
        <span class="project-name">{{ project.name }}</span>
        <span class="task-count">{{ project.tasks_count || 0 }} tarefas</span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'ProjectList',
  
  props: {
    projects: {
      type: Array,
      required: true
    },
    selectedProjectId: {
      type: Number,
      default: null
    }
  },
  
  emits: ['select-project']
};
</script>

<style scoped>
.project-list {
  padding: 1.5rem;
  flex: 1;
  overflow-y: auto;
}

.project-list h3 {
  margin: 0 0 1rem 0;
  font-size: 1.1rem;
  color: #333;
}

.empty-list {
  text-align: center;
  color: #999;
  padding: 2rem 1rem;
  font-size: 0.9rem;
  line-height: 1.6;
}

.projects {
  list-style: none;
  padding: 0;
  margin: 0;
}

.project-item {
  padding: 1rem;
  margin-bottom: 0.5rem;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s;
  border: 2px solid transparent;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.project-item:hover {
  background-color: #f8f9fa;
  border-color: #e0e0e0;
}

.project-item.active {
  background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
  border-color: #667eea;
}

.project-name {
  font-weight: 600;
  color: #333;
  flex: 1;
}

.task-count {
  font-size: 0.85rem;
  color: #999;
  background: #f0f0f0;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
}

.project-item.active .task-count {
  background: #667eea;
  color: white;
}
</style>