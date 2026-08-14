<script setup>
import { RouterLink } from 'vue-router'

defineProps(['firstName', 'mobileMenuDisplay', 'logoutLoading'])
defineEmits(['logout', 'toggleMenu'])
</script>

<template>
    <header
        class="fixed top-0 left-0 right-0 z-40 w-full bg-slate-900 border-b border-slate-200 shadow text-white"
    >
        <div class="font-semibold max-w-4xl mx-auto px-6 lg:px-8 py-5">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Hello, {{ firstName }}</h1>

                <!--Desktop Project and Task Buttons-->
                <div class="hidden md:block">
                    <router-link
                        :to="{ name: 'create-project' }"
                        class="desktop-menu-link brand-transition"
                    >
                        Create Project
                    </router-link>

                    <router-link
                        :to="{ name: 'create-task' }"
                        class="desktop-menu-link brand-transition"
                    >
                        Create Task
                    </router-link>

                    <a href="#projects" class="desktop-menu-link brand-transition">
                        View Projects
                    </a>

                    <a href="#tasks" class="desktop-menu-link brand-transition"> View Tasks </a>
                </div>

                <!--Desktop logout button-->
                <div class="hidden md:block">
                    <button
                        @click="$emit('logout')"
                        :class="
                            logoutLoading
                                ? 'bg-red-400 px-5 py-3 rounded mr-3 brand-transition cursor-not-allowed'
                                : 'bg-red-600/80 px-5 py-3 rounded cursor-pointer mr-3 hover:bg-red-400 brand-transition'
                        "
                    >
                        {{ logoutLoading ? 'Logging out...' : 'Logout' }}
                    </button>
                </div>

                <!-- Mobile Menu Button-->
                <div class="md:hidden">
                    <button @click="$emit('toggleMenu')">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="1em"
                            height="1em"
                            viewBox="0 0 24 24"
                            class="text-3xl"
                        >
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path
                                fill="#fff"
                                d="M4 18q-.425 0-.712-.288T3 17t.288-.712T4 16h16q.425 0 .713.288T21 17t-.288.713T20 18zm0-5q-.425 0-.712-.288T3 12t.288-.712T4 11h16q.425 0 .713.288T21 12t-.288.713T20 13zm0-5q-.425 0-.712-.288T3 7t.288-.712T4 6h16q.425 0 .713.288T21 7t-.288.713T20 8z"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="">
            <transition
                enter-active-class="transition ease-out duration-500"
                enter-from-class="transform -translate-y-10 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition ease-in duration-500"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-10 opacity-0"
            >
                <div v-if="mobileMenuDisplay" class="md:hidden border-t border-slate-200">
                    <div class="p-6 space-y-3">
                        <router-link
                            :to="{ name: 'create-project' }"
                            @click="$emit('toggleMenu')"
                            class="mobile-menu-link"
                        >
                            Create Project
                        </router-link>

                        <router-link
                            :to="{ name: 'create-task' }"
                            @click="$emit('toggleMenu')"
                            class="mobile-menu-link"
                        >
                            Create Task
                        </router-link>

                        <a href="#projects" @click="$emit('toggleMenu')" class="mobile-menu-link">
                            View Projects
                        </a>

                        <a href="#tasks" @click="$emit('toggleMenu')" class="mobile-menu-link"
                            >View Tasks</a
                        >

                        <button @click="$emit('logout')" class="mobile-menu-link">
                            {{ logoutLoading ? 'Logging out...' : 'Logout' }}
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </header>
</template>
