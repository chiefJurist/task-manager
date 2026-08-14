<script setup>
defineProps(['hasNothing', 'loadingDashboard'])
</script>
<template>
    <!-- empty task and project display -->
    <section class="py-32 italic font-semibold text-lg px-6 lg:px-8 text-emerald-500">
        <!-- dashboard still loading -->
        <div v-if="hasNothing && loadingDashboard" class="text-center">
            Loading Projects and Tasks...
        </div>

        <!-- dashboard done loading -->
        <div v-if="hasNothing && !loadingDashboard" class="text-center">
            You have no available projects or tasks
        </div>
    </section>
</template>
