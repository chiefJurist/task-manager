<script setup>
import { ref } from 'vue'
import DropDownIcon from '@/components/Icons/DropDownIcon.vue'
import DropUpIcon from '@/components/Icons/DropUpIcon.vue'
import EditIcon from '../Icons/EditIcon.vue'
import DeleteIcon from '../Icons/DeleteIcon.vue'
import LoadingIcon from '../Icons/LoadingIcon.vue'
import { useAuthStore } from '@/stores/auth.js'
import { storeToRefs } from 'pinia'

const auth = useAuthStore()
const { projects } = storeToRefs(auth)

const openProject = ref(null)
const loadingDelete = ref(false)

function toggeleOpenProject(id) {
    if (openProject.value === id) {
        openProject.value = null
    } else {
        openProject.value = id
    }
}

async function deleteProject() {
    try {
        loadingDelete.value = true
        await auth.deleteProject(openProject.value)
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
    <!-- projects dsplay -->
    <section id="projects" v-if="projects.length" class="py-20 px-6 lg:px-8">
        <div class="p-5 mx-auto max-w-3xl bg-white border border-slate-200 shadow rounded-xl">
            <div class="font-bold text-center text-3xl py-5">
                <span class="text-emerald-600">PROJECT </span>
                <span>LIST</span>
            </div>
            <div class="">
                <!-- project list -->
                <div
                    v-for="project in projects"
                    :key="project.id"
                    class="border border-slate-700 rounded-lg my-5 overflow-hidden"
                >
                    <!-- project name-->
                    <button
                        @click="toggeleOpenProject(project.id)"
                        class="px-4 py-2 w-full flex justify-between items-center hover:bg-slate-100 cursor-pointer brand-transition"
                        :class="{
                            'text-emerald-600 bg-emerald-50': project.id === openProject,
                        }"
                    >
                        <div class="text-lg font-semibold capitalize">
                            {{ project.name }}
                        </div>
                        <div>
                            <DropDownIcon v-if="project.id !== openProject" />
                            <DropUpIcon v-else />
                        </div>
                    </button>

                    <!-- tasks nested straight from the API response, no extra fetch needed -->
                    <transition
                        enter-active-class="transition ease-out duration-500"
                        enter-from-class="transform -translate-y-10 opacity-0"
                        enter-to-class="transform translate-y-0 opacity-100"
                        leave-active-class="transition ease-in duration-500"
                        leave-from-class="transform translate-y-0 opacity-100"
                        leave-to-class="transform -translate-y-5 opacity-0"
                    >
                        <div v-if="openProject === project.id">
                            <div v-if="project.tasks.length" class="px-4 pb-3">
                                <router-link
                                    :to="{ name: 'show-task', params: { id: task.id } }"
                                    v-for="task in project.tasks"
                                    :key="task.id"
                                    :class="
                                        task.status == 'completed'
                                            ? 'completed-status-list cursor-pointer'
                                            : 'uncompleted-status-list cursor-pointer'
                                    "
                                >
                                    {{ task.name }}
                                </router-link>
                            </div>

                            <div v-else class="px-4 pb-3 text-sm text-emerald-400 italic">
                                No tasks yet
                            </div>

                            <!--edit and delete project-->
                            <div class="flex justify-between items-center m-4 flex-wrap gap-2">
                                <router-link
                                    :to="{ name: 'edit-project', params: { id: project.id } }"
                                    class="view-btn brand-transition"
                                >
                                    <span><EditIcon class="text-white" /></span>
                                    <span>Edit Project</span>
                                </router-link>

                                <form @submit.prevent="deleteProject">
                                    <button
                                        :class="
                                            loadingDelete
                                                ? 'deleting-btn'
                                                : 'before-delete-btn brand-transition'
                                        "
                                    >
                                        <span v-if="!loadingDelete">
                                            <DeleteIcon class="text-white" />
                                        </span>
                                        <span v-if="loadingDelete">
                                            <LoadingIcon class="text-white" />
                                        </span>
                                        <span>
                                            {{ loadingDelete ? 'Deleting...' : 'Delete Task' }}
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </section>
</template>
