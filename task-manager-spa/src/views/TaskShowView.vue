<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import BackIcon from '@/components/Icons/BackIcon.vue'
import DeleteIcon from '@/components/Icons/DeleteIcon.vue'
import EditIcon from '@/components/Icons/EditIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'

const route = useRoute()
const auth = useAuthStore()

const { task } = storeToRefs(auth)

onMounted(async () => {
    if (!task.value || task.value.id != route.params.id) {
        await auth.fetchTask(route.params.id)
    }
})
</script>
<template>
    <section class="view-section">
        <div v-if="task && task.id == route.params.id" class="view-sub-section">
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
                <button
                    :class="
                        false ? 'form-loading' : 'form-before-loading brand-transition view-btn'
                    "
                >
                    <span v-if="false">
                        <LoadingIcon />
                    </span>
                    <span> {{ false ? 'Marking...' : ' Mark as completed' }} </span>
                </button>

                <button :class="false ? 'deleting-btn' : 'before-delete-btn brand-transition'">
                    <span v-if="true">
                        <DeleteIcon class="text-white" />
                    </span>
                    <span v-if="false">
                        <LoadingIcon class="text-white" />
                    </span>
                    <span> {{ false ? 'Deleting...' : 'Delete Task' }} </span>
                </button>
            </div>
        </div>
    </section>
</template>
