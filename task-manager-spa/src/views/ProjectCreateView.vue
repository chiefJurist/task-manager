<script setup>
import { RouterLink } from 'vue-router'
</script>
<template>
    <section class="min-h-screen bg-slate-100 flex justify-center items-center px-3 py-8">
        <form class="bg-white text-slate-700 p-5 rounded-lg shadow">
            <router-link
                :to="{ name: 'dashboard' }"
                class="font-semibold text-blue-600 hover:text-blue-400 block mb-5"
            >
                Back to Dashboard
            </router-link>

            <div class="text-xl font-bold text-center py-5">Create a Project</div>

            <div class="my-5">
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

                    {{ false ? 'Saving...' : 'Save Project' }}
                </button>
            </div>
        </form>
    </section>
</template>
