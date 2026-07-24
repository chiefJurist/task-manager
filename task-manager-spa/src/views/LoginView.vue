<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { RouterLink } from 'vue-router'

const auth = useAuthStore()

const email = ref('')
const password = ref('')
const errorMessage = ref({})
const loading = ref(false)

async function handleSubmit() {
    errorMessage.value = ''
    loading.value = true

    try {
        await auth.login(email.value, password.value)
    } catch (error) {
        errorMessage.value = error.response.data.errors ?? {}
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 py-10">
        <form @submit.prevent="handleSubmit" class="mx-auto p-6 bg-white rounded shadow md:w-xl">
            <h1 class="text-xl font-semibold italic text-center text-gray-800 pt-10 py-5">
                <span>Log in To </span>
                <span class="text-blue-600">Task </span>
                <span>Manager</span>
            </h1>

            <div class="my-5">
                <label class="text-gray-700 font-semibold">Email</label>
                <input
                    v-model="email"
                    type="email"
                    required
                    placeholder="gavinbelson@gmail.com"
                    class="outline-none p-3 w-full rounded-lg ring ring-blue-500 focus:ring-2 focus:bg-blue-50"
                />
                <p v-if="errorMessage.email" class="text-sm text-red-600 mt-1">
                    {{ errorMessage.email[0] }}
                </p>
            </div>

            <div class="my-5">
                <label class="text-gray-700 font-semibold">Password</label>
                <input
                    v-model="password"
                    type="password"
                    required
                    placeholder="hoolipassword123"
                    class="outline-none p-3 w-full rounded-lg ring ring-blue-500 focus:ring-2 focus:bg-blue-50"
                />
                <p v-if="errorMessage.password" class="text-sm text-red-600 mt-1">
                    {{ errorMessage.password[0] }}
                </p>
            </div>

            <p v-if="errorMessage.invalid" class="font-medium text-center text-red-600 mt-1">
                {{ errorMessage.invalid[0] }}
            </p>

            <div class="flex justify-center my-10">
                <button
                    type="submit"
                    :disabled="loading"
                    class="min-w-2xs p-3 bg-blue-600 hover:bg-blue-400 text-white font-semibold rounded-lg cursor-pointer brand-transition"
                >
                    {{ loading ? 'Logging in...' : 'Log in' }}
                </button>
            </div>

            <div class="text-blue-600 font-medium mt-10 mb-5 grid grid-cols-1 gap-2 md:grid-cols-2">
                <router-link :to="{ name: 'register' }">
                    Don't have an account yet? Register
                </router-link>
                <router-link :to="{ name: 'forgot-password' }" class="md:text-right">
                    Forgot Password
                </router-link>
            </div>
        </form>
    </div>
</template>
