<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-28 pb-20 px-4 sm:px-6 lg:px-12">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-8">My Profile</h1>

      <div class="space-y-6">
        <!-- Profile Info Card -->
        <div class="bg-[var(--color-primary-dark)] rounded-2xl border border-white/5 overflow-hidden shadow-lg">
          <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[var(--color-accent-orange)]">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <h2 class="text-lg font-medium text-[var(--color-text-light)]">Personal Information</h2>
          </div>
          <form @submit.prevent="handleUpdateProfile" class="px-6 py-6 space-y-5">
            <!-- Avatar -->
            <div class="flex items-center gap-4 mb-2">
              <div class="w-16 h-16 rounded-full bg-[var(--color-accent-orange)] flex items-center justify-center text-white text-2xl font-bold font-serif">
                {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
              </div>
              <div>
                <p class="text-[var(--color-text-light)] font-medium text-lg">{{ user?.name }}</p>
                <p class="text-[var(--color-text-muted)] text-sm">{{ user?.email }}</p>
              </div>
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Full Name</label>
              <input
                v-model="profileForm.name"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Email Address</label>
              <input
                v-model="profileForm.email"
                type="email"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Phone Number</label>
              <input
                v-model="profileForm.phone"
                type="tel"
                placeholder="e.g. +62 812 3456 7890"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>

            <div v-if="profileMsg" :class="profileMsg.type === 'success' ? 'text-green-400' : 'text-red-400'" class="text-sm">
              {{ profileMsg.text }}
            </div>

            <button
              type="submit"
              :disabled="profileSaving"
              class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white py-3 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
            >
              <div v-if="profileSaving" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
              {{ profileSaving ? 'Saving...' : 'Save Changes' }}
            </button>
          </form>
        </div>

        <!-- Change Password Card -->
        <div class="bg-[var(--color-primary-dark)] rounded-2xl border border-white/5 overflow-hidden shadow-lg">
          <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[var(--color-accent-orange)]">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
            <h2 class="text-lg font-medium text-[var(--color-text-light)]">Change Password</h2>
          </div>
          <form @submit.prevent="handleChangePassword" class="px-6 py-6 space-y-5">
            <div>
              <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Current Password</label>
              <input
                v-model="passwordForm.current_password"
                type="password"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">New Password</label>
              <input
                v-model="passwordForm.password"
                type="password"
                required
                minlength="8"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Confirm New Password</label>
              <input
                v-model="passwordForm.password_confirmation"
                type="password"
                required
                minlength="8"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>

            <div v-if="passwordMsg" :class="passwordMsg.type === 'success' ? 'text-green-400' : 'text-red-400'" class="text-sm">
              {{ passwordMsg.text }}
            </div>

            <button
              type="submit"
              :disabled="passwordSaving"
              class="w-full bg-[var(--color-primary-light)] border border-white/10 hover:border-white/30 text-[var(--color-text-light)] py-3 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
            >
              <div v-if="passwordSaving" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
              {{ passwordSaving ? 'Changing...' : 'Change Password' }}
            </button>
          </form>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-[var(--color-primary-dark)] rounded-2xl border border-white/5 overflow-hidden shadow-lg">
          <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[var(--color-accent-orange)]">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            <h2 class="text-lg font-medium text-[var(--color-text-light)]">Order History</h2>
          </div>
          <div class="px-6 py-6">
            <div v-if="ordersLoading" class="flex justify-center py-4">
              <div class="w-8 h-8 border-3 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"></div>
            </div>
            <div v-else>
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-[var(--color-primary-light)] rounded-xl p-4 text-center border border-white/5">
                  <p class="text-2xl font-bold text-[var(--color-accent-orange)]">{{ orderStats.totalOrders }}</p>
                  <p class="text-xs text-[var(--color-text-muted)] mt-1">Total Orders</p>
                </div>
                <div class="bg-[var(--color-primary-light)] rounded-xl p-4 text-center border border-white/5">
                  <p class="text-2xl font-bold text-[var(--color-accent-orange)]">Rp {{ formatPrice(orderStats.totalSpent) }}</p>
                  <p class="text-xs text-[var(--color-text-muted)] mt-1">Total Spent</p>
                </div>
              </div>
              <router-link
                to="/orders"
                class="flex items-center justify-center gap-2 text-[var(--color-accent-orange)] hover:underline text-sm font-medium"
              >
                View All Orders →
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { changePassword, getUserOrders } from '@/services/api'

const { user, updateUserProfile } = useAuth()

const profileForm = reactive({
  name: user.value?.name || '',
  email: user.value?.email || '',
  phone: user.value?.phone || '',
})

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const profileSaving = ref(false)
const profileMsg = ref(null)
const passwordSaving = ref(false)
const passwordMsg = ref(null)
const ordersLoading = ref(true)
const orders = ref([])

const orderStats = computed(() => ({
  totalOrders: orders.value.length,
  totalSpent: orders.value.reduce((sum, o) => sum + (o.total_price || 0), 0),
}))

function formatPrice(value) {
  return Number(value).toLocaleString('id-ID')
}

async function handleUpdateProfile() {
  profileSaving.value = true
  profileMsg.value = null
  try {
    await updateUserProfile({
      name: profileForm.name,
      email: profileForm.email,
      phone: profileForm.phone || null,
    })
    profileMsg.value = { type: 'success', text: 'Profile updated successfully!' }
  } catch (err) {
    const errors = err.response?.data?.errors
    const firstError = errors ? Object.values(errors).flat()[0] : null
    profileMsg.value = { type: 'error', text: firstError || err.response?.data?.message || 'Failed to update profile.' }
  } finally {
    profileSaving.value = false
    setTimeout(() => (profileMsg.value = null), 4000)
  }
}

async function handleChangePassword() {
  passwordSaving.value = true
  passwordMsg.value = null
  try {
    await changePassword({
      current_password: passwordForm.current_password,
      password: passwordForm.password,
      password_confirmation: passwordForm.password_confirmation,
    })
    passwordMsg.value = { type: 'success', text: 'Password changed successfully!' }
    passwordForm.current_password = ''
    passwordForm.password = ''
    passwordForm.password_confirmation = ''
  } catch (err) {
    const errors = err.response?.data?.errors
    const firstError = errors ? Object.values(errors).flat()[0] : null
    passwordMsg.value = { type: 'error', text: firstError || err.response?.data?.message || 'Failed to change password.' }
  } finally {
    passwordSaving.value = false
    setTimeout(() => (passwordMsg.value = null), 4000)
  }
}

onMounted(async () => {
  // Sync form with user data
  if (user.value) {
    profileForm.name = user.value.name || ''
    profileForm.email = user.value.email || ''
    profileForm.phone = user.value.phone || ''
  }

  // Load order stats
  try {
    const res = await getUserOrders()
    orders.value = res.data
  } catch {
    // ignore
  } finally {
    ordersLoading.value = false
  }
})
</script>
