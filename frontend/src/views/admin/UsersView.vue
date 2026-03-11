<template>
  <AdminLayout>
    <div>
      <!-- Search -->
      <div class="mb-6">
        <div class="relative max-w-sm">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)]"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
            />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search users..."
            class="pl-9 pr-4 py-2 rounded-lg bg-[var(--color-primary-dark)] border border-white/10 text-[var(--color-text-light)] text-sm placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] w-full"
          />
        </div>
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
                  User
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Email
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Role
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Orders
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase"
                >
                  Registered
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="u in filteredUsers"
                :key="u.id"
                class="border-b border-white/5 hover:bg-white/5 transition-colors"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold"
                      :class="
                        u.role === 'admin' ? 'bg-[var(--color-accent-orange)]' : 'bg-blue-500'
                      "
                    >
                      {{ u.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="text-[var(--color-text-light)] font-medium">{{ u.name }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-[var(--color-text-muted)] text-sm">{{ u.email }}</td>
                <td class="px-6 py-4">
                  <span
                    :class="
                      u.role === 'admin'
                        ? 'bg-[var(--color-accent-orange)]/20 text-[var(--color-accent-orange)]'
                        : 'bg-blue-500/20 text-blue-400'
                    "
                    class="px-2.5 py-1 rounded-full text-xs font-bold uppercase"
                  >
                    {{ u.role }}
                  </span>
                </td>
                <td class="px-6 py-4 text-[var(--color-text-light)] font-medium">
                  {{ u.orders_count }}
                </td>
                <td class="px-6 py-4 text-[var(--color-text-muted)] text-sm">
                  {{ formatDate(u.created_at) }}
                </td>
              </tr>
              <tr v-if="filteredUsers.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-[var(--color-text-muted)]">
                  No users found
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
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { getAdminUsers } from '@/services/api'

const users = ref([])
const loading = ref(true)
const searchQuery = ref('')

const filteredUsers = computed(() => {
  if (!searchQuery.value) return users.value
  const q = searchQuery.value.toLowerCase()
  return users.value.filter(
    (u) => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q),
  )
})

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

onMounted(async () => {
  try {
    users.value = (await getAdminUsers()).data
  } catch {
  } finally {
    loading.value = false
  }
})
</script>
