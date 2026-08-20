<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import LoadingModal from '@/components/Modals/LoadingModal.vue'
import NotFound from '@/components/Modals/NotFound.vue'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const auth = useAuthStore()
const route = useRoute()
const { projects } = storeToRefs(auth)

const loading = ref(false)
const loadingSubmit = ref(false)
const errorMessage = ref({})

const project = computed(() => {
    return projects.value.find((project) => project.id == route.params.id)
})

const name = ref(project.value?.name ?? '')

onMounted(async () => {
    try {
        if (!project.value) {
            loading.value = true
            await auth.fetchProjects()
            name.value = project.value?.name ?? ''
        }
    } finally {
        loading.value = false
    }
})

async function editProject() {
    loadingSubmit.value = true
    errorMessage.value = {}

    try {
        await auth.editProject(project.value.id, name.value)
    } catch (error) {
        if (error.response?.status === 422) {
            errorMessage.value = error.response.data.errors ?? {}
        } else {
            auth.setToast(
                error.response?.data?.message ?? 'Check internet connection and try again',
            )
        }
    } finally {
        loadingSubmit.value = false
    }
}
</script>

<template>
    <section class="view-section">
        <LoadingModal v-if="loading" />

        <form
            v-else-if="!loading && project"
            @submit.prevent="editProject"
            class="view-sub-section"
        >
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span><BackIcon /></span>
                <span>Back to Dashboard</span>
            </router-link>

            <div class="view-header">
                <span>EDIT </span>
                <span class="text-emerald-600">PROJECT</span>
            </div>

            <div class="mt-10">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-input" v-model="name" />
                <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p>
            </div>

            <div class="my-10 flex justify-center">
                <button
                    type="submit"
                    :class="loadingSubmit ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="loadingSubmit">
                        <LoadingIcon />
                    </span>

                    <span>{{ loadingSubmit ? 'Saving...' : 'Save Project' }}</span>
                </button>
            </div>
        </form>

        <NotFound v-else>Project Not Found</NotFound>
    </section>
</template>
