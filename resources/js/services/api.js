import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

export const projectService = {
    getAll(){
        return api.get('/projects');
    },

    create(data){
        return api.post('/projects', data);
    },

    getTasks(projectId){
        return api.get(`/projects/${projectId}/tasks`);
    },

    updateProject(projectId, data){
        return api.put(`/projects/${projectId}`, data);
    },

    deleteProject(projectId){
        return api.delete(`/projects/${projectId}`);
    },
};

export const taskService = {
    create(data){
        return api.post('/tasks', data);
    },

    update(taskId, data){
        return api.put(`/tasks/${taskId}`, data);
    },

    delete(id){
        return api.delete(`/tasks/${id}`);
    }
};

export default api;
