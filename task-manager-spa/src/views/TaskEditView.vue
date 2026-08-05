<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { onMounted } from 'vue'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'

const auth = useAuthStore()
const route = useRoute()
const { task, projects } = storeToRefs(auth)

const name = ref('')
const projectId = ref(null)
const loading = ref(false)
const errorMessage = ref({})

onMounted(async () => {
    await auth.fetchTask(route.params.id)
    await auth.fetchProjects()
    name.value = task.value.name
    projectId.value = task.value.project_id
})

async function handleSubmit() {
    errorMessage.value = {}
    loading.value = true

    try {
        await auth.editTask(task.value.id, name.value, projectId.value, task.value.status)
    } catch (error) {
        errorMessage.value = error?.response?.data?.errors ?? {}
    } finally {
        loading.value = false
        auth.fetchTask(task.value.id)
        auth.fetchProjects()
    }
}

const availableProjects = computed(() => {
    return projects.value.filter((project) => project.id !== task.value.project_id)
})
</script>
<template>
    <section class="min-h-screen bg-slate-50 py-20 px-4">
        <form
            @submit.prevent="handleSubmit"
            v-if="task && projects.length"
            class="bg-white border border-slate-100 shadow rounded-lg p-6 max-w-3xl mx-auto"
        >
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span><BackIcon /></span>
                <span>Back to Dashboard</span>
            </router-link>

            <div class="view-header">
                <span>EDIT </span>
                <span class="text-emerald-600">TASK</span>
            </div>

            <div class="mt-10">
                <label class="form-label">Task Name</label>
                <input type="text" class="form-input" v-model="name" />
                <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p>
            </div>

            <div class="mt-10">
                <h2 class="text-xl font-semibold text-center">CHANGE TASK'S PROJECT</h2>
                <div class="mt-5">
                    <select class="form-input" v-model="projectId">
                        <option :value="task.project_id">{{ task.project.name }}</option>
                        <option
                            v-for="project in availableProjects"
                            :key="project.id"
                            :value="project.id"
                        >
                            {{ project.name }}
                        </option>
                    </select>
                    <p v-if="errorMessage.project_id" class="form-error">
                        {{ errorMessage.project_id[0] }}
                    </p>
                </div>
            </div>

            <div class="my-10 flex justify-center">
                <button
                    type="submit"
                    :class="loading ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="loading">
                        <LoadingIcon />
                    </span>

                    <span v-if="!loading">Save Task</span>
                </button>
            </div>
        </form>
    </section>
</template>
