import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../lib/axios'
import router from '../router/index'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)

    async function register(name, email, password, password_confirmation) {
        await api.get('/sanctum/csrf-cookie')
        const response = await api.post('/api/register', {
            name,
            email,
            password,
            password_confirmation,
        })
        user.value = response.data.data
        router.push({ name: 'dashboard' })
    }

    async function login(email, password) {
        await api.get('/sanctum/csrf-cookie')
        const response = await api.post('/api/login', { email, password })
        user.value = response.data.data
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
            user.value = response.data.data
        } catch (error) {
            user.value = null
        }
    }
    return { user, register, login, logout, fetchUser }
})
