<script setup></script>
<template>
    <section class="min-h-screen bg-slate-100 flex justify-center items-center px-3 py-8">
        <div class="bg-emerald-50 shadow rounded-lg p-5">
            <router-link
                :to="{ name: 'dashboard' }"
                class="block mt-3 mb-6 text-emerald-600 hover:text-emerald-500 font-medium"
            >
                Back to Dashboard
            </router-link>

            <div class="text-center font-bold text-xl py-3">
                <span class="text-emerald-600">Task </span>
                <span>Details</span>
            </div>

            <div class="font-medium text-slate-500 text-center my-3">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nulla, ab.
            </div>

            <div class="my-3 text-center font-medium text-slate-400">Priority Number 10</div>

            <div
                class="my-10 flex justify-between items-center gap-5 flex-wrap italic text-slate-400"
            >
                <div>Created 10</div>

                <div>Last Edited at 10</div>
            </div>

            <div class="flex justify-between items-center flex-wrap gap-3 my-10">
                <button
                    class="bg-emerald-600 hover:bg-emerald-400 cursor-pointer text-white p-3 rounded-xl font-medium"
                >
                    Mark as Completed
                </button>
                <button
                    class="bg-red-600 hover:bg-red-400 cursor-pointer text-white p-3 rounded-xl font-medium"
                >
                    Delete Task
                </button>
            </div>
            <div></div>
        </div>
    </section>
</template>
