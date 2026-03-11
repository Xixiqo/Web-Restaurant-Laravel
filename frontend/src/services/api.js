/**
 * API Service Module
 * Centralized axios instance and all API functions
 * for communicating with the Laravel backend.
 */
import axios from 'axios'
import router from '@/router'

// Base axios instance pointing to Laravel API
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    Accept: 'application/json',
  },
})

// ─── Request Interceptor: Attach Bearer Token ──────────────────────
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// ─── Response Interceptor: Handle 401 ──────────────────────────────
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      // Only redirect if not already on a login page
      const currentPath = router.currentRoute?.value?.path || ''
      if (!currentPath.includes('login')) {
        router.push('/login')
      }
    }
    return Promise.reject(error)
  },
)

// ─── Auth API Functions ────────────────────────────────────────────
export const apiRegister = (data) => api.post('/register', data)
export const apiLogin = (data) => api.post('/login', data)
export const apiAdminLogin = (data) => api.post('/admin/login', data)
export const apiLogout = () => api.post('/logout')
export const apiGetMe = () => api.get('/me')

// ─── Product API Functions ─────────────────────────────────────────
export const getProducts = () => api.get('/products')
export const getProduct = (id) => api.get(`/products/${id}`)

export const createProduct = (formData) =>
  api.post('/admin/products', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  })

export const updateProduct = (id, formData) => {
  formData.append('_method', 'PUT')
  return api.post(`/admin/products/${id}`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  })
}

export const deleteProduct = (id) => api.delete(`/admin/products/${id}`)

// ─── Order API Functions ───────────────────────────────────────────
export const createOrder = (data) => api.post('/orders', data)
export const getUserOrders = () => api.get('/orders')
export const getAdminOrders = (params) => api.get('/admin/orders', { params })
export const updateOrderStatus = (id, data) => api.patch(`/admin/orders/${id}/status`, data)

// ─── User API Functions (Admin) ────────────────────────────────────
export const getAdminUsers = () => api.get('/admin/users')
export const getAdminDashboard = () => api.get('/admin/dashboard')

// ─── Reservation API Functions ─────────────────────────────────────
export const createReservation = (data) => api.post('/reservations', data)

export default api
