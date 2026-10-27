import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = createRouter({
    history: createWebHistory('/'),
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { top: 0, behavior: 'smooth' }
        }
    },
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/public/HomeView.vue')
        },
        {
            path: '/about',
            name: 'about',
            component: () => import('../views/public/AboutView.vue')
        },
        {
            path: '/admin/login',
            name: 'admin.login',
            component: () => import('../views/admin/AdminLogin.vue'),
            meta: { requiresGuest: true }
        },
        {
            path: '/services',
            name: 'services',
            component: () => import('../views/public/ServicesView.vue')
        },
        {
            path: '/services/:id',
            name: 'service.detail',
            component: () => import('../views/public/ServiceDetailView.vue')
        },
        {
            path: '/projects',
            name: 'projects',
            component: () => import('../views/public/ProjectsView.vue')
        },
        {
            path: '/projects/:id',
            name: 'project.detail',
            component: () => import('../views/public/ProjectDetailView.vue')
        },
        {
            path: '/contact',
            name: 'contact',
            component: () => import('../views/public/ContactView.vue')
        },
        {
            path: '/admin',
            component: () => import('../layouts/AdminLayout.vue'),
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'admin.dashboard',
                    component: () => import('../views/admin/AdminDashboard.vue')
                },
                {
                    path: 'projects',
                    name: 'admin.projects',
                    component: () => import('../views/admin/AdminProjects.vue')
                },
                {
                    path: 'services',
                    name: 'admin.services',
                    component: () => import('../views/admin/AdminServices.vue')
                },
                {
                    path: 'media',
                    name: 'admin.media',
                    component: () => import('../views/admin/AdminMedia.vue')
                },
                {
                    path: 'contacts',
                    name: 'admin.contacts',
                    component: () => import('../views/admin/AdminContacts.vue')
                },
                {
                    path: 'users',
                    name: 'admin.users',
                    component: () => import('../views/admin/AdminUsers.vue'),
                    meta: { adminOnly: true }
                },
                {
                    path: 'pages',
                    name: 'admin.pages',
                    component: () => import('../views/admin/AdminPages.vue'),
                    meta: { adminOnly: true }
                },
                {
                    path: 'pages/:id/sections',
                    name: 'admin.sections',
                    component: () => import('../views/admin/AdminSections.vue'),
                    meta: { adminOnly: true }
                },
                {
                    path: 'code-injections',
                    name: 'admin.code_injections',
                    component: () => import('../views/admin/AdminCodeInjection.vue'),
                    meta: { adminOnly: true }
                },
                {
                    path: 'settings',
                    name: 'admin.settings',
                    component: () => import('../views/admin/AdminSettings.vue'),
                    meta: { adminOnly: true }
                }
            ]
        }
    ]
});

router.beforeEach(async (to) => {
    const auth = useAuthStore()

    // Protected routes
    if (to.meta.requiresAuth || to.matched.some(record => record.meta.requiresAuth)) {
        if (!auth.isLoggedIn) {
            try {
                await auth.fetchUser()
            } catch (err) {
                return { 
                    name: 'admin.login', 
                    query: { redirect: to.fullPath } 
                }
            }
        }

        // Restrict adminOnly routes to users with role 'admin'
        if (to.meta.adminOnly && auth.role !== 'admin') {
            return { name: 'admin.dashboard' }
        }

        return true
    }

    // Guest only routes (like login)
    if (to.meta.requiresGuest && auth.isLoggedIn) {
        return { name: 'admin.dashboard' }
    }
})

export default router
