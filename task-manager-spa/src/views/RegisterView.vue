<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const errorMessage = ref({})
const loading = ref(false)

async function handleSubmit() {
    errorMessage.value = ''
    loading.value = true

    try {
        await auth.register(name.value, email.value, password.value, password_confirmation.value)
    } catch (error) {
        errorMessage.value = error.response.data.errors ?? {}
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 px-2 py-10">
        <form @submit.prevent="handleSubmit" class="mx-auto p-6 bg-white rounded shadow md:w-lg">
            <h1 class="text-xl font-semibold italic text-center text-gray-800">
                <span>Create Your </span>
                <span class="text-blue-600">Task </span>
                <span>Manager Account</span>
            </h1>

            <div class="my-5">
                <label class="form-label">Full Name</label>
                <input v-model="name" required placeholder="Gavin Belson" class="form-input" />
                <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p>
            </div>

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
                    placeholder="input your password here"
                    class="form-input"
                />
                <p v-if="errorMessage.password" class="form-error">
                    {{ errorMessage.password[0] }}
                </p>
            </div>

            <div class="my-5">
                <label class="form-label">Password</label>
                <input
                    v-model="password_confirmation"
                    type="password"
                    required
                    placeholder="confirm your password input"
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
                <button
                    type="submit"
                    :class="loading ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <img
                        v-if="loading"
                        src="../assets/images/mingcute--loading-fill.svg"
                        class="animate-spin inline mr-2"
                    />
                    {{ loading ? 'Registering...' : 'Register' }}
                </button>
            </div>

            <div class="text-blue-600 font-medium mt-10 mb-5">
                <router-link :to="{ name: 'login' }"> Already have an account? Login </router-link>
            </div>
        </form>
    </div>
</template>
