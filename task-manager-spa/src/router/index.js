import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/LoginView.vue'),
            meta: { guestOnly: true },
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('../views/RegisterView.vue'),
            meta: { guestOnly: true },
        },
        {
            path: '/forgot-password',
            name: 'forgot-password',
            component: () => import('../views/ForgotPasswordView.vue'),
            meta: { guestOnly: true },
        },
        {
            path: '/',
            name: 'dashboard',
            component: () => import('../views/DashboardView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/create-project',
            name: 'create-project',
            component: () => import('../views/ProjectCreateView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/project-edit/',
            name: 'edit-project',
            component: () => import('../views/ProjectEditView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/create-task',
            name: 'create-task',
            component: () => import('../views/TaskCreateView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/task/:id',
            name: 'show-task',
            component: () => import('../views/TaskShowView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/task-edit/:id',
            name: 'edit-task',
            component: () => import('../views/TaskEditView.vue'),
            meta: { requiresAuth: true },
        },

        {
            path: '/:pathMatch(.*)*',
            redirect: '/login',
        },
    ],
})

router.beforeEach(async (to) => {
    const auth = useAuthStore()

    if (!auth.initialized) {
        await auth.fetchUser()
    }

    if (to.meta.requiresAuth && !auth.user) {
        return { name: 'login' }
    }

    if (to.meta.guestOnly && auth.user) {
        return { name: 'dashboard' }
    }
})

export default router
