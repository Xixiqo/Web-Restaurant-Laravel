<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-28 pb-20 px-4 sm:px-6 lg:px-12">
    <div class="max-w-2xl mx-auto">
      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-20">
        <div class="w-12 h-12 border-4 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"></div>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="text-center py-20">
        <p class="text-red-400 text-lg mb-4">{{ error }}</p>
        <router-link to="/orders" class="text-[var(--color-accent-orange)] hover:underline">← Back to Orders</router-link>
      </div>

      <!-- Payment Content -->
      <div v-else-if="order">
        <h1 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-2">Payment</h1>
        <p class="text-[var(--color-text-muted)] mb-8">Order #{{ order.id }}</p>

        <!-- Order Summary Card -->
        <div class="bg-[var(--color-primary-dark)] rounded-2xl border border-white/5 overflow-hidden shadow-lg mb-6">
          <div class="px-6 py-4 border-b border-white/10">
            <h2 class="text-lg font-medium text-[var(--color-text-light)]">Order Summary</h2>
          </div>
          <div class="px-6 py-4 space-y-3">
            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg overflow-hidden bg-[var(--color-primary-light)] shrink-0">
                <img v-if="item.product?.image" :src="item.product.image" :alt="item.product?.name" class="w-full h-full object-cover" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-[var(--color-text-light)] truncate">{{ item.product?.name || 'Product' }}</p>
                <p class="text-xs text-[var(--color-text-muted)]">Qty: {{ item.quantity }} × Rp {{ formatPrice(item.price) }}</p>
              </div>
              <span class="text-sm font-medium text-[var(--color-text-light)]">Rp {{ formatPrice(item.price * item.quantity) }}</span>
            </div>
          </div>
          <div class="px-6 py-4 border-t border-white/10 flex justify-between items-center">
            <span class="text-[var(--color-text-muted)] font-medium">Total</span>
            <span class="text-xl font-bold text-[var(--color-accent-orange)]">Rp {{ formatPrice(order.total_price) }}</span>
          </div>
        </div>

        <!-- Payment Status Card -->
        <div class="bg-[var(--color-primary-dark)] rounded-2xl border border-white/5 overflow-hidden shadow-lg mb-6">
          <div class="px-6 py-4 border-b border-white/10">
            <h2 class="text-lg font-medium text-[var(--color-text-light)]">Payment Status</h2>
          </div>
          <div class="px-6 py-6">
            <!-- Unpaid -->
            <div v-if="order.payment_status === 'unpaid' || !order.payment_status" class="text-center">
              <div class="w-16 h-16 rounded-full bg-yellow-500/20 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-yellow-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>
              </div>
              <p class="text-[var(--color-text-light)] font-medium mb-1">Awaiting Payment</p>
              <p class="text-[var(--color-text-muted)] text-sm mb-6">Click the button below to pay via Xendit</p>
              <button
                @click="initiatePayment"
                :disabled="paying"
                class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white py-3.5 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 text-lg"
              >
                <div v-if="paying" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
                {{ paying ? 'Creating Invoice...' : 'Pay with Xendit' }}
              </button>
            </div>

            <!-- Pending -->
            <div v-else-if="order.payment_status === 'pending'" class="text-center">
              <div class="w-16 h-16 rounded-full bg-blue-500/20 flex items-center justify-center mx-auto mb-4">
                <div class="w-8 h-8 border-3 border-blue-400 border-t-transparent rounded-full animate-spin"></div>
              </div>
              <p class="text-[var(--color-text-light)] font-medium mb-1">Payment Pending</p>
              <p class="text-[var(--color-text-muted)] text-sm mb-6">Complete your payment on the Xendit page, then click "Check Status" below</p>
              <a
                v-if="order.xendit_payment_url"
                :href="order.xendit_payment_url"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
                Continue Payment
              </a>
              <button
                @click="refreshOrder"
                :disabled="checking"
                class="mt-4 w-full bg-[var(--color-primary-light)] border border-white/10 hover:border-white/30 text-[var(--color-text-light)] py-3 rounded-xl font-medium cursor-pointer transition-all duration-300 flex items-center justify-center gap-2"
              >
                <div v-if="checking" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
                {{ checking ? 'Checking...' : 'Check Payment Status' }}
              </button>
            </div>

            <!-- Paid -->
            <div v-else-if="order.payment_status === 'paid'" class="text-center">
              <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-green-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
              </div>
              <p class="text-green-400 font-bold text-lg mb-1">Payment Successful!</p>
              <p class="text-[var(--color-text-muted)] text-sm mb-1" v-if="order.payment_channel">Paid via {{ order.payment_channel }}</p>
              <p class="text-[var(--color-text-muted)] text-xs" v-if="order.paid_at">{{ formatDate(order.paid_at) }}</p>
            </div>

            <!-- Failed / Expired -->
            <div v-else class="text-center">
              <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-red-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
              </div>
              <p class="text-red-400 font-bold text-lg mb-1">Payment {{ order.payment_status === 'expired' ? 'Expired' : 'Failed' }}</p>
              <p class="text-[var(--color-text-muted)] text-sm mb-6">You can try again below</p>
              <button
                @click="initiatePayment"
                :disabled="paying"
                class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white py-3 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
              >
                <div v-if="paying" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                {{ paying ? 'Creating Invoice...' : 'Retry Payment' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Back to Orders -->
        <router-link
          to="/orders"
          class="flex items-center gap-2 text-[var(--color-text-muted)] hover:text-[var(--color-text-light)] transition-colors text-sm"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
          </svg>
          Back to My Orders
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { getUserOrders, createPayment, checkPaymentStatus } from '@/services/api'

const route = useRoute()

const order = ref(null)
const loading = ref(true)
const paying = ref(false)
const checking = ref(false)
const error = ref(null)

function formatPrice(value) {
  return Number(value).toLocaleString('id-ID')
}

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

async function fetchOrder() {
  try {
    const res = await getUserOrders()
    const found = res.data.find((o) => o.id === Number(route.params.orderId))
    if (found) {
      order.value = found
    } else {
      error.value = 'Order not found.'
    }
  } catch {
    error.value = 'Failed to load order details.'
  } finally {
    loading.value = false
  }
}

async function refreshOrder() {
  checking.value = true
  try {
    // Query Xendit API directly to get real-time payment status
    const res = await checkPaymentStatus(order.value.id)
    order.value.payment_status = res.data.payment_status
    order.value.payment_channel = res.data.payment_channel
    order.value.paid_at = res.data.paid_at
  } catch {
    // Fallback: re-fetch the order list
    await fetchOrder()
  } finally {
    checking.value = false
  }
}

async function initiatePayment() {
  paying.value = true
  try {
    const res = await createPayment(order.value.id)
    const paymentUrl = res.data.payment_url
    if (paymentUrl) {
      // Update local state
      order.value.payment_status = 'pending'
      order.value.xendit_payment_url = paymentUrl
      // Open in new tab
      window.open(paymentUrl, '_blank')
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to create payment. Please try again.')
  } finally {
    paying.value = false
  }
}

onMounted(async () => {
  await fetchOrder()

  // Auto-check status when redirected back from Xendit
  if (route.query.status && order.value && order.value.payment_status === 'pending') {
    await refreshOrder()
  }
})
</script>
