<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-28 pb-20 px-4 sm:px-6 lg:px-12">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-8">My Orders</h1>

      <!-- Payment status flash -->
      <div
        v-if="paymentFlash"
        :class="paymentFlash === 'success' ? 'bg-green-500/20 border-green-500/30 text-green-400' : 'bg-red-500/20 border-red-500/30 text-red-400'"
        class="rounded-xl border px-4 py-3 mb-6 text-sm flex items-center gap-2"
      >
        <svg v-if="paymentFlash === 'success'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        {{ paymentFlash === 'success' ? 'Payment successful! Your order is being processed.' : 'Payment failed. You can retry from the order below.' }}
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-20">
        <div
          class="w-12 h-12 border-4 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"
        ></div>
      </div>

      <!-- Empty State -->
      <div v-else-if="orders.length === 0" class="text-center py-20">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1"
          stroke="currentColor"
          class="w-16 h-16 text-[var(--color-text-muted)]/30 mx-auto mb-4"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"
          />
        </svg>
        <p class="text-[var(--color-text-muted)] text-lg mb-4">
          You haven't placed any orders yet.
        </p>
        <router-link to="/menu" class="text-[var(--color-accent-orange)] hover:underline"
          >Browse our menu →</router-link
        >
      </div>

      <!-- Orders List -->
      <div v-else class="space-y-6">
        <div
          v-for="order in orders"
          :key="order.id"
          class="bg-[var(--color-primary-dark)] rounded-2xl border border-white/5 overflow-hidden shadow-lg"
        >
          <!-- Order Header -->
          <div
            class="flex flex-wrap items-center justify-between px-6 py-4 border-b border-white/10 gap-3"
          >
            <div>
              <span class="text-[var(--color-text-muted)] text-sm">Order #{{ order.id }}</span>
              <p class="text-[var(--color-text-light)] font-medium">
                {{ formatDate(order.created_at) }}
              </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
              <!-- Payment Status Badge -->
              <span
                :class="paymentStatusClass(order.payment_status)"
                class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
              >
                {{ paymentStatusLabel(order.payment_status) }}
              </span>
              <!-- Order Status Badge -->
              <span
                :class="statusClass(order.status)"
                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
              >
                {{ statusLabel(order.status) }}
              </span>
              <span class="text-[var(--color-accent-orange)] font-bold text-lg"
                >Rp {{ formatPrice(order.total_price) }}</span
              >
            </div>
          </div>

          <!-- Status Stepper -->
          <div class="px-6 py-4 border-b border-white/10">
            <div class="flex items-center justify-between max-w-xs mx-auto">
              <div v-for="(step, i) in statusSteps" :key="step.key" class="flex items-center">
                <div class="flex flex-col items-center">
                  <div
                    :class="[
                      'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold',
                      stepReached(order.status, step.key)
                        ? 'bg-[var(--color-accent-orange)] text-white'
                        : 'bg-white/10 text-[var(--color-text-muted)]',
                    ]"
                  >
                    {{ i + 1 }}
                  </div>
                  <span class="text-[10px] text-[var(--color-text-muted)] mt-1">{{
                    step.label
                  }}</span>
                </div>
                <div
                  v-if="i < statusSteps.length - 1"
                  :class="[
                    'w-12 sm:w-20 h-0.5 mx-1 mb-5',
                    stepReached(order.status, statusSteps[i + 1].key)
                      ? 'bg-[var(--color-accent-orange)]'
                      : 'bg-white/10',
                  ]"
                ></div>
              </div>
            </div>
          </div>

          <!-- Items -->
          <div class="px-6 py-4 space-y-3">
            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-lg overflow-hidden bg-[var(--color-primary-light)] shrink-0"
              >
                <img
                  v-if="item.product?.image"
                  :src="item.product.image"
                  :alt="item.product?.name"
                  class="w-full h-full object-cover"
                />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-[var(--color-text-light)] truncate">
                  {{ item.product?.name || 'Product' }}
                </p>
                <p class="text-xs text-[var(--color-text-muted)]">
                  Qty: {{ item.quantity }} × Rp {{ formatPrice(item.price) }}
                </p>
              </div>
              <span class="text-sm font-medium text-[var(--color-text-light)]"
                >Rp {{ formatPrice(item.price * item.quantity) }}</span
              >
            </div>
          </div>

          <!-- Notes -->
          <div v-if="order.notes" class="px-6 pb-4">
            <p class="text-xs text-[var(--color-text-muted)]">
              <span class="font-medium">Notes:</span> {{ order.notes }}
            </p>
          </div>

          <!-- Payment Action -->
          <div
            v-if="order.payment_status === 'unpaid' || order.payment_status === 'failed' || order.payment_status === 'expired'"
            class="px-6 pb-4"
          >
            <router-link
              :to="`/payment/${order.id}`"
              class="inline-flex items-center gap-2 bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-300"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
              </svg>
              Pay Now
            </router-link>
          </div>

          <!-- Pending: show both Continue Payment + Check Status -->
          <div
            v-else-if="order.payment_status === 'pending'"
            class="px-6 pb-4 flex flex-wrap items-center gap-3"
          >
            <a
              v-if="order.xendit_payment_url"
              :href="order.xendit_payment_url"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-300"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
              </svg>
              Continue Payment
            </a>
            <button
              @click="handleCheckStatus(order)"
              :disabled="checkingOrderId === order.id"
              class="inline-flex items-center gap-2 bg-[var(--color-primary-light)] border border-white/10 hover:border-white/30 text-[var(--color-text-light)] px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 cursor-pointer disabled:opacity-50"
            >
              <div v-if="checkingOrderId === order.id" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
              <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
              </svg>
              {{ checkingOrderId === order.id ? 'Checking...' : 'Check Status' }}
            </button>
          </div>

          <!-- Paid -->
          <div v-else-if="order.payment_status === 'paid'" class="px-6 pb-4">
            <p class="text-xs text-green-400 flex items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Paid{{ order.payment_channel ? ` via ${order.payment_channel}` : '' }}{{ order.paid_at ? ` • ${formatDate(order.paid_at)}` : '' }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { getUserOrders, checkPaymentStatus } from '@/services/api'

