import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../lib/axios'
import router from '../router/index'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const projects = ref([])
    const tasks = ref([])
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
        try {
            const response = await api.get('/api/tasks')
            tasks.value = response.data.data
        } catch (error) {
            toastMessage.value =
                error.response?.data?.message ?? 'Check Internet Connection and Try Again'
        }
    }

    async function createTask(name, project_id) {
        const response = await api.post(`/api/tasks/`, { name, project_id })
        await fetchTasks()
        toastMessage.value = response.data.message
    }

    async function editTask(id, name, project_id, status) {
        try {
            const response = await api.put(`/api/tasks/${id}`, { name, project_id, status })
            const updatedTask = response.data.data
            const index = tasks.value.findIndex((t) => t.id === id)
            tasks.value[index] = updatedTask
            toastMessage.value = 'Task updated successfully'
        } catch (error) {
            toastMessage.value =
                error.response?.data?.message ?? 'Check internet connection and try again'
        }
    }

    async function deleteTask(id) {
        try {
            const response = await api.delete(`/api/tasks/${id}`)
            tasks.value = tasks.value.filter((t) => t.id !== id)
            toastMessage.value = response.data.message
        } catch (error) {
            toastMessage.value =
                error.response?.data?.message ?? 'Check internet connection and try again'
        }
    }

    return {
        user,
        initialized,
        projects,
        tasks,
        toastMessage,
        register,
        login,
        logout,
        fetchUser,
        fetchProjects,
        fetchTasks,
        createTask,
        editTask,
        deleteTask,
    }
})
