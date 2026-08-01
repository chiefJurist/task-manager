<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import LoadingIcon from '@/components/Icons/LoadingIcon.vue'
import { RouterLink } from 'vue-router'
</script>
<template>
    <section class="view-section">
        <form class="view-sub-section">
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span>
                    <BackIcon />
                </span>
                <span>Back to Dashboard</span>
            </router-link>

            <div class="view-header">CREATE A TASK</div>

            <div class="mt-10">
                <label class="form-label">Task Name</label>
                <input type="text" required placeholder="Input your task name" class="form-input" />
                <!-- <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p> -->
            </div>

            <div class="mt-5">
                <label class="form-label">Choose Associated Project</label>
                <select class="form-input">
                    <option value="">Select a Project</option>
                    <option value="Lorem, ipsum dolor1." class="">Lorem, ipsum dolor1.</option>
                    <option value="lorem, ipsum dolor2.">Lorem, ipsum dolor2.</option>
                    <option value="lorem, ipsum dolor3.">Lorem, ipsum dolor3.</option>
                </select>
                <!-- <p v-if="errorMessage.project" class="form-error">
                    {{ errorMessage.project[0] }}
                </p> -->
            </div>

            <div class="flex justify-center mt-5">
                <button
                    type="submit"
                    :class="false ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="false">
                        <LoadingIcon />
                    </span>

                    <span>{{ false ? 'Creating...' : 'Create Task' }}</span>
                </button>
            </div>
        </form>
    </section>
</template>
