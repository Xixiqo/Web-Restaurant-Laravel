<template>
  <AdminLayout>
    <div>
      <!-- Header Row -->
      <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <!-- Search -->
        <div class="relative w-full max-w-sm">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)]">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name, email, or phone..."
            class="pl-9 pr-4 py-2 rounded-lg bg-[var(--color-primary-dark)] border border-white/10 text-[var(--color-text-light)] text-sm placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] w-full"
          />
        </div>

        <!-- Add User Button -->
        <button
          @click="openCreate"
          class="flex items-center gap-2 bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer shrink-0"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
          </svg>
          Add User
        </button>
      </div>

      <!-- Role Filter Tabs -->
      <div class="flex gap-2 mb-6 flex-wrap">
        <button
          v-for="tab in roleTabs"
          :key="tab.value"
          @click="roleFilter = tab.value"
          :class="[
            'px-4 py-1.5 rounded-lg text-sm font-medium transition-all cursor-pointer',
            roleFilter === tab.value
              ? 'bg-[var(--color-accent-orange)] text-white'
              : 'bg-[var(--color-primary-dark)] text-[var(--color-text-muted)] border border-white/10 hover:text-[var(--color-text-light)]',
          ]"
        >
          {{ tab.label }}
          <span class="ml-1 opacity-70">({{ tab.count }})</span>
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-20">
        <div class="w-12 h-12 border-4 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"></div>
      </div>

      <!-- Table -->
      <div v-else class="bg-[var(--color-primary-dark)] rounded-2xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-white/10">
                <th class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase">User</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase">Email</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase hidden md:table-cell">Phone</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase">Role</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase">Orders</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase hidden lg:table-cell">Registered</th>
                <th class="text-right px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="u in filteredUsers"
                :key="u.id"
                class="border-b border-white/5 hover:bg-white/5 transition-colors"
              >
                <!-- Avatar + Name -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                      :class="roleAvatarClass(u.role)"
                    >
                      {{ u.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="text-[var(--color-text-light)] font-medium">{{ u.name }}</span>
                  </div>
                </td>
                <!-- Email -->
                <td class="px-6 py-4 text-[var(--color-text-muted)] text-sm">{{ u.email }}</td>
                <!-- Phone -->
                <td class="px-6 py-4 text-[var(--color-text-muted)] text-sm hidden md:table-cell">{{ u.phone || '-' }}</td>
                <!-- Role Badge -->
                <td class="px-6 py-4">
                  <span :class="roleBadgeClass(u.role)" class="px-2.5 py-1 rounded-full text-xs font-bold uppercase">
                    {{ roleLabel(u.role) }}
                  </span>
                </td>
                <!-- Orders Count -->
                <td class="px-6 py-4 text-[var(--color-text-light)] font-medium">{{ u.orders_count }}</td>
                <!-- Registered -->
                <td class="px-6 py-4 text-[var(--color-text-muted)] text-sm hidden lg:table-cell">{{ formatDate(u.created_at) }}</td>
                <!-- Actions -->
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <!-- Edit -->
                    <button
                      v-if="u.role !== 'admin'"
                      @click="openEdit(u)"
                      class="p-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 transition-colors cursor-pointer"
                      title="Edit user"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                      </svg>
                    </button>
                    <!-- Delete -->
                    <button
                      v-if="u.role !== 'admin'"
                      @click="confirmDelete(u)"
                      class="p-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 transition-colors cursor-pointer"
                      title="Delete user"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                      </svg>
                    </button>
                    <!-- Admin badge (no actions) -->
                    <span v-if="u.role === 'admin'" class="text-xs text-[var(--color-text-muted)] italic">Super Admin</span>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredUsers.length === 0">
                <td colspan="7" class="px-6 py-12 text-center text-[var(--color-text-muted)]">No users found</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ─── Create / Edit Modal ─────────────────────────────────────────── -->
      <Transition name="modal">
        <div
          v-if="modal.open"
          class="fixed inset-0 z-50 flex items-center justify-center p-4"
          @click.self="closeModal"
        >
          <!-- Backdrop -->
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

          <!-- Panel -->
          <div class="relative bg-[var(--color-primary-dark)] rounded-2xl border border-white/10 shadow-2xl w-full max-w-md">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
              <h3 class="text-lg font-semibold text-[var(--color-text-light)]">
                {{ modal.mode === 'create' ? 'Add New User' : 'Edit User' }}
              </h3>
              <button @click="closeModal" class="text-[var(--color-text-muted)] hover:text-[var(--color-text-light)] transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="handleSubmit" class="px-6 py-6 space-y-4">
              <!-- Info banner for context -->
              <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl px-4 py-3 text-xs text-blue-300 flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 shrink-0 mt-0.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
                <span>Restaurant workers should request an account from admin. Create accounts here for staff or customers.</span>
              </div>

              <!-- Name -->
              <div>
                <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Full Name <span class="text-red-400">*</span></label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  placeholder="e.g. Budi Santoso"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                />
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Email Address <span class="text-red-400">*</span></label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  placeholder="e.g. budi@email.com"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                />
              </div>

              <!-- Phone -->
              <div>
                <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Phone Number</label>
                <input
                  v-model="form.phone"
                  type="tel"
                  placeholder="e.g. +62 812 3456 7890"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                />
              </div>

              <!-- Role -->
              <div>
                <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Role <span class="text-red-400">*</span></label>
                <select
                  v-model="form.role"
                  required
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors cursor-pointer"
                >
                  <option value="user">User (Customer)</option>
                  <option value="sub-admin">Sub-Admin (Restaurant Staff)</option>
                </select>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">
                  Sub-admins can manage orders and products but cannot manage users.
                </p>
              </div>

              <!-- Password -->
              <div>
                <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">
                  Password <span class="text-red-400">*</span>
                  <span v-if="modal.mode === 'edit'" class="text-[10px] ml-1">(leave blank to keep current)</span>
                </label>
                <input
                  v-model="form.password"
                  type="password"
                  :required="modal.mode === 'create'"
                  placeholder="Min. 8 characters"
                  minlength="8"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                />
              </div>

              <!-- Confirm Password -->
              <div>
                <label class="block text-sm text-[var(--color-text-muted)] mb-1.5">Confirm Password</label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  :required="modal.mode === 'create' || !!form.password"
                  placeholder="Repeat password"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                />
              </div>

              <!-- Error -->
              <div v-if="formError" class="text-sm text-red-400 bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-2.5">
                {{ formError }}
              </div>

              <!-- Actions -->
              <div class="flex gap-3 pt-2">
                <button
                  type="button"
                  @click="closeModal"
                  class="flex-1 py-2.5 rounded-xl border border-white/10 text-[var(--color-text-muted)] hover:text-[var(--color-text-light)] hover:border-white/30 transition-all text-sm font-medium cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="submitting"
                  class="flex-1 py-2.5 rounded-xl bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white text-sm font-medium transition-all cursor-pointer flex items-center justify-center gap-2"
                >
                  <div v-if="submitting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                  {{ submitting ? 'Saving...' : (modal.mode === 'create' ? 'Create User' : 'Save Changes') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>

      <!-- ─── Delete Confirm Modal ───────────────────────────────────────── -->
      <Transition name="modal">
        <div
          v-if="deleteTarget"
          class="fixed inset-0 z-50 flex items-center justify-center p-4"
          @click.self="deleteTarget = null"
        >
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
          <div class="relative bg-[var(--color-primary-dark)] rounded-2xl border border-white/10 shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-red-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-[var(--color-text-light)] mb-1">Delete User</h3>
            <p class="text-sm text-[var(--color-text-muted)] mb-6">
              Are you sure you want to delete <strong class="text-[var(--color-text-light)]">{{ deleteTarget?.name }}</strong>?
              This action cannot be undone.
            </p>
            <div class="flex gap-3">
              <button
                @click="deleteTarget = null"
                class="flex-1 py-2.5 rounded-xl border border-white/10 text-[var(--color-text-muted)] hover:text-[var(--color-text-light)] hover:border-white/30 transition-all text-sm font-medium cursor-pointer"
              >
                Cancel
              </button>
              <button
                @click="handleDelete"
                :disabled="deleting"
                class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white text-sm font-medium transition-all cursor-pointer flex items-center justify-center gap-2"
              >
                <div v-if="deleting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                {{ deleting ? 'Deleting...' : 'Delete' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>

      <!-- Toast -->
      <Transition name="slide-up">
        <div
          v-if="toast.show"
          :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
          class="fixed bottom-6 right-6 px-6 py-3 rounded-xl shadow-2xl text-white font-medium z-[70] flex items-center gap-2"
        >
          <svg v-if="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
          </svg>
          {{ toast.message }}
        </div>
      </Transition>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { getAdminUsers, createAdminUser, updateAdminUser, deleteAdminUser } from '@/services/api'

// ─── State ────────────────────────────────────────────────────────────
const users = ref([])
const loading = ref(true)
const searchQuery = ref('')
const roleFilter = ref('all')

// ─── Role Tabs ────────────────────────────────────────────────────────
const roleTabs = computed(() => [
  { value: 'all',       label: 'All',       count: users.value.length },
  { value: 'user',      label: 'Customers', count: users.value.filter(u => u.role === 'user').length },
  { value: 'sub-admin', label: 'Staff',     count: users.value.filter(u => u.role === 'sub-admin').length },
  { value: 'admin',     label: 'Admin',     count: users.value.filter(u => u.role === 'admin').length },
])

// ─── Filtered Users ───────────────────────────────────────────────────
const filteredUsers = computed(() => {
  let result = users.value
  if (roleFilter.value !== 'all') {
    result = result.filter(u => u.role === roleFilter.value)
  }
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(u =>
      u.name.toLowerCase().includes(q) ||
      u.email.toLowerCase().includes(q) ||
      (u.phone && u.phone.includes(q))
    )
  }
  return result
})

// ─── Modal ────────────────────────────────────────────────────────────
const modal = reactive({ open: false, mode: 'create', editId: null })
const form = reactive({ name: '', email: '', phone: '', role: 'user', password: '', password_confirmation: '' })
const submitting = ref(false)
const formError = ref(null)

function resetForm() {
  form.name = ''
  form.email = ''
  form.phone = ''
  form.role = 'user'
  form.password = ''
  form.password_confirmation = ''
  formError.value = null
}

function openCreate() {
  resetForm()
  modal.mode = 'create'
  modal.editId = null
  modal.open = true
}

function openEdit(u) {
  resetForm()
  form.name  = u.name
  form.email = u.email
  form.phone = u.phone || ''
  form.role  = u.role
  modal.mode   = 'edit'
  modal.editId = u.id
  modal.open   = true
}

function closeModal() {
  modal.open = false
  formError.value = null
}

async function handleSubmit() {
  submitting.value = true
  formError.value = null
  try {
    const payload = {
      name:                  form.name,
      email:                 form.email,
      phone:                 form.phone || null,
      role:                  form.role,
      password:              form.password || undefined,
      password_confirmation: form.password_confirmation || undefined,
    }

    if (modal.mode === 'create') {
      const res = await createAdminUser(payload)
      users.value.unshift(res.data.user)
      showToast('User created successfully!', 'success')
    } else {
      const res = await updateAdminUser(modal.editId, payload)
      const idx = users.value.findIndex(u => u.id === modal.editId)
      if (idx !== -1) users.value[idx] = res.data.user
      showToast('User updated successfully!', 'success')
    }
    closeModal()
  } catch (err) {
    const errors = err.response?.data?.errors
    const firstError = errors ? Object.values(errors).flat()[0] : null
    formError.value = firstError || err.response?.data?.message || 'An error occurred. Please try again.'
  } finally {
    submitting.value = false
  }
}

// ─── Delete ───────────────────────────────────────────────────────────
const deleteTarget = ref(null)
const deleting = ref(false)

function confirmDelete(u) {
  deleteTarget.value = u
}

async function handleDelete() {
  deleting.value = true
  try {
    await deleteAdminUser(deleteTarget.value.id)
    users.value = users.value.filter(u => u.id !== deleteTarget.value.id)
    showToast('User deleted successfully.', 'success')
    deleteTarget.value = null
  } catch (err) {
    showToast(err.response?.data?.message || 'Failed to delete user.', 'error')
  } finally {
    deleting.value = false
  }
}

// ─── Toast ────────────────────────────────────────────────────────────
const toast = reactive({ show: false, message: '', type: 'success' })
function showToast(message, type = 'success') {
  toast.message = message
  toast.type    = type
  toast.show    = true
  setTimeout(() => { toast.show = false }, 3000)
}

// ─── Helpers ──────────────────────────────────────────────────────────
function roleLabel(role) {
  return { user: 'Customer', 'sub-admin': 'Staff', admin: 'Admin' }[role] || role
}
function roleBadgeClass(role) {
  return {
    admin:       'bg-[var(--color-accent-orange)]/20 text-[var(--color-accent-orange)]',
    'sub-admin': 'bg-purple-500/20 text-purple-400',
    user:        'bg-blue-500/20 text-blue-400',
  }[role] || 'bg-gray-500/20 text-gray-400'
}
function roleAvatarClass(role) {
  return {
    admin:       'bg-[var(--color-accent-orange)]',
    'sub-admin': 'bg-purple-500',
    user:        'bg-blue-500',
  }[role] || 'bg-gray-500'
}
function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

// ─── Load ─────────────────────────────────────────────────────────────
onMounted(async () => {
  try {
    users.value = (await getAdminUsers()).data
  } catch {
    // handled by interceptor
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
.modal-enter-from .relative,
.modal-leave-to .relative {
  transform: scale(0.95);
}

.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}
.slide-up-enter-from,
.slide-up-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
