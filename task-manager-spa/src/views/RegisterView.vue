<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'

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
    <div class="view-section">
        <form @submit.prevent="handleSubmit" class="view-sub-section">
            <h1 class="view-header">
                <span>CREATE </span>
                <span class="text-emerald-600">TASK MANAGER </span>
                <span>ACCOUNT</span>
            </h1>

            <div class="mt-10">
                <label class="form-label">Full Name</label>
                <input v-model="name" required placeholder="Gavin Belson" class="form-input" />
                <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p>
            </div>

            <div class="mt-5">
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

            <div class="mt-5">
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

            <div class="mt-5">
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

            <div class="flex justify-center mt-5">
                <button
                    type="submit"
                    :class="loading ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="loading">
                        <LoadingIcon />
                    </span>
                    <span>{{ loading ? 'Registering...' : 'Register' }}</span>
                </button>
            </div>

            <div class="text-emerald-600 font-medium m-5">
                <router-link :to="{ name: 'login' }"> Already have an account? Login </router-link>
            </div>
        </form>
    </div>
</template>
