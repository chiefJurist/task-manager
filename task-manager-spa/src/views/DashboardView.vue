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
        <section v-else class="py-32">
            <div v-for="project in projects" :key="project.id">{{ project.name }} 123</div>
        </section>
    </div>
</template>
