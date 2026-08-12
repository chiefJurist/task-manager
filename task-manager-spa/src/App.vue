<script setup>
import { RouterView } from 'vue-router'
import ToastMessage from './components/Modals/ToastMessage.vue'
import { useAuthStore } from './stores/auth.js'
import { storeToRefs } from 'pinia'

const auth = useAuthStore()
const { toastMessage } = storeToRefs(auth)

function closeToast() {
    toastMessage.value = null
}
</script>

<template>
    <div class="min-h-screen bg-slate-100 text-slate-900">
        <ToastMessage :message="toastMessage" @close="closeToast" />
        <RouterView />
    </div>
</template>
