<template>
  <div class="min-h-screen bg-[#111] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <!-- Admin Branding -->
      <div class="text-center mb-8">
        <div
          class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[var(--color-accent-orange)] to-[#d06a2b] flex items-center justify-center mx-auto mb-4 shadow-lg shadow-orange-500/20"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-8 h-8 text-white"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
            />
          </svg>
        </div>
        <h1 class="text-2xl font-serif text-[var(--color-text-light)]">Admin Panel</h1>
        <p class="text-[var(--color-text-muted)] mt-2 text-sm">Sunuy House Management</p>
      </div>

      <!-- Card -->
      <div class="bg-[var(--color-primary-dark)] rounded-2xl shadow-2xl border border-white/5 p-6">
        <form @submit.prevent="handleSubmit" class="space-y-5">
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
              >Email Address</label
            >
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="admin@sunuyhouse.com"
              class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
              >Password</label
            >
            <input
              v-model="form.password"
              type="password"
              required
              placeholder="••••••••"
              class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
            />
          </div>

          <!-- Errors -->
          <div v-if="errors.length" class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
            <p v-for="(err, i) in errors" :key="i" class="text-red-400 text-sm">{{ err }}</p>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-gradient-to-r from-[var(--color-accent-orange)] to-[#d06a2b] hover:from-[#e07a3b] hover:to-[#c05a1b] disabled:opacity-50 text-white py-3 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20"
          >
            <div
              v-if="loading"
              class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
            ></div>
            {{ loading ? 'Authenticating...' : 'Sign In as Admin' }}
          </button>
        </form>
      </div>

      <!-- Back Link -->
      <p class="text-center mt-6 text-sm text-[var(--color-text-muted)]">
        <router-link to="/" class="text-[var(--color-accent-orange)] hover:underline"
          >← Back to Sunuy House</router-link
        >
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { adminLogin } = useAuth()

const loading = ref(false)
const errors = ref([])

const form = reactive({
  email: '',
  password: '',
})

async function handleSubmit() {
  loading.value = true
  errors.value = []
  try {
    await adminLogin(form.email, form.password)
    router.push('/admin/dashboard')
  } catch (err) {
    if (err.response?.status === 403) {
      errors.value = ['Access denied. Admin privileges required.']
    } else if (err.response?.status === 422 && err.response?.data?.errors) {
      errors.value = Object.values(err.response.data.errors).flat()
    } else if (err.response?.data?.message) {
      errors.value = [err.response.data.message]
    } else {
      errors.value = ['An unexpected error occurred. Please try again.']
    }
  } finally {
    loading.value = false
  }
}
</script>
