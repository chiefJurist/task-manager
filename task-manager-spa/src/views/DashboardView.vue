<script setup>
import { computed, onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'
import HeaderSection from '@/components/Dashboard/HeaderSection.vue'
import EmptySection from '@/components/Dashboard/EmptySection.vue'
import ProjectsSection from '@/components/Dashboard/ProjectsSection.vue'
import TaskSection from '@/components/Dashboard/TaskSection.vue'

const auth = useAuthStore()
const { user, tasks, projects } = storeToRefs(auth)
const mobileMenuDisplay = ref(false)
const loadingDashboard = ref(true)
const logoutLoading = ref(false)

const firstName = computed(() => {
    const name = user.value.name.split(' ')[0]
    return name.length > 20 ? name.substring(0, 15) + '...' : name
})
const hasNothing = computed(() => !projects.value.length && !tasks.value.length)
const hasProjects = computed(() => projects.value.length)
const hasTasks = computed(() => tasks.value.length)

function toggelMobileMenuDisplay() {
    mobileMenuDisplay.value = !mobileMenuDisplay.value
}

async function logout() {
    logoutLoading.value = true
    try {
        await auth.logout()
    } finally {
        logoutLoading.value = false
    }
}

onMounted(async () => {
    try {
        await Promise.all([auth.fetchProjects(), auth.fetchTasks()])
    } finally {
        loadingDashboard.value = false
    }
})
</script>
<template>
    <div class="">
        <HeaderSection
            :firstName="firstName"
            :mobile-menu-display="mobileMenuDisplay"
            :logoutLoading="logoutLoading"
            @logout="logout"
            @toggle-menu="toggelMobileMenuDisplay"
        />

        <EmptySection :loading-dashboard="loadingDashboard" :has-nothing="hasNothing" />

        <ProjectsSection :has-projects="hasProjects" :projects="projects" />

        <TaskSection :has-tasks="hasTasks" :tasks="tasks" />
    </div>
</template>
