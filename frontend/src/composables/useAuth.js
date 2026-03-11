/**
 * Auth Composable
 * Reactive authentication state using Sanctum tokens.
 * Persists token in localStorage and auto-fetches user on init.
 */
import { ref, computed } from 'vue'
import api, { apiLogin, apiRegister, apiAdminLogin, apiLogout, apiGetMe } from '@/services/api'
import router from '@/router'

const user = ref(null)
const token = ref(localStorage.getItem('auth_token') || null)

const isAuthenticated = computed(() => !!token.value && !!user.value)
const isAdmin = computed(() => user.value?.role === 'admin')

/**
 * Set auth token and persist to localStorage + axios default header.
 */
function setToken(newToken) {
  token.value = newToken
  if (newToken) {
    localStorage.setItem('auth_token', newToken)
  } else {
    localStorage.removeItem('auth_token')
  }
}

/**
 * Clear auth state.
 */
function clearAuth() {
  user.value = null
  setToken(null)
}

/**
 * Fetch current user data from /api/me.
 */
async function fetchUser() {
  if (!token.value) return
  try {
    const res = await apiGetMe()
    user.value = res.data.user
  } catch {
    clearAuth()
  }
}

/**
 * Login with email/password.
 */
async function login(email, password) {
  const res = await apiLogin({ email, password })
  setToken(res.data.token)
  user.value = res.data.user
  return res.data
}

/**
 * Register a new user.
 */
async function register(name, email, password, password_confirmation) {
  const res = await apiRegister({ name, email, password, password_confirmation })
  setToken(res.data.token)
  user.value = res.data.user
  return res.data
}

/**
 * Admin login.
 */
async function adminLogin(email, password) {
  const res = await apiAdminLogin({ email, password })
  setToken(res.data.token)
  user.value = res.data.user
  return res.data
}

/**
 * Logout — revoke token server-side and clear local state.
 */
async function logout() {
  try {
    await apiLogout()
  } catch {
    // ignore errors, clear anyway
  }
  clearAuth()
  router.push('/')
}

/**
 * Initialize: if token exists in localStorage, fetch user data.
 */
async function initAuth() {
  if (token.value) {
    await fetchUser()
  }
}

export function useAuth() {
  return {
    user,
    token,
    isAuthenticated,
    isAdmin,
    login,
    register,
    adminLogin,
    logout,
    fetchUser,
    initAuth,
    clearAuth,
  }
}
