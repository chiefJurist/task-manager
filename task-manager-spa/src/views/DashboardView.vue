<script setup>
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const firstName = computed(() => auth.user?.name?.split(' ')[0])
const userData = computed(() => auth.user)

console.log(userData)
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
        <section v-if="!userData.projects" class="py-32">
            <div>Nil</div>
        </section>
        <section>
            <div>
                <div></div>
            </div>
        </section>
    </div>
</template>
