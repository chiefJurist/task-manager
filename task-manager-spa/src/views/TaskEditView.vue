<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import LoadingModal from '@/components/Modals/LoadingModal.vue'
import NotFound from '@/components/Modals/NotFound.vue'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { onMounted } from 'vue'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'

const auth = useAuthStore()
const route = useRoute()
const { tasks, projects, initialized } = storeToRefs(auth)

const loading = ref(false)
const loadingSubmit = ref(false)

const task = computed(() => {
    return tasks.value.find((task) => task.id == route.params.id)
})

const availableProjects = computed(() => {
    return projects.value.filter((project) => project.id !== task.value?.project_id)
})

const name = ref(task.value?.name ?? '')
const projectId = ref(task.value?.project_id ?? null)
const errorMessage = ref({})

onMounted(async () => {
    if (!initialized.value) {
        try {
            loading.value = true
            await auth.fetchTasks()
            await auth.fetchProjects()
            name.value = task.value?.name ?? ''
            projectId.value = task.value?.project_id ?? 'null'
        } finally {
            loading.value = false
        }
    }
})

async function handleSubmit() {
    errorMessage.value = {}
    loadingSubmit.value = true

    try {
        await auth.editTask(task.value.id, name.value, projectId.value)
    } catch (error) {
        if (error.response?.status === 422) {
            errorMessage.value = error.response.data.errors ?? {}
        } else {
            auth.setToast(
                error.response?.data?.message ?? 'Check internet connection and try again',
            )
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
            v-else-if="task?.id == route.params.id"
            class="view-sub-section"
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
                        <option :value="task.project_id">
                            {{ task.project?.name ?? 'No Attached Project' }}
                        </option>
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
