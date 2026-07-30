<script setup>
import { computed, onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'
import HeaderSection from '@/components/Dashboard/HeaderSection.vue'

const auth = useAuthStore()
const { user, tasks, projects } = storeToRefs(auth)
const mobileMenuDisplay = ref(false)
const firstName = computed(() => {
    const name = user.value.name.split(' ')[0]
    return name.length > 20 ? name.substring(0, 15) + '...' : name
})
const hasNothing = computed(() => !projects.value.length && !tasks.value.length)

const openProject = ref(null)

function toggelMobileMenuDisplay() {
    mobileMenuDisplay.value = !mobileMenuDisplay.value
}

function toggeleOpenProject(id) {
    if (openProject.value === id) {
        openProject.value = null
    } else {
        openProject.value = id
    }
}

onMounted(() => {
    ;(auth.fetchProjects(), auth.fetchTasks())
})
</script>
<template>
    <div class="">
        <HeaderSection
            :firstName="firstName"
            :mobile-menu-display="mobileMenuDisplay"
            @logout="auth.logout"
            @toggle-menu="toggelMobileMenuDisplay"
        />

        <!-- empty task and project display -->
        <section
            v-if="hasNothing"
            class="py-32 italic font-semibold text-lg px-6 lg:px-8 text-blue-500"
        >
            <div class="text-center">You have no available projects or tasks</div>
        </section>

        <!-- projects and tasks dsplay -->
        <section v-else class="py-32 px-6 lg:px-8">
            <!-- projects display -->
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
                                <svg
                                    v-if="project.id !== openProject"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="1em"
                                    height="1em"
                                    viewBox="0 0 24 24"
                                    class="text-2xl"
                                >
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        fill="currentColor"
                                        d="M17.37 12.39L12 16.71l-5.36-4.48a1 1 0 1 0-1.28 1.54l6 5a1 1 0 0 0 1.27 0l6-4.83a1 1 0 0 0 .15-1.41a1 1 0 0 0-1.41-.14"
                                    />
                                    <path
                                        fill="currentColor"
                                        d="M11.36 11.77a1 1 0 0 0 1.27 0l6-4.83a1 1 0 0 0 .15-1.41a1 1 0 0 0-1.41-.15L12 9.71L6.64 5.23a1 1 0 0 0-1.28 1.54Z"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="1em"
                                    height="1em"
                                    viewBox="0 0 24 24"
                                    class="text-emerald-600 text-2xl"
                                >
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        fill="currentColor"
                                        d="M6.63 11.61L12 7.29l5.37 4.48A1 1 0 0 0 18 12a1 1 0 0 0 .77-.36a1 1 0 0 0-.13-1.41l-6-5a1 1 0 0 0-1.27 0l-6 4.83a1 1 0 0 0-.15 1.41a1 1 0 0 0 1.41.14"
                                    />
                                    <path
                                        fill="currentColor"
                                        d="M12.64 12.23a1 1 0 0 0-1.27 0l-6 4.83a1 1 0 0 0-.15 1.41a1 1 0 0 0 1.41.15L12 14.29l5.37 4.48A1 1 0 0 0 18 19a1 1 0 0 0 .77-.36a1 1 0 0 0-.13-1.41Z"
                                    />
                                </svg>
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

                                <!--delete project-->
                                <form>
                                    <button
                                        type="submit"
                                        class="block p-2 mx-auto rounded-lg text-white my-3 bg-red-600 hover:bg-red-400 font-semibold cursor-pointer"
                                    >
                                        Delete Project
                                    </button>
                                </form>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>

            <!-- tasks display-->
            <div></div>
        </section>
    </div>
</template>
