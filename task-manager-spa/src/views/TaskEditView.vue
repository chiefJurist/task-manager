<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import LoadingModal from '@/components/Modals/LoadingModal.vue'
import NotFound from '@/components/NotFound.vue'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { onMounted } from 'vue'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'

const auth = useAuthStore()
const route = useRoute()
const { tasks, projects } = storeToRefs(auth)

const name = ref('')
const projectId = ref(null)
const loading = ref(false)
const loadingSubmit = ref(false)
const errorMessage = ref({})
const display = ref('')

const task = computed(() => {
    return tasks.value.find((task) => task.id == route.params.id)
})

const availableProjects = computed(() => {
    return projects.value.filter((project) => project.id !== route.params.id)
})

onMounted(async () => {
    try {
        if (!task.value) {
            loading.value = true
            await auth.fetchTasks()
        }
        if (!availableProjects.value.length) {
            loading.value = true
            await auth.fetchProjects()
        }
        if (task.value) {
            name.value = task.value.name
            projectId.value = task.value.project_id
        }
    } finally {
        loading.value = false
    }
})

async function handleSubmit() {
    errorMessage.value = {}
    display.value = ''
    loadingSubmit.value = true

    try {
        await auth.editTask(task.value.id, name.value, projectId.value, task.value.status)
    } catch (error) {
        if (error.response?.status === 422) {
            errorMessage.value = error.response.data.errors ?? {}
        } else {
            display.value =
                error.response?.data?.message ?? 'Check internet connection and try again'
        }
    } finally {
        loadingSubmit.value = false
    }
}
</script>

<template>
    <section class="view-section">
        <LoadingModal v-if="loading" />

        <form
            @submit.prevent="handleSubmit"
            v-else-if="name && projectId && availableProjects.length"
            class="view-sub-section"
        >
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span><BackIcon /></span>
                <span>Back to Dashboard</span>
            </router-link>

            <p v-if="display" class="response-message-display">
                {{ display }}
            </p>

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
                    :class="loadingSubmit ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="loadingSubmit">
                        <LoadingIcon />
                    </span>

                    <span v-if="!loadingSubmit">Save Task</span>
                </button>
            </div>
        </form>

        <NotFound v-else>Could Not Load Tasks And Projects</NotFound>
    </section>
</template>
