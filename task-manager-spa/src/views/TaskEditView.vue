<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
</script>
<template>
    <section class="min-h-screen bg-slate-50 py-20 px-4">
        <div class="bg-white border border-slate-100 shadow rounded-lg p-6 max-w-3xl mx-auto">
            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <span><BackIcon /></span>
                <span>Back to Dashboard</span>
            </router-link>

            <div class="view-header">
                <span>EDIT </span>
                <span class="text-emerald-600">TASK</span>
            </div>

            <div class="mt-10">
                <label class="form-label">Task Name</label>
                <input type="text" class="form-input" />
            </div>

            <div class="mt-10">
                <h2 class="text-xl font-semibold text-center">CHANGE TASK'S PROJECT</h2>
                <div class="mt-5">
                    <select class="form-input">
                        <option value="Lorem ipsum dolor sit amet.">
                            Lorem ipsum dolor sit amet.
                        </option>
                        <option value="Lorem ipsum dolor sit amet.">
                            Lorem ipsum dolor sit amet.
                        </option>
                        <option value="Lorem ipsum dolor sit amet.">
                            Lorem ipsum dolor sit amet.
                        </option>
                        <option value="Lorem ipsum dolor sit amet.">
                            Lorem ipsum dolor sit amet.
                        </option>
                    </select>
                </div>
            </div>

            <div class="my-10 flex justify-center">
                <button
                    type="submit"
                    :class="false ? 'form-loading' : 'form-before-loading brand-transition'"
                >
                    <span v-if="false">
                        <LoadingIcon />
                    </span>

                    <span>{{ false ? 'Saving...' : 'Save Task' }}</span>
                </button>
            </div>
        </div>
    </section>
</template>
