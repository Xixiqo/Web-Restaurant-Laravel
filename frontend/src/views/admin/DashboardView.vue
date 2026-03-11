<template>
  <AdminLayout>
    <div>
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div
          v-for="stat in statCards"
          :key="stat.label"
          class="bg-[var(--color-primary-dark)] rounded-2xl p-6 border border-white/5"
        >
          <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-[var(--color-text-muted)]">{{ stat.label }}</span>
            <div :class="stat.iconBg" class="w-10 h-10 rounded-xl flex items-center justify-center">
              <div v-html="stat.icon" class="w-5 h-5"></div>
            </div>
          </div>
          <p class="text-2xl font-bold text-[var(--color-text-light)]">{{ stat.value }}</p>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="bg-[var(--color-primary-dark)] rounded-2xl border border-white/5 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
          <h3 class="text-lg font-serif text-[var(--color-text-light)]">Recent Orders</h3>
          <router-link
            to="/admin/orders"
            class="text-sm text-[var(--color-accent-orange)] hover:underline"
            >View All →</router-link
          >
        </div>
        <div v-if="loading" class="flex justify-center py-12">
          <div
            class="w-10 h-10 border-4 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"
          ></div>
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-white/10">
                <th
                  class="text-left px-6 py-3 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Order
                </th>
                <th
                  class="text-left px-6 py-3 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Customer
                </th>
                <th
                  class="text-left px-6 py-3 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Items
                </th>
                <th
                  class="text-left px-6 py-3 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Total
                </th>
                <th
                  class="text-left px-6 py-3 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Status
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="order in recentOrders"
                :key="order.id"
                class="border-b border-white/5 hover:bg-white/5"
              >
                <td class="px-6 py-4 text-[var(--color-text-light)] font-medium">
                  #{{ order.id }}
                </td>
                <td class="px-6 py-4 text-[var(--color-text-muted)]">
                  {{ order.user?.name || 'N/A' }}
                </td>
                <td class="px-6 py-4 text-[var(--color-text-muted)]">
                  {{ order.items_count }} items
                </td>
                <td class="px-6 py-4 text-[var(--color-accent-orange)] font-bold">
                  ${{ order.total_price.toFixed(2) }}
                </td>
                <td class="px-6 py-4">
                  <span
                    :class="statusClass(order.status)"
                    class="px-2.5 py-1 rounded-full text-xs font-bold uppercase"
                  >
                    {{ statusLabel(order.status) }}
                  </span>
                </td>
              </tr>
              <tr v-if="recentOrders.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-[var(--color-text-muted)]">
                  No orders yet
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { getAdminDashboard } from '@/services/api'

const loading = ref(true)
const recentOrders = ref([])
const statCards = ref([
  {
    label: 'Total Orders',
    value: '0',
    iconBg: 'bg-blue-500/10 text-blue-400',
    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>',
  },
  {
    label: 'Pending',
    value: '0',
    iconBg: 'bg-yellow-500/10 text-yellow-400',
    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
  },
  {
    label: 'Revenue',
    value: '$0',
    iconBg: 'bg-green-500/10 text-green-400',
    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
  },
  {
    label: 'Menu Items',
    value: '0',
    iconBg: 'bg-purple-500/10 text-purple-400',
    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0L3 16.5m15-3.379a48.474 48.474 0 0 0-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 0 1 3 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 0 1 6 13.12M12.265 3.11a.375.375 0 1 1-.53 0L12 2.845l.265.265Z" /></svg>',
  },
])

function statusClass(s) {
  return (
    {
      dipesan: 'bg-yellow-500/20 text-yellow-400',
      diantar: 'bg-blue-500/20 text-blue-400',
      selesai: 'bg-green-500/20 text-green-400',
    }[s] || 'bg-gray-500/20 text-gray-400'
  )
}
function statusLabel(s) {
  return { dipesan: 'Ordered', diantar: 'Delivering', selesai: 'Completed' }[s] || s
}

onMounted(async () => {
  try {
    const res = await getAdminDashboard()
    const d = res.data
    statCards.value[0].value = String(d.total_orders)
    statCards.value[1].value = String(d.pending_orders)
    statCards.value[2].value = `$${d.total_revenue.toFixed(2)}`
    statCards.value[3].value = String(d.total_menu_items)
    recentOrders.value = d.recent_orders
  } catch {
    // handled by interceptor
  } finally {
    loading.value = false
  }
})
</script>
