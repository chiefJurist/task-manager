<script setup>
import { computed, onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import BackIcon from '@/components/Icons/BackIcon.vue'
import DeleteIcon from '@/components/Icons/DeleteIcon.vue'
import EditIcon from '@/components/Icons/EditIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import router from '@/router/index'
import LoadingModal from '@/components/Modals/LoadingModal.vue'
import NotFound from '@/components/NotFound.vue'

const route = useRoute()
const auth = useAuthStore()
const { tasks } = storeToRefs(auth)

const loading = ref(false)
const loadingMark = ref(false)
const loadingDelete = ref(false)

const task = computed(() => {
    return tasks.value.find((task) => task.id == route.params.id)
})

onMounted(async () => {
    try {
        if (!task.value) {
            loading.value = true
            await auth.fetchTasks()
        }
    } finally {
        loading.value = false
    }
})

async function toggleStatus() {
    try {
        loadingMark.value = true
        const newStatus = task.value.status === 'completed' ? 'uncompleted' : 'completed'
        await auth.editStatus(task.value.id, newStatus)
    } catch (error) {
        await auth.setToast(
            error.response?.data?.message ?? 'Check internet connection and try again',
        )
    } finally {
        loadingMark.value = false
    }
}

async function deleteTask() {
    try {
        loadingDelete.value = true
        await auth.deleteTask(task.value.id)
        router.push({ name: 'dashboard' })
    } catch (error) {
        await auth.setToast(
            error.response?.data?.message ?? 'Check internet connection and try again',
        )
    } finally {
        loadingDelete.value = false
    }
}
</script>

<template>
    <section class="view-section">
        <LoadingModal v-if="loading" />

        <div v-else-if="task?.id == route.params.id" class="view-sub-section">
            <div class="flex justify-between items-center flex-wrap gap-3">
                <router-link :to="{ name: 'dashboard' }" class="nav-link">
                    <span><BackIcon /></span>
                    <span>Back to Dashboard</span>
                </router-link>

                <router-link
                    :to="{ name: 'edit-task', params: { id: task.id } }"
                    class="view-btn brand-transition"
                >
                    <span> <EditIcon class="text-white" /> </span>
                    <span> Edit Task </span>
                </router-link>
            </div>

            <h1 class="view-header mb-12">
                <span class="text-emerald-600">TASK </span>
                <span>DETAILS</span>
            </h1>

            <p class="show-task-paragraph">
                <span class="show-task-paragrapgh-label">TASK NAME:</span>
                <span>{{ task.name }}</span>
            </p>

            <div class="grid grid-cols-1 gap-5 mt-5">
                <p class="show-task-paragraph" v-if="task.project_id">
                    <span class="show-task-paragrapgh-label">PROJECT NAME:</span>
                    <span>{{ task.project.name }}</span>
                </p>

                <p class="show-task-paragraph">Priority {{ task.priority }}</p>

                <p
                    :class="
                        task.status == 'completed'
                            ? 'show-task-paragraph capitalize'
                            : 'show-incomplete-task-paragraph'
                    "
                >
                    <span class="show-task-paragrapgh-label">TASK STATUS:</span>
                    {{ task.status }}
                </p>

                <p class="show-task-paragraph">
                    <span class="show-task-paragrapgh-label">CREATION TIME:</span>
                    {{
                        new Date(task.created_at).toLocaleString('en-US', {
                            dateStyle: 'long',
                            timeStyle: 'short',
                        })
                    }}
                </p>

                <p class="show-task-paragraph">
                    <span class="show-task-paragrapgh-label">LAST UPDATED:</span>
                    {{
                        new Date(task.updated_at).toLocaleString('en-US', {
                            dateStyle: 'long',
                            timeStyle: 'short',
                        })
                    }}
                </p>
            </div>

            <div class="flex justify-between items-center flex-wrap gap-3 my-10">
                <form @submit.prevent="toggleStatus">
                    <button
                        type="submit"
                        :class="
                            loadingMark
                                ? 'form-loading'
                                : 'form-before-loading brand-transition view-btn'
                        "
                    >
                        <span v-if="loadingMark">
                            <LoadingIcon />
                        </span>
                        <span>
                            {{
                                loadingMark
                                    ? 'Marking...'
                                    : task.status == 'completed'
                                      ? ' Mark as Uncompleted'
                                      : 'Mark as Completed'
                            }}
                        </span>
                    </button>
                </form>

                <form @submit.prevent="deleteTask">
                    <button
                        type="submit"
                        :class="
                            loadingDelete ? 'deleting-btn' : 'before-delete-btn brand-transition'
                        "
                    >
                        <span v-if="!loadingDelete">
                            <DeleteIcon class="text-white" />
                        </span>
                        <span v-if="loadingDelete">
                            <LoadingIcon class="text-white" />
                        </span>
                        <span> {{ loadingDelete ? 'Deleting...' : 'Delete Task' }} </span>
                    </button>
                </form>
            </div>
        </div>

        <NotFound v-else>Task Not Found</NotFound>
    </section>
</template>
