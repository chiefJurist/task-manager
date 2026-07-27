<script setup>
import { computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const { user, tasks, projects } = storeToRefs(auth)

const firstName = computed(() => user.value.name.split(' ')[0])
const hasNothing = computed(() => !projects.value.length && !tasks.value.length)

onMounted(() => {
    ;(auth.fetchProjects(), auth.fetchTasks())
})
</script>
<template>
    <div>
        <header
            class="fixed top-0 left-0 right-0 z-40 w-full bg-gray-700/80 border-b border-gray-700 shadow p-5 text-white font-semibold"
        >
            <div class="flex items-center justify-between max-w-4xl mx-auto">
                <h1>Hello, {{ firstName }}!</h1>
                <div>
                    <button
                        class="bg-zinc-600/80 px-5 py-3 rounded cursor-pointer mr-3 hover:bg-zinc-400 brand-transition"
                    >
                        Add Project
                    </button>
                    <button
                        class="bg-zinc-600/80 px-5 py-3 rounded cursor-pointer mr-3 hover:bg-zinc-400 brand-transition"
                    >
                        Add Task
                    </button>
                </div>
            </div>
        </header>
        <section v-if="hasNothing" class="py-32">
            <div>You have no available projects or tasks</div>
        </section>
        <section v-else class="py-32">
            <div v-for="task in tasks" :key="task.id">{{ task.name }} 123</div>
        </section>
    </div>
</template>
