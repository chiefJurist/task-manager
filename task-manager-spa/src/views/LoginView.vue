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
                <label class="form-label">Email</label>
                <input
                    v-model="email"
                    type="email"
                    required
                    placeholder="gavinbelson@gmail.com"
                    class="form-input"
                />
                <p v-if="errorMessage.email" class="form-error">
                    {{ errorMessage.email[0] }}
                </p>
            </div>

            <div class="my-5">
                <label class="form-label">Password</label>
                <input
                    v-model="password"
                    type="password"
                    required
                    placeholder="hoolipassword123"
                    class="form-input"
                />
                <p v-if="errorMessage.password" class="form-error">
                    {{ errorMessage.password[0] }}
                </p>
            </div>

            <p v-if="errorMessage.invalid" class="font-medium text-center text-red-600 mt-1">
                {{ errorMessage.invalid[0] }}
            </p>

            <div class="flex justify-center my-10">
                <button type="submit" :class="loading ? 'form-loading' : 'form-before-loading'">
                    <img
                        v-if="loading"
                        src="../assets/images/mingcute--loading-fill.svg"
                        class="animate-spin inline mr-2"
                    />

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
