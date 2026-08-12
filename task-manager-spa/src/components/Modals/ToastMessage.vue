<script setup>
import { computed } from 'vue'
import CancelIcon from '../Icons/CancelIcon.vue'

const props = defineProps(['message'])
defineEmits(['close'])

const positive = computed(() => /successful/i.test(props.message))
</script>
<template>
    <transition
        enter-active-class="transition ease-out duration-500"
        enter-from-class="transform -translate-y-10 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-500"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform -translate-y-10 opacity-0"
    >
        <div
            v-if="message"
            :class="
                positive
                    ? 'bg-white text-emerald-600 border border-slate-100 shadow-lg font-semibold fixed top-30 left-1/2 -translate-x-1/2 m-5 p-5 rounded-xl w-4/5 md:w-sm z-50'
                    : 'bg-white text-red-600 shadow-lg font-semibold fixed top-30 left-1/2 -translate-x-1/2 m-5 p-5 rounded-xl w-4/5 md:w-sm z-50'
            "
        >
            <div class="flex justify-end items-center">
                <CancelIcon @click="$emit('close')" />
            </div>

            <p class="text-lg text-center font-medium capitalize my-5">{{ message }}</p>
        </div>
    </transition>
</template>
