<script setup>
import { computed, onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const { user, tasks, projects } = storeToRefs(auth)
const mobileMenuDisplay = ref(false)

const firstName = computed(() => user.value.name.split(' ')[0])
const hasNothing = computed(() => !projects.value.length && !tasks.value.length)

function toggelMobileMenuDisplay() {
    mobileMenuDisplay.value = !mobileMenuDisplay.value
}

onMounted(() => {
    ;(auth.fetchProjects(), auth.fetchTasks())
})
</script>
<template>
    <div class="">
        <header
            class="fixed top-0 left-0 right-0 z-40 w-full bg-slate-900 border-b border-slate-200 shadow text-white"
        >
            <div class="font-semibold max-w-4xl mx-auto px-6 lg:px-8 py-5">
                <div class="flex items-center justify-between">
                    <h1>Hello, {{ firstName }}!</h1>
                    <!--Large Screen Project and Task Buttons-->
                    <div class="hidden md:block">
                        <button
                            class="bg-blue-700/80 px-5 py-3 rounded cursor-pointer mr-3 hover:bg-blue-400 brand-transition"
                        >
                            Add Project
                        </button>
                        <button
                            class="bg-blue-700/80 px-5 py-3 rounded cursor-pointer mr-3 hover:bg-blue-400 brand-transition"
                        >
                            Add Task
                        </button>
                    </div>

                    <!--Large screen logout button-->
                    <div class="hidden md:block">
                        <button
                            @click="auth.logout"
                            class="bg-red-600/80 px-5 py-3 rounded cursor-pointer mr-3 hover:bg-red-400 brand-transition"
                        >
                            Log out
                        </button>
                    </div>

                    <!-- Mobile Menu Button-->
                    <div class="md:hidden">
                        <button @click="toggelMobileMenuDisplay">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="1em"
                                height="1em"
                                viewBox="0 0 24 24"
                                class="text-3xl"
                            >
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    fill="#fff"
                                    d="M4 18q-.425 0-.712-.288T3 17t.288-.712T4 16h16q.425 0 .713.288T21 17t-.288.713T20 18zm0-5q-.425 0-.712-.288T3 12t.288-.712T4 11h16q.425 0 .713.288T21 12t-.288.713T20 13zm0-5q-.425 0-.712-.288T3 7t.288-.712T4 6h16q.425 0 .713.288T21 7t-.288.713T20 8z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="">
                <transition
                    enter-active-class="transition ease-out duration-500"
                    enter-from-class="transform -translate-y-10 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition ease-in duration-500"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-10 opacity-0"
                >
                    <div v-if="mobileMenuDisplay" class="md:hidden border-t border-slate-200">
                        <div class="p-6 space-y-3">
                            <button class="mobile-menu-link" @click="mobileMenuDisplay = false">
                                Add Project
                            </button>
                            <button class="mobile-menu-link" @click="mobileMenuDisplay = false">
                                Add Task
                            </button>
                            <button class="mobile-menu-link" @click="mobileMenuDisplay = false">
                                Logout
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </header>

        <!-- empty task and project display -->
        <section v-if="hasNothing" class="py-32 italic font-semibold text-lg px-6 lg:px-8">
            <div class="text-center">You have no available projects or tasks</div>
        </section>

        <!-- projects and section dsplay -->
        <section v-else class="py-32">
            <div v-for="project in projects" :key="project.id">{{ project.name }} 123</div>
        </section>
    </div>
</template>
