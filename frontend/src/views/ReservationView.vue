<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-28 pb-20 px-4 sm:px-6 lg:px-12">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-12">
        <p
          class="text-xs uppercase tracking-[0.2em] text-[var(--color-accent-orange)] mb-3 font-medium"
        >
          Book a Table
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[var(--color-text-light)] mb-4">
          Make a Reservation
        </h1>
        <p class="text-[var(--color-text-muted)] max-w-xl mx-auto">
          Reserve your table at Sunuy House and experience our culinary artistry in a warm,
          welcoming atmosphere.
        </p>
      </div>

      <!-- Success Message -->
      <div
        v-if="success"
        class="bg-green-500/10 border border-green-500/30 rounded-2xl p-8 text-center mb-8"
      >
        <div
          class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-8 h-8 text-green-400"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
          </svg>
        </div>
        <h3 class="text-xl font-serif text-[var(--color-text-light)] mb-2">
          Reservation Confirmed!
        </h3>
        <p class="text-[var(--color-text-muted)]">
          We look forward to welcoming you. A confirmation has been noted.
        </p>
        <button
          @click="
            success = false
            resetForm()
          "
          class="mt-4 text-[var(--color-accent-orange)] hover:underline cursor-pointer"
        >
          Make another reservation
        </button>
      </div>

      <!-- Form -->
      <div
        v-else
        class="bg-[var(--color-primary-dark)] rounded-2xl shadow-2xl border border-white/5 p-6 sm:p-10"
      >
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Full Name *</label
              >
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Your name"
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Email Address *</label
              >
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="you@example.com"
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
            <!-- Phone -->
            <div>
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Phone Number *</label
              >
              <input
                v-model="form.phone"
                type="tel"
                required
                placeholder="+1 234 567 890"
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
            <!-- Guests -->
            <div>
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Number of Guests *</label
              >
              <input
                v-model.number="form.guests"
                type="number"
                min="1"
                max="20"
                required
                placeholder="2"
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
            <!-- Date -->
            <div>
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Date *</label
              >
              <input
                v-model="form.date"
                type="date"
                required
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-gray-300 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
            <!-- Time -->
            <div>
              <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                >Time *</label
              >
              <input
                v-model="form.time"
                type="time"
                required
                class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-gray-300 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
              />
            </div>
          </div>

          <!-- Special Requests -->
          <div>
            <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
              >Special Requests</label
            >
            <textarea
              v-model="form.special_requests"
              rows="3"
              placeholder="Any allergies, dietary requirements, or special occasions?"
              class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors resize-none"
            ></textarea>
          </div>

          <!-- Errors -->
          <div v-if="errors.length" class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
            <p v-for="(err, i) in errors" :key="i" class="text-red-400 text-sm">{{ err }}</p>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white py-3.5 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
          >
            <div
              v-if="loading"
              class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
            ></div>
            {{ loading ? 'Booking...' : 'Confirm Reservation' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { createReservation } from '@/services/api'
import { useAuth } from '@/composables/useAuth'

const { user } = useAuth()

const loading = ref(false)
const success = ref(false)
const errors = ref([])

const form = reactive({
  name: '',
  email: '',
  phone: '',
  date: '',
  time: '',
  guests: 2,
  special_requests: '',
})

// Pre-fill from user data
if (user.value) {
  form.name = user.value.name || ''
  form.email = user.value.email || ''
}

function resetForm() {
  form.name = user.value?.name || ''
  form.email = user.value?.email || ''
  form.phone = ''
  form.date = ''
  form.time = ''
  form.guests = 2
  form.special_requests = ''
  errors.value = []
}

async function handleSubmit() {
  loading.value = true
  errors.value = []
  try {
    await createReservation(form)
    success.value = true
  } catch (err) {
    if (err.response?.status === 422 && err.response?.data?.errors) {
      errors.value = Object.values(err.response.data.errors).flat()
    } else {
      errors.value = ['Something went wrong. Please try again.']
    }
  } finally {
    loading.value = false
  }
}
</script>
