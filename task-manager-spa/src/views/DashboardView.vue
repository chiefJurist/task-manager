<script setup>
import { computed, onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'
import HeaderSection from '@/components/Dashboard/HeaderSection.vue'

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
        <HeaderSection
            :firstName="firstName"
            :mobile-menu-display="mobileMenuDisplay"
            @logout="auth.logout"
            @toggle-menu="toggelMobileMenuDisplay"
        />

        <!-- empty task and project display -->
        <section v-if="hasNothing" class="py-32 italic font-semibold text-lg px-6 lg:px-8">
            <div class="text-center">You have no available projects or tasks</div>
        </section>

        <!-- projects and section dsplay -->
        <section v-else class="py-32 px-6 lg:px-8">
            <div class="p-5 mx-auto max-w-3xl bg-white shadow border border-slate-200 rounded-xl">
                <div class="font-semibold text-center text-2xl py-5">PROJECT LIST</div>
                <div class="">
                    <!-- project list -->
                    <div
                        v-for="project in projects"
                        :key="project.id"
                        class="border border-slate-700 rounded-lg my-5 overflow-hidden"
                    >
                        <!-- project name-->
                        <div
                            class="px-4 py-2 flex justify-between items-end hover:bg-gray-100 cursor-pointer"
                        >
                            <div class="text-lg font-semibold capitalize">
                                {{ project.name }}
                            </div>
                            <div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="1em"
                                    height="1em"
                                    viewBox="0 0 24 24"
                                    class="text-2xl"
                                >
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        fill="currentColor"
                                        d="M14.5 14.5L18 11h-7zM8 19q-2.925 0-4.962-2.037T1 12t2.038-4.962T8 5h8q2.925 0 4.963 2.038T23 12t-2.037 4.963T16 19zm0-2h8q2.075 0 3.538-1.463T21 12t-1.463-3.537T16 7H8Q5.925 7 4.463 8.463T3 12t1.463 3.538T8 17"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- tasks nested straight from the API response, no extra fetch needed -->
                        <div v-if="project.tasks.length" class="px-4 pb-3">
                            <div
                                v-for="task in project.tasks"
                                :key="task.id"
                                class="py-2 text-sm text-gray-700"
                            >
                                {{ task.name }}
                            </div>
                        </div>
                        <div v-else class="px-4 pb-3 text-sm text-gray-400 italic">
                            No tasks yet
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
