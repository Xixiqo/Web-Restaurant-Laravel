import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) return { el: to.hash, behavior: 'smooth' }
    if (savedPosition) return savedPosition
    return { top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/menu',
      name: 'menu',
      component: () => import('../views/MenuView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/reservation',
      name: 'reservation',
      component: () => import('../views/ReservationView.vue'),
      meta: { requireAuth: true },
    },
    {
      path: '/orders',
      name: 'orders',
      component: () => import('../views/UserOrdersView.vue'),
      meta: { requireAuth: true },
    },

    // ─── Admin Routes ────────────────────────────────────
    {
      path: '/admin/login',
      name: 'admin-login',
      component: () => import('../views/AdminLoginView.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/admin',
      redirect: '/admin/dashboard',
    },
    {
      path: '/admin/dashboard',
      name: 'admin-dashboard',
      component: () => import('../views/admin/DashboardView.vue'),
      meta: { requireAdmin: true },
    },
    {
      path: '/admin/menu',
      name: 'admin-menu',
      component: () => import('../views/admin/MenuManagementView.vue'),
      meta: { requireAdmin: true },
    },
    {
      path: '/admin/orders',
      name: 'admin-orders',
      component: () => import('../views/admin/OrdersView.vue'),
      meta: { requireAdmin: true },
    },
    {
      path: '/admin/users',
      name: 'admin-users',
      component: () => import('../views/admin/UsersView.vue'),
      meta: { requireAdmin: true },
    },
  ],
})

// Navigation Guards
router.beforeEach(async (to, from, next) => {
  const { isAuthenticated, isAdmin, initAuth, token } = useAuth()

  // Initialize auth if token exists but user not loaded yet
  if (token.value && !isAuthenticated.value) {
    await initAuth()
  }

  // Guest only routes (login, register)
  if (to.meta.guestOnly && isAuthenticated.value) {
    return next('/')
  }

  // Requires authentication
  if (to.meta.requireAuth && !isAuthenticated.value) {
    return next('/login')
  }

  // Requires admin
  if (to.meta.requireAdmin && (!isAuthenticated.value || !isAdmin.value)) {
    return next('/admin/login')
  }

  next()
})

export default router
