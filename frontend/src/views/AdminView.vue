<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-36 pb-20 px-4 sm:px-6 lg:px-12">
    <div class="max-w-6xl mx-auto">
      <!-- Header -->
      <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 gap-4"
      >
        <div>
          <h1 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)]">
            Product Management
          </h1>
          <p class="text-[var(--color-text-muted)] mt-2">Manage your restaurant menu items</p>
        </div>
        <button
          @click="openCreateForm"
          class="bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:scale-105 flex items-center gap-2 cursor-pointer shadow-lg"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-5 h-5"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add Product
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-20">
        <div
          class="w-12 h-12 border-4 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"
        ></div>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="text-center py-20">
        <p class="text-red-400 text-lg mb-4">{{ error }}</p>
        <button
          @click="fetchProducts"
          class="bg-[var(--color-accent-orange)] text-white px-6 py-2 rounded-full cursor-pointer"
        >
          Try Again
        </button>
      </div>

      <!-- Products Table -->
      <div
        v-else
        class="bg-[var(--color-primary-dark)] rounded-2xl overflow-hidden shadow-2xl border border-white/5"
      >
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-white/10">
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                >
                  Image
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                >
                  Name
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider hidden md:table-cell"
                >
                  Description
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                >
                  Price
                </th>
                <th
                  class="text-left px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                >
                  Rating
                </th>
                <th
                  class="text-right px-6 py-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="product in products"
                :key="product.id"
                class="border-b border-white/5 hover:bg-white/5 transition-colors"
              >
                <td class="px-6 py-4">
                  <div class="w-14 h-14 rounded-xl overflow-hidden bg-[var(--color-primary-light)]">
                    <img
                      v-if="product.image"
                      :src="product.image"
                      :alt="product.name"
                      class="w-full h-full object-cover"
                    />
                    <div
                      v-else
                      class="w-full h-full flex items-center justify-center text-[var(--color-text-muted)] text-xs"
                    >
                      No img
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-[var(--color-text-light)] font-medium">
                  {{ product.name }}
                </td>
                <td
                  class="px-6 py-4 text-[var(--color-text-muted)] text-sm hidden md:table-cell max-w-xs truncate"
                >
                  {{ product.description }}
                </td>
                <td class="px-6 py-4 text-[var(--color-accent-orange)] font-bold">
                  ${{ product.price.toFixed(2) }}
                </td>
                <td class="px-6 py-4">
                  <span class="text-[var(--color-accent-gold)]">★</span>
                  <span class="text-[var(--color-text-light)] text-sm ml-1">{{
                    product.rating.toFixed(1)
                  }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button
                      @click="openEditForm(product)"
                      class="p-2 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors cursor-pointer"
                      title="Edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                        />
                      </svg>
                    </button>
                    <button
                      @click="confirmDelete(product)"
                      class="p-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors cursor-pointer"
                      title="Delete"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                        />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="products.length === 0" class="text-center py-16">
          <p class="text-[var(--color-text-muted)] text-lg">
            No products yet. Add your first menu item!
          </p>
        </div>
      </div>

      <!-- Success / Error Toast -->
      <Transition name="slide-up">
        <div
          v-if="toast.show"
          :class="`fixed bottom-6 right-6 px-6 py-3 rounded-xl shadow-2xl text-white font-medium z-[70] ${toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'}`"
        >
          {{ toast.message }}
        </div>
      </Transition>

      <!-- Modal Overlay -->
      <Transition name="fade">
        <div
          v-if="showModal"
          class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
          @click.self="closeModal"
        >
          <div
            class="bg-[var(--color-primary-dark)] rounded-2xl w-full max-w-lg shadow-2xl border border-white/10 max-h-[90vh] overflow-y-auto"
          >
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
              <h2 class="text-xl font-serif text-[var(--color-text-light)]">
                {{ editingProduct ? 'Edit Product' : 'Add New Product' }}
              </h2>
              <button
                @click="closeModal"
                class="p-2 rounded-lg hover:bg-white/10 transition-colors text-[var(--color-text-muted)] cursor-pointer"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="px-6 py-5 space-y-5">
              <!-- Name -->
              <div>
                <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                  >Name *</label
                >
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)] focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                  placeholder="e.g. Pizza Pepperoni"
                />
              </div>

              <!-- Description -->
              <div>
                <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                  >Description *</label
                >
                <textarea
                  v-model="form.description"
                  required
                  rows="3"
                  class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)] focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors resize-none"
                  placeholder="Describe the product..."
                ></textarea>
              </div>

              <!-- Price & Rating Row -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                    >Price ($) *</label
                  >
                  <input
                    v-model.number="form.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                    placeholder="0.00"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                    >Rating (0–5)</label
                  >
                  <input
                    v-model.number="form.rating"
                    type="number"
                    step="0.1"
                    min="0"
                    max="5"
                    class="w-full px-4 py-3 rounded-xl bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
                    placeholder="4.5"
                  />
                </div>
              </div>

              <!-- Image Upload -->
              <div>
                <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-2"
                  >Image</label
                >
                <div class="flex items-center gap-4">
                  <!-- Preview -->
                  <div
                    v-if="imagePreview || (editingProduct && editingProduct.image)"
                    class="w-16 h-16 rounded-xl overflow-hidden bg-[var(--color-primary-light)] shrink-0"
                  >
                    <img
                      :src="imagePreview || editingProduct?.image"
                      alt="Preview"
                      class="w-full h-full object-cover"
                    />
                  </div>
                  <label
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-dashed border-white/20 hover:border-[var(--color-accent-orange)] text-[var(--color-text-muted)] hover:text-[var(--color-accent-orange)] transition-colors cursor-pointer"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-5 h-5"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"
                      />
                    </svg>
                    <span class="text-sm">{{ form.image ? 'Change image' : 'Upload image' }}</span>
                    <input
                      type="file"
                      accept="image/*"
                      class="hidden"
                      @change="handleImageSelect"
                    />
                  </label>
                </div>
              </div>

              <!-- Validation Errors -->
              <div
                v-if="validationErrors.length"
                class="bg-red-500/10 border border-red-500/30 rounded-xl p-4"
              >
                <p v-for="(err, i) in validationErrors" :key="i" class="text-red-400 text-sm">
                  {{ err }}
                </p>
              </div>

              <!-- Submit Button -->
              <div class="pt-2">
                <button
                  type="submit"
                  :disabled="submitting"
                  class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white py-3 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
                >
                  <div
                    v-if="submitting"
                    class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
                  ></div>
                  {{
                    submitting ? 'Saving...' : editingProduct ? 'Update Product' : 'Create Product'
                  }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>

      <!-- Delete Confirmation Modal -->
      <Transition name="fade">
        <div
          v-if="showDeleteModal"
          class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
          @click.self="showDeleteModal = false"
        >
          <div
            class="bg-[var(--color-primary-dark)] rounded-2xl w-full max-w-sm shadow-2xl border border-white/10 p-6 text-center"
          >
            <div
              class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 text-red-400"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                />
              </svg>
            </div>
            <h3 class="text-lg font-serif text-[var(--color-text-light)] mb-2">Delete Product</h3>
            <p class="text-[var(--color-text-muted)] text-sm mb-6">
              Are you sure you want to delete
              <strong class="text-[var(--color-text-light)]">{{ deletingProduct?.name }}</strong
              >? This action cannot be undone.
            </p>
            <div class="flex gap-3">
              <button
                @click="showDeleteModal = false"
                class="flex-1 px-4 py-2.5 rounded-xl border border-white/10 text-[var(--color-text-light)] hover:bg-white/5 transition-colors cursor-pointer"
              >
                Cancel
              </button>
              <button
                @click="handleDelete"
                :disabled="submitting"
                class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white transition-colors cursor-pointer disabled:opacity-50"
              >
                {{ submitting ? 'Deleting...' : 'Delete' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getProducts, createProduct, updateProduct, deleteProduct } from '@/services/api'

// State
const products = ref([])
const loading = ref(true)
const error = ref(null)
const showModal = ref(false)
const showDeleteModal = ref(false)
const editingProduct = ref(null)
const deletingProduct = ref(null)
const submitting = ref(false)
const imagePreview = ref(null)
const validationErrors = ref([])

// Toast notification
const toast = ref({ show: false, message: '', type: 'success' })
const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type }
  setTimeout(() => {
    toast.value.show = false
  }, 3000)
}

