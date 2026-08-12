<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import router from '@/router/index'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'

const auth = useAuthStore()
const { projects } = storeToRefs(auth)

const loading = ref(false)
const errorMessage = ref({})

const taskName = ref('')
const projectId = ref(null)

onMounted(async () => {
    await auth.fetchProjects()
})

async function createTask() {
    try {
        loading.value = true
        errorMessage.value = {}

        await auth.createTask(taskName.value, projectId.value)
        router.push({ name: 'dashboard' })
    } catch (error) {
        errorMessage.value = error?.response?.data?.errors ?? {}
        if (error.response?.status === 422) {
            errorMessage.value = error.response.data.errors ?? {}
        } else {
            await auth.setToast(
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
        <form @submit.prevent="createTask" class="view-sub-section">
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span>
                    <BackIcon />
                </span>
                <span>Back to Dashboard</span>
            </router-link>

            <div class="view-header">CREATE A TASK</div>

            <div class="mt-10">
                <label class="form-label">Task Name</label>
                <input
                    type="text"
                    required
                    placeholder="Input your task name"
                    v-model="taskName"
                    class="form-input"
                />
                <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p>
            </div>

            <div class="mt-5">
                <label class="form-label">Choose Associated Project</label>
                <select class="form-input cursor-pointer" v-model="projectId">
                    <option :value="null">Select a Project</option>
                    <option v-for="project in projects" :key="project.id" :value="project.id">
                        {{ project.name }}
                    </option>
                </select>
                <p v-if="errorMessage.project_id" class="form-error">
                    {{ errorMessage.project_id[0] }}
                </p>
            </div>

            <div class="flex justify-center mt-5">
                <button
                    type="submit"
                    :class="loading ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="loading">
                        <LoadingIcon />
                    </span>

                    <span>{{ loading ? 'Creating...' : 'Create Task' }}</span>
                </button>
            </div>
        </form>
    </section>
</template>
