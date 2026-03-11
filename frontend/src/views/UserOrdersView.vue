<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-28 pb-20 px-4 sm:px-6 lg:px-12">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-8">My Orders</h1>

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
            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"
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
            <div class="flex items-center gap-3">
              <span
                :class="statusClass(order.status)"
                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
              >
                {{ statusLabel(order.status) }}
              </span>
              <span class="text-[var(--color-accent-orange)] font-bold text-lg"
                >${{ order.total_price.toFixed(2) }}</span
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
                  Qty: {{ item.quantity }} × ${{ item.price.toFixed(2) }}
                </p>
              </div>
              <span class="text-sm font-medium text-[var(--color-text-light)]"
                >${{ (item.price * item.quantity).toFixed(2) }}</span
              >
            </div>
          </div>

          <!-- Notes -->
          <div v-if="order.notes" class="px-6 pb-4">
            <p class="text-xs text-[var(--color-text-muted)]">
              <span class="font-medium">Notes:</span> {{ order.notes }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getUserOrders } from '@/services/api'

const orders = ref([])
const loading = ref(true)

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

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

onMounted(async () => {
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
