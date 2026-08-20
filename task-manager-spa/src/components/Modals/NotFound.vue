<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
</script>

<template>
    <div class="bg-slate-400 px-5 md:px-8 py-12 max-w-xl w-full rounded-lg shadow">
        <router-link :to="{ name: 'dashboard' }" class="nav-link">
            <span><BackIcon /></span>
            <span>Back to Dashboard</span>
        </router-link>

        <p class="text-slate-600 text-xl font-bold text-center"><slot></slot></p>
    </div>
</template>
