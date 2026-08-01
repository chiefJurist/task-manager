<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { RouterLink } from 'vue-router'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'

const auth = useAuthStore()

const email = ref('')
const password = ref('')
const remember = ref(false)
const errorMessage = ref({})
const loading = ref(false)

async function handleSubmit() {
    errorMessage.value = ''
    loading.value = true

    try {
        await auth.login(email.value, password.value, remember.value)
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
                <span>LOG IN TO </span>
                <span class="text-emerald-600">TASK MANAGER</span>
            </h1>

            <div class="mt-10">
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

            <div class="mt-5 flex items-center gap-2 flex-wrap">
                <input type="checkbox" v-model="remember" class="accent-emerald-600" />
                <label class="form-label">Remember Me</label>
            </div>

            <div class="flex justify-center mt-5">
                <button
                    type="submit"
                    :class="loading ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="loading">
                        <LoadingIcon />
                    </span>

                    <span>
                        {{ loading ? 'Logging in...' : 'Log in' }}
                    </span>
                </button>
            </div>

            <div class="text-emerald-600 font-medium mt-10 mb-5">
                <router-link :to="{ name: 'register' }">
                    Don't have an account yet? Register
                </router-link>
            </div>
        </form>
    </div>
</template>
