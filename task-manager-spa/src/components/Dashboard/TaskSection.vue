<script setup>
defineProps(['hasTasks'])
</script>

<template>
    <!-- tasks display-->
    <section v-if="hasTasks" class="py-32 px-6 lg:px-8">
        <div class="p-5 mx-auto max-w-3xl bg-white border border-slate-200 shadow rounded-xl">
            <div class="font-bold text-center text-3xl py-5">
                <span class="text-emerald-600">TASK </span>
                <span>LIST</span>
            </div>
        </div>
    </section>
</template>
