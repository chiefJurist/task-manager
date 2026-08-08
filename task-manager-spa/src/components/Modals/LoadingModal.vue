<script setup>
import LoadingIcon from '../Icons/LoadingIcon.vue'
</script>

<template>
    <div
        class="bg-emerald-600 text-white p-5 md:p-8 max-w-xl w-full rounded-lg shadow flex justify-center"
    >
        <div class="flex gap-3 flex-wrap items-center">
            <span>
                <LoadingIcon />
            </span>
            <span class="font-semibold text-lg">LOADING ...</span>
        </div>
    </div>
</template>
