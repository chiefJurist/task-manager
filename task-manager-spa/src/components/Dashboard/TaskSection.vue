<script setup>
import { useAuthStore } from '@/stores/auth.js'
import DragDropIcon from '../Icons/DragDropIcon.vue'
import EditIcon from '../Icons/EditIcon.vue'
import draggable from 'vuedraggable'
import { storeToRefs } from 'pinia'

const auth = useAuthStore()
const { tasks } = storeToRefs(auth)

async function onDragEnd() {
    const taskIds = tasks.value.map((task) => task.id)
    await auth.reorderTasks(taskIds)
}
</script>

<template>
    <!-- tasks display-->
    <section id="tasks" v-if="tasks.length" class="pt-32 px-6 lg:px-8">
        <div class="p-5 mx-auto max-w-3xl bg-white border border-slate-200 shadow rounded-xl">
            <div class="font-bold text-center text-3xl py-5">
                <span class="text-emerald-600">TASK </span>
                <span>LIST</span>
            </div>

            <draggable v-model="tasks" @end="onDragEnd" item-key="id" handle=".drag-handle">
                <template #item="{ element: task }">
                    <router-link
                        :to="{ name: 'show-task', params: { id: task.id } }"
                        :class="
                            task.status == 'completed'
                                ? 'completed-status-list'
                                : 'uncompleted-status-list'
                        "
                    >
                        <div class="flex items-center gap-2">
                            <DragDropIcon class="drag-handle cursor-grab" />
                            <p>{{ task.name }}</p>
                        </div>
                        <EditIcon class="hidden md:block" />
                    </router-link>
                </template>
            </draggable>
        </div>
    </section>
</template>
