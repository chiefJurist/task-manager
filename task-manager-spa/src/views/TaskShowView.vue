<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import DeleteIcon from '@/components/Icons/DeleteIcon.vue'
import EditIcon from '@/components/Icons/EditIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
</script>
<template>
    <section class="view-section">
        <div class="view-sub-section">
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span><BackIcon /></span>
                <span>Back to Dashboard</span>
            </router-link>

            <h1 class="view-header">
                <span class="text-emerald-600">TASK </span>
                <span>DETAILS</span>
            </h1>

            <p class="text-lg text-slate-600 text-center mt-10">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nulla, ab.
            </p>

            <div class="flex flex-wrap justify-between items-center gap-5">
                <p class="show-task-paragraph">Priority Number of 10</p>

                <p class="show-task-paragraph">Status : Completed</p>
            </div>
            <p class="show-task-paragraph">Created on</p>

            <p class="show-task-paragraph">Last Edited At</p>

            <div class="flex justify-between items-center flex-wrap gap-3 my-10">
                <router-link :to="{ name: 'edit-task' }" class="view-btn brand-transition">
                    <span> <EditIcon class="text-white" /> </span>
                    <span> Edit Task </span>
                </router-link>

                <button
                    :class="
                        false ? 'form-loading' : 'form-before-loading brand-transition view-btn'
                    "
                >
                    <span v-if="false">
                        <LoadingIcon />
                    </span>
                    <span> {{ false ? 'Marking...' : ' Mark as completed' }} </span>
                </button>

                <button :class="false ? 'deleting-btn' : 'before-delete-btn brand-transition'">
                    <span v-if="true">
                        <DeleteIcon class="text-white" />
                    </span>
                    <span v-if="false">
                        <LoadingIcon class="text-white" />
                    </span>
                    <span> {{ false ? 'Deleting...' : 'Delete Task' }} </span>
                </button>
            </div>
        </div>
    </section>
</template>