const route = useRoute()
const orders = ref([])
const loading = ref(true)
const paymentFlash = ref(null)
const checkingOrderId = ref(null)

const statusSteps = [
  { key: 'dipesan', label: 'Ordered' },
  { key: 'diantar', label: 'Delivering' },
  { key: 'selesai', label: 'Done' },
]

const statusOrder = { dipesan: 0, diantar: 1, selesai: 2 }

function stepReached(current, target) {
  return (statusOrder[current] ?? -1) >= (statusOrder[target] ?? 99)
}

function statusClass(status) {
  return (
    {
      dipesan: 'bg-yellow-500/20 text-yellow-400',
      diantar: 'bg-blue-500/20 text-blue-400',
      selesai: 'bg-green-500/20 text-green-400',
    }[status] || 'bg-gray-500/20 text-gray-400'
  )
}

function statusLabel(status) {
  return { dipesan: 'Ordered', diantar: 'Delivering', selesai: 'Completed' }[status] || status
}

function paymentStatusClass(paymentStatus) {
  return (
    {
      unpaid: 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20',
      pending: 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
      paid: 'bg-green-500/10 text-green-400 border border-green-500/20',
      failed: 'bg-red-500/10 text-red-400 border border-red-500/20',
      expired: 'bg-gray-500/10 text-gray-400 border border-gray-500/20',
    }[paymentStatus] || 'bg-gray-500/10 text-gray-400 border border-gray-500/20'
  )
}

function paymentStatusLabel(paymentStatus) {
  return (
    { unpaid: 'Unpaid', pending: 'Pending', paid: 'Paid', failed: 'Failed', expired: 'Expired' }[
      paymentStatus
    ] || 'Unpaid'
  )
}

function formatPrice(value) {
  return Number(value).toLocaleString('id-ID')
}

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

async function handleCheckStatus(order) {
  checkingOrderId.value = order.id
  try {
    const res = await checkPaymentStatus(order.id)
    // Update the order in the list reactively
    order.payment_status = res.data.payment_status
    order.payment_channel = res.data.payment_channel
    order.paid_at = res.data.paid_at
  } catch {
    // Silently ignore, user can try again
  } finally {
    checkingOrderId.value = null
  }
}

onMounted(async () => {
  // Check for payment flash from redirect
  const paymentQuery = route.query.payment
  if (paymentQuery === 'success' || paymentQuery === 'failed') {
    paymentFlash.value = paymentQuery
    setTimeout(() => (paymentFlash.value = null), 6000)
  }

  try {
    const res = await getUserOrders()
    orders.value = res.data
  } catch {
    // handled by interceptor
  } finally {
    loading.value = false
  }
})
</script>
