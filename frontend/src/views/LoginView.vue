<template>
  <div
    class="min-h-screen bg-[var(--color-bg-darkest)] flex items-center justify-center px-4 pt-24 pb-12"
  >
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div
          class="w-16 h-16 rounded-full bg-[var(--color-primary-light)] border border-white/10 flex items-center justify-center mx-auto mb-4"
        >
          <span class="text-[var(--color-text-light)] text-xl font-semibold font-serif">SH</span>
        </div>
        <h1 class="text-3xl font-serif text-[var(--color-text-light)]">Welcome to Sunuy House</h1>
        <p class="text-[var(--color-text-muted)] mt-2 text-sm">
          {{ isLoginMode ? 'Sign in to your account' : 'Create a new account' }}
        </p>
      </div>

      <!-- Card -->
      <div
        class="bg-[var(--color-primary-dark)] rounded-2xl shadow-2xl border border-white/5 overflow-hidden"
      >
        <!-- Tab Switcher -->
        <div class="flex border-b border-white/10">
          <button
            @click="isLoginMode = true"
            :class="[
              'flex-1 py-4 text-sm font-medium transition-all duration-300 cursor-pointer',
              isLoginMode
                ? 'text-[var(--color-accent-orange)] border-b-2 border-[var(--color-accent-orange)] bg-white/5'
                : 'text-[var(--color-text-muted)] hover:text-[var(--color-text-light)]',
            ]"
          >
            Sign In
          </button>
          <button
            @click="isLoginMode = false"
            :class="[
              'flex-1 py-4 text-sm font-medium transition-all duration-300 cursor-pointer',
              !isLoginMode
                ? 'text-[var(--color-accent-orange)] border-b-2 border-[var(--color-accent-orange)] bg-white/5'
                : 'text-[var(--color-text-muted)] hover:text-[var(--color-text-light)]',
            ]"
          >
            Register
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="p-6 space-y-5">
          <!-- Name (Register only) -->
          <Transition name="slide-down">
            <div v-if="!isLoginMode">
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Full Name</label
              >
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Enter your name"
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
          </Transition>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
              >Email Address</label
            >
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="you@example.com"
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

          <!-- Confirm Password (Register only) -->
          <Transition name="slide-down">
            <div v-if="!isLoginMode">
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Confirm Password</label
              >
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                placeholder="••••••••"
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
          </Transition>

          <!-- Validation Errors -->
          <div v-if="errors.length" class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
            <p v-for="(err, i) in errors" :key="i" class="text-red-400 text-sm">{{ err }}</p>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white py-3 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
          >
            <div
              v-if="loading"
              class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
            ></div>
            {{ loading ? 'Please wait...' : isLoginMode ? 'Sign In' : 'Create Account' }}
          </button>
        </form>
      </div>

      <!-- Admin Link -->
      <p class="text-center mt-6 text-sm text-[var(--color-text-muted)]">
        Admin?
        <router-link to="/admin/login" class="text-[var(--color-accent-orange)] hover:underline"
          >Sign in here</router-link
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
const { login, register } = useAuth()

const isLoginMode = ref(true)
const loading = ref(false)
const errors = ref([])

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

async function handleSubmit() {
  loading.value = true
  errors.value = []
  try {
    if (isLoginMode.value) {
      await login(form.email, form.password)
    } else {
      await register(form.name, form.email, form.password, form.password_confirmation)
    }
    router.push('/')
  } catch (err) {
    if (err.response?.status === 422 && err.response?.data?.errors) {
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

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
  overflow: hidden;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-10px);
}
.slide-down-enter-to,
.slide-down-leave-from {
  opacity: 1;
  max-height: 200px;
}
</style>
