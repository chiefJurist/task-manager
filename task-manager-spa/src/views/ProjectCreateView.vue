<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import router from '@/router/index'
import { useAuthStore } from '@/stores/auth'
import { ref } from 'vue'
import { RouterLink } from 'vue-router'

const auth = useAuthStore()

const loading = ref(false)
const errorMessage = ref({})

const projectName = ref('')

async function createProject() {
    try {
        loading.value = true
        errorMessage.value = {}

        await auth.createProject(projectName.value)
        router.push({ name: 'dashboard' })
    } catch (error) {
        if (error.response?.status === 422) {
            errorMessage.value = error.response.data.errors ?? {}
        } else {
            auth.setToast(
                error.response?.data?.message ?? 'Check internet connection and try again',
            )
        }
    } finally {
        loading.value = false
    }
}
</script>
<template>
    <section class="view-section">
        <form @submit.prevent="createProject" class="view-sub-section">
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span><BackIcon /></span>
                <span>Back to Dashboard</span>
            </router-link>

            <h1 class="view-header">CREATE A PROJECT</h1>

            <div class="mt-10">
                <label class="form-label">Project Name</label>
                <input
                    type="text"
                    required
                    placeholder="Input your project name"
                    class="form-input"
                    v-model="projectName"
                />
                <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p>
            </div>

            <div class="flex justify-center my-5">
                <button
                    type="submit"
                    :class="loading ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="loading">
                        <LoadingIcon />
                    </span>

                    <span>{{ loading ? 'Creating...' : 'Create Project' }}</span>
                </button>
            </div>
        </form>
    </section>
</template>
