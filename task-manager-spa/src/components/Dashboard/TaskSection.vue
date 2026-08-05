<script setup>
import DragDropIcon from '../Icons/DragDropIcon.vue'
import EditIcon from '../Icons/EditIcon.vue'

defineProps(['hasTasks', 'tasks'])
</script>

<template>
    <!-- tasks display-->
    <section v-if="hasTasks" class="pt-32 px-6 lg:px-8">
        <div class="p-5 mx-auto max-w-3xl bg-white border border-slate-200 shadow rounded-xl">
            <div class="font-bold text-center text-3xl py-5">
                <span class="text-emerald-600">TASK </span>
                <span>LIST</span>
            </div>

            <div class="">
                <!-- task list -->
                <router-link
                    :to="{ name: 'show-task', params: { id: task.id } }"
                    v-for="task in tasks"
                    :key="task.id"
                    class="border border-slate-700 hover:bg-slate-50 rounded p-3 my-5 overflow-hidden flex items-center justify-between cursor-pointer"
                >
                    <div class="flex items-center gap-2">
                        <DragDropIcon />
                        <p>{{ task.name }}</p>
                    </div>
                    <EditIcon class="hidden md:block" />
                </router-link>
            </div>
        </div>
    </section>
</template>