// Form state
const defaultForm = { name: '', description: '', price: '', rating: '', image: null }
const form = ref({ ...defaultForm })

// Fetch products
const fetchProducts = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await getProducts()
    products.value = res.data
  } catch (err) {
    error.value = 'Failed to load products. Is the API server running?'
  } finally {
    loading.value = false
  }
}

// Open create form
const openCreateForm = () => {
  editingProduct.value = null
  form.value = { ...defaultForm }
  imagePreview.value = null
  validationErrors.value = []
  showModal.value = true
}

// Open edit form
const openEditForm = (product) => {
  editingProduct.value = product
  form.value = {
    name: product.name,
    description: product.description,
    price: product.price,
    rating: product.rating,
    image: null,
  }
  imagePreview.value = null
  validationErrors.value = []
  showModal.value = true
}

// Close modal
const closeModal = () => {
  showModal.value = false
  editingProduct.value = null
  imagePreview.value = null
  validationErrors.value = []
}

// Handle image selection
const handleImageSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.image = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

// Submit form (create or update)
const handleSubmit = async () => {
  submitting.value = true
  validationErrors.value = []

  const formData = new FormData()
  formData.append('name', form.value.name)
  formData.append('description', form.value.description)
  formData.append('price', form.value.price)
  if (form.value.rating !== '' && form.value.rating !== null) {
    formData.append('rating', form.value.rating)
  }
  if (form.value.image) {
    formData.append('image', form.value.image)
  }

  try {
    if (editingProduct.value) {
      await updateProduct(editingProduct.value.id, formData)
      showToast('Product updated successfully!')
    } else {
      await createProduct(formData)
      showToast('Product created successfully!')
    }
    closeModal()
    await fetchProducts()
  } catch (err) {
    if (err.response?.status === 422 && err.response?.data?.errors) {
      const errors = err.response.data.errors
      validationErrors.value = Object.values(errors).flat()
    } else {
      validationErrors.value = ['An unexpected error occurred. Please try again.']
    }
  } finally {
    submitting.value = false
  }
}

// Confirm delete
const confirmDelete = (product) => {
  deletingProduct.value = product
  showDeleteModal.value = true
}

// Handle delete
const handleDelete = async () => {
  submitting.value = true
  try {
    await deleteProduct(deletingProduct.value.id)
    showToast('Product deleted successfully!')
    showDeleteModal.value = false
    deletingProduct.value = null
    await fetchProducts()
  } catch (err) {
    showToast('Failed to delete product.', 'error')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  fetchProducts()
})
</script>

<style scoped>
/* Transition animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
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
