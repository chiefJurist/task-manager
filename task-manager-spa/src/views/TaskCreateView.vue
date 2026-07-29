<script setup>
import { RouterLink } from 'vue-router'
</script>
<template>
    <section class="min-h-screen bg-slate-100 flex justify-center items-center px-3 py-8">
        <form class="bg-white text-slate-700 p-5 rounded-lg shadow">
            <router-link :to="{ name: 'dashboard' }" class="font-semibold text-blue-600 block mb-5">
                Back to Dashboard
            </router-link>

            <div class="text-xl font-bold text-center py-5">Create a Task</div>

            <div class="my-5">
                <label class="form-label">Task Name</label>
                <input type="text" required placeholder="Input your task name" class="form-input" />
                <!-- <p v-if="errorMessage.name" class="form-error">
                    {{ errorMessage.name[0] }}
                </p> -->
            </div>

            <div class="my-5">
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

            <div class="flex justify-center my-10">
                <button
                    type="submit"
                    :class="false ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <img
                        v-if="false"
                        src="../assets/images/mingcute--loading-fill.svg"
                        class="animate-spin inline mr-2"
                    />

                    {{ false ? 'Saving...' : 'Save Task' }}
                </button>
            </div>
        </form>
    </section>
</template>
