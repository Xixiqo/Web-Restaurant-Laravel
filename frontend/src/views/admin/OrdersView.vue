<template>
  <AdminLayout>
    <div>
      <!-- Filter Tabs -->
      <div class="flex flex-wrap gap-2 mb-6">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="activeTab = tab.value"
          :class="[
            'px-4 py-2 rounded-lg text-sm font-medium transition-all cursor-pointer',
            activeTab === tab.value
              ? 'bg-[var(--color-accent-orange)] text-white'
              : 'bg-[var(--color-primary-dark)] text-[var(--color-text-muted)] border border-white/10 hover:text-[var(--color-text-light)]',
          ]"
        >
          {{ tab.label }}
          <span v-if="tab.count !== undefined" class="ml-1 opacity-70">({{ tab.count }})</span>
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-20">
        <div
          class="w-12 h-12 border-4 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"
        ></div>
      </div>

      <!-- Table -->
      <div
        v-else
        class="bg-[var(--color-primary-dark)] rounded-2xl overflow-hidden border border-white/5"
      >
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-white/10">
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Order
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Customer
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase hidden md:table-cell"
                >
                  Items
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Total
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Date
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Status
                </th>
                <th
                  class="text-right px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="order in filteredOrders"
                :key="order.id"
                class="border-b border-white/5 hover:bg-white/5 transition-colors"
              >
                <td class="px-6 py-4 text-[var(--color-text-light)] font-medium">
                  #{{ order.id }}
                </td>
                <td class="px-6 py-4">
                  <p class="text-[var(--color-text-light)] text-sm">{{ order.user?.name }}</p>
                  <p class="text-[var(--color-text-muted)] text-xs">{{ order.user?.email }}</p>
                </td>
                <td class="px-6 py-4 text-[var(--color-text-muted)] text-sm hidden md:table-cell">
                  {{ order.items?.map((i) => i.product?.name).join(', ') || '-' }}
                </td>
                <td class="px-6 py-4 text-[var(--color-accent-orange)] font-bold">
                  ${{ order.total_price.toFixed(2) }}
                </td>
                <td class="px-6 py-4 text-[var(--color-text-muted)] text-sm">
                  {{ formatDate(order.created_at) }}
                </td>
                <td class="px-6 py-4">
                  <span
                    :class="statusClass(order.status)"
                    class="px-2.5 py-1 rounded-full text-xs font-bold uppercase"
                    >{{ statusLabel(order.status) }}</span
                  >
                </td>
                <td class="px-6 py-4 text-right">
                  <select
                    :value="order.status"
                    @change="handleStatusChange(order.id, $event.target.value)"
                    class="bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] text-xs rounded-lg px-2 py-1.5 focus:outline-none focus:border-[var(--color-accent-orange)] cursor-pointer"
                  >
                    <option value="dipesan">Ordered</option>
                    <option value="diantar">Delivering</option>
                    <option value="selesai">Completed</option>
                  </select>
                </td>
              </tr>
              <tr v-if="filteredOrders.length === 0">
                <td colspan="7" class="px-6 py-12 text-center text-[var(--color-text-muted)]">
                  No orders found
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Toast -->
      <Transition name="slide-up">
        <div
          v-if="toast.show"
          class="fixed bottom-6 right-6 px-6 py-3 rounded-xl shadow-2xl text-white font-medium z-[70] bg-green-600"
        >
          {{ toast.message }}
        </div>
      </Transition>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { getAdminOrders, updateOrderStatus } from '@/services/api'

const orders = ref([])
const loading = ref(true)
const activeTab = ref('all')

const toast = ref({ show: false, message: '' })
const showToast = (msg) => {
  toast.value = { show: true, message: msg }
  setTimeout(() => {
    toast.value.show = false
  }, 2500)
}

const tabs = computed(() => [
  { value: 'all', label: 'All', count: orders.value.length },
  {
    value: 'dipesan',
    label: 'Ordered',
    count: orders.value.filter((o) => o.status === 'dipesan').length,
  },
  {
    value: 'diantar',
    label: 'Delivering',
    count: orders.value.filter((o) => o.status === 'diantar').length,
  },
  {
    value: 'selesai',
    label: 'Completed',
    count: orders.value.filter((o) => o.status === 'selesai').length,
  },
])

const filteredOrders = computed(() =>
  activeTab.value === 'all'
    ? orders.value
    : orders.value.filter((o) => o.status === activeTab.value),
)

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
function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

async function handleStatusChange(id, status) {
  try {
    const res = await updateOrderStatus(id, { status })
    const i = orders.value.findIndex((o) => o.id === id)
    if (i !== -1) orders.value[i] = res.data.order
    showToast('Status updated!')
  } catch {
    showToast('Failed to update')
  }
}

onMounted(async () => {
  try {
    orders.value = (await getAdminOrders()).data
  } catch {
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.4s ease;
}
.slide-up-enter-from,
.slide-up-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
