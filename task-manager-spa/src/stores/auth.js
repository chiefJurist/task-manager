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
    const errorMessage = ref({})

    function setToast(msg, duration = 3000) {
        toastMessage.value = msg

        setTimeout(() => (toastMessage.value = ''), duration)
    }

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
        const response = await api.post('/api/logout')

        user.value = null
        tasks.value = []
        projects.value = []
        initialized.value = true

        setToast(response.data.message)

        await router.push({ name: 'login' })
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
        try {
            const response = await api.get('/api/projects')
            projects.value = response.data.data
        } catch (error) {
            setToast(error.response?.data?.message ?? 'Check Internet Connection and Try Again')
        }
    }

    async function fetchTasks() {
        try {
            const response = await api.get('/api/tasks')
            tasks.value = response.data.data
        } catch (error) {
            setToast(error.response?.data?.message ?? 'Check Internet Connection and Try Again')
        }
    }

    async function createProject(name) {
        const response = await api.post(`/api/projects/`, { name })
        const newProject = response.data.data
        projects.value.unshift(newProject)

        setToast('Project Created Successfully')
    }

    async function createTask(name, project_id) {
        const response = await api.post(`/api/tasks/`, { name, project_id })
        const newTask = response.data.data
        tasks.value.push(newTask)

        const project = projects.value.find((p) => p.id == project_id)
        if (project) {
            project.tasks.push(newTask)
        }

        setToast('Task Created Successfully')
    }

    async function editStatus(id, status) {
        const response = await api.patch(`/api/tasks/${id}`, { status })
        const updatedTask = response.data.data
        const index = tasks.value.findIndex((task) => task.id === id)
        tasks.value[index] = updatedTask
    }

    async function editProject(id, name) {
        const response = await api.put(`/api/projects/${id}`, { name })
        const updatedProject = response.data.data
        const index = projects.value.findIndex((project) => project.id === id)
        projects.value[index] = updatedProject

        setToast('Project Updated Successfully')
    }

    async function editTask(id, name, project_id, status) {
        const oldTask = tasks.value.find((task) => task.id === id)
        const oldProjectId = oldTask?.project_id

        const response = await api.patch(`/api/tasks/${id}`, { name, project_id, status })
        const updatedTask = response.data.data
        const index = tasks.value.findIndex((task) => task.id === id)
        tasks.value[index] = updatedTask

        if (oldProjectId && oldProjectId != project_id) {
            const oldProject = projects.value.find((project) => project.id === oldProjectId)
            oldProject.tasks = oldProject.tasks.filter((task) => task.id !== id)
        }
        if (project_id && oldProjectId != project_id) {
            const newProject = projects.value.find((project) => project.id == project_id)
            newProject.tasks.push(updatedTask)
        }

        setToast('Task Updated Successfully')
    }

    async function deleteProject(id) {
        const response = await api.delete(`/api/projects/${id}`)
        projects.value = projects.value.filter((project) => project.id !== id)
        setToast(response.data.message)
    }

    async function deleteTask(id) {
        const oldTask = tasks.value.find((task) => task.id === id)
        const oldProjectId = oldTask?.project_id

        const response = await api.delete(`/api/tasks/${id}`)
        tasks.value = tasks.value.filter((task) => task.id !== id)

        if (oldProjectId) {
            const oldProject = projects.value.find((project) => project.id === oldProjectId)
            oldProject.tasks = oldProject.tasks.filter((task) => task.id !== id)
        }

        setToast(response.data.message)
    }

    async function reorderTasks(changed) {
        const previousOrder = tasks.value

        try {
            const response = await api.post('/api/tasks/reorder', { tasks: changed })
            tasks.value = response.data.data
        } catch (error) {
            tasks.value = previousOrder
            if (error.response?.status === 422) {
                errorMessage.value = error.response.data.errors ?? {}
            } else {
                await setToast(error.response?.data?.message ?? error)
            }
        }
    }

    return {
        user,
        initialized,
        projects,
        tasks,
        toastMessage,
        errorMessage,
        setToast,
        register,
        login,
        logout,
        fetchUser,
        fetchProjects,
        createProject,
        editProject,
        deleteProject,
        fetchTasks,
        createTask,
        editStatus,
        editTask,
        deleteTask,
        reorderTasks,
    }
})
