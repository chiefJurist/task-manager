<script setup>
import { useAuthStore } from '@/stores/auth.js'
import DragDropIcon from '../Icons/DragDropIcon.vue'
import EditIcon from '../Icons/EditIcon.vue'
import draggable from 'vuedraggable'
import { storeToRefs } from 'pinia'
import { ref } from 'vue'

const auth = useAuthStore()
const { tasks } = storeToRefs(auth)
const previousTasksOrder = ref([])

function onDragStart() {
    previousTasksOrder.value = [...tasks.value]
}

async function onDragEnd() {
    const changed = []
    tasks.value.forEach((task, index) => {
        const oldIndex = previousTasksOrder.value.findIndex((t) => t.id === task.id)

        if (oldIndex !== index) {
            changed.push({ id: task.id, priority: index + 1 })
        }
    })
    if (changed.length) {
        await auth.reorderTasks(changed)
    }
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

            <draggable
                v-model="tasks"
                @start="onDragStart"
                @end="onDragEnd"
                item-key="id"
                handle=".drag-handle"
            >
                <template #item="{ element: task }">
                    <router-link
                        :to="{ name: 'show-task', params: { id: task.id } }"
                        :class="
                            task.status == 'completed'
                                ? 'completed-status-list drag-handle cursor-grab'
                                : 'uncompleted-status-list drag-handle cursor-grab'
                        "
                    >
                        <div class="flex items-center gap-2">
                            <DragDropIcon />
                            <p>{{ task.name }}</p>
                        </div>
                        <EditIcon class="hidden md:block" />
                    </router-link>
                </template>
            </draggable>
        </div>
    </section>
</template>
