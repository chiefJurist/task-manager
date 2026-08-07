<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import { RouterLink } from 'vue-router'
</script>
<template>
    <section class="view-section">
        <form class="view-sub-section">
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span><BackIcon /></span>
                <span>Back to Dashboard</span>
            </router-link>

            <h1 class="view-header">CREATE A PROJECT</h1>

            <div class="mt-10">
                <label class="form-label">Project Name</label>
                <input
                    type="text"
                    required
                    placeholder="Input your project name"
                    class="form-input"
                />
                <!-- <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p> -->
            </div>

            <div class="flex justify-center my-5">
                <button
                    type="submit"
                    :class="false ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="false">
                        <LoadingIcon />
                    </span>

                    <span>{{ false ? 'Creating...' : 'Create Project' }}</span>
                </button>
            </div>
        </form>
    </section>
</template>
