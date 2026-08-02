<script setup>
import { ref } from 'vue'
import DropDownIcon from '@/components/Icons/DropDownIcon.vue'
import DropUpIcon from '@/components/Icons/DropUpIcon.vue'
import EditIcon from '../Icons/EditIcon.vue'
import DeleteIcon from '../Icons/DeleteIcon.vue'

defineProps(['hasProjects', 'projects'])

const openProject = ref(null)

function toggeleOpenProject(id) {
    if (openProject.value === id) {
        openProject.value = null
    } else {
        openProject.value = id
    }
}
</script>

<template>
    <!-- projects dsplay -->
    <section v-if="hasProjects" class="pt-32 px-6 lg:px-8">
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
                                <div
                                    v-for="task in project.tasks"
                                    :key="task.id"
                                    class="p-2 my-5 text-sm border border-emerald-600 rounded"
                                >
                                    {{ task.name }}
                                </div>
                            </div>

                            <div v-else class="px-4 pb-3 text-sm text-emerald-400 italic">
                                No tasks yet
                            </div>

                            <!--edit and delete project-->
                            <div class="flex justify-between items-center m-4 flex-wrap gap-2">
                                <router-link
                                    :to="{ name: 'edit-project' }"
                                    class="view-btn brand-transition"
                                >
                                    <span><EditIcon class="text-white" /></span>
                                    <span>Edit Project</span>
                                </router-link>

                                <form>
                                    <button
                                        :class="
                                            false
                                                ? 'deleting-btn'
                                                : 'before-delete-btn brand-transition'
                                        "
                                    >
                                        <span v-if="true">
                                            <DeleteIcon class="text-white" />
                                        </span>
                                        <span v-if="false">
                                            <LoadingIcon class="text-white" />
                                        </span>
                                        <span> {{ false ? 'Deleting...' : 'Delete Task' }} </span>
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
