<script setup>
import BackIcon from '@/components/Icons/BackIcon.vue'
import DeleteIcon from '@/components/Icons/DeleteIcon.vue'
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
                <span class="text-emerald-600">PROJECT</span>
            </div>

            <div class="mt-10">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-input" />
            </div>

            <div class="mt-10">
                <h2 class="text-xl font-semibold text-center">REMOVE PROJECT TASKS</h2>
                <div class="flex flex-col gap-5 mt-5">
                    <div class="flex items-center gap-5">
                        <input type="text" class="form-input" />
                        <button
                            class="bg-red-600 hover:bg-red-400 cursor-pointer p-2 rounded-full text-white"
                        >
                            <DeleteIcon />
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" class="form-input" />
                        <button
                            class="bg-red-600 hover:bg-red-400 cursor-pointer p-2 rounded-full text-white"
                        >
                            <DeleteIcon />
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" class="form-input" />
                        <button
                            class="bg-red-600 hover:bg-red-400 cursor-pointer p-2 rounded-full text-white"
                        >
                            <DeleteIcon />
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <h2 class="text-xl font-semibold text-center">ADD NEW TASK</h2>
                <div class="mt-5">
                    <label class="w-full block my-5">
                        <input type="checkbox" value="Laravel" class="peer hidden" />
                        <span
                            class="py-3 px-4 w-full ring ring-emerald-500 cursor-pointer rounded-lg brand-transition peer-checked:bg-emerald-600 peer-checked:text-white block"
                        >
                            Lorem ipsum dolor sit amet consectetur.
                        </span>
                    </label>

                    <label class="w-full block my-4">
                        <input type="checkbox" value="Laravel" class="peer hidden" />
                        <span
                            class="py-3 px-4 w-full ring ring-emerald-500 cursor-pointer rounded-lg brand-transition peer-checked:bg-emerald-600 peer-checked:text-white block"
                        >
                            Lorem ipsum dolor sit amet consectetur.
                        </span>
                    </label>

                    <label class="w-full block my-4">
                        <input type="checkbox" value="Laravel" class="peer hidden" />
                        <span
                            class="py-3 px-4 w-full ring ring-emerald-500 cursor-pointer rounded-lg brand-transition peer-checked:bg-emerald-600 peer-checked:text-white block"
                        >
                            Lorem ipsum dolor sit amet consectetur.
                        </span>
                    </label>
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
