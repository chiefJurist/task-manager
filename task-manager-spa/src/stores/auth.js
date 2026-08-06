import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../lib/axios'
import router from '../router/index'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const projects = ref([])
    const tasks = ref([])
    const task = ref(null)
    const initialized = ref(false)
    const toastMessage = ref('')

    async function register(name, email, password, password_confirmation) {
        await api.get('/sanctum/csrf-cookie')
        const response = await api.post('/api/register', {
            name,
            email,
            password,
            password_confirmation,
        })
        user.value = response.data.data
        initialized.value = true
        router.push({ name: 'dashboard' })
    }

    async function login(email, password, remember) {
        await api.get('/sanctum/csrf-cookie')
        const response = await api.post('/api/login', { email, password, remember })
        user.value = response.data.data
        initialized.value = true
        await router.push({ name: 'dashboard' })
    }

    async function logout() {
        await api.post('/api/logout')
        user.value = null
        router.push({ name: 'login' })
    }

    async function fetchUser() {
        try {
            const response = await api.get('/api/user')
            user.value = response.data.user
        } catch (error) {
            user.value = null
        } finally {
            initialized.value = true
        }
    }

    async function fetchProjects() {
        const response = await api.get('/api/projects')
        projects.value = response.data.data
    }

    async function fetchTasks() {
        const response = await api.get('/api/tasks')
        tasks.value = response.data.data
    }

    async function fetchTask(id) {
        const response = await api.get(`/api/tasks/${id}`)
        task.value = response.data.data
    }

    async function createTask(name, project_id) {
        const response = await api.post(`/api/tasks/`, { name, project_id })
        await fetchTasks()
        toastMessage.value = response.data.message
    }

    async function editTask(id, name, project_id, status) {
        const response = await api.put(`/api/tasks/${id}`, { name, project_id, status })
        task.value = response.data.data
    }

    async function deleteTask(id) {
        const response = await api.delete(`/api/tasks/${id}`)
        await fetchTasks()
        toastMessage.value = response.data.message
    }

    return {
        user,
        initialized,
        projects,
        tasks,
        task,
        toastMessage,
        register,
        login,
        logout,
        fetchUser,
        fetchProjects,
        fetchTasks,
        fetchTask,
        createTask,
        editTask,
        deleteTask,
    }
})
