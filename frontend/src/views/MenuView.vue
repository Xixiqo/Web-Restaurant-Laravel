<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-28 pb-20">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
      <div class="text-center mb-12">
        <p
          class="text-xs uppercase tracking-[0.2em] text-[var(--color-accent-orange)] mb-3 font-medium"
        >
          Our Menu
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[var(--color-text-light)] mb-4">
          Discover Our Dishes
        </h1>
        <p class="text-[var(--color-text-muted)] max-w-xl mx-auto">
          Handcrafted with the finest ingredients, every dish tells a story of tradition and
          passion.
        </p>
      </div>

      <!-- Search Bar -->
      <div class="max-w-lg mx-auto mb-12">
        <div class="relative">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)]"
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
            placeholder="Search menu items..."
            class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-[var(--color-primary-dark)] border border-white/10 text-[var(--color-text-light)] placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)] transition-colors"
          />
        </div>
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

      <!-- Menu Grid -->
      <div v-else>
        <p
          v-if="filteredProducts.length === 0"
          class="text-center text-[var(--color-text-muted)] py-16 text-lg"
        >
          No items match your search.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="product in filteredProducts"
            :key="product.id"
            class="bg-[var(--color-primary-dark)] rounded-2xl overflow-hidden shadow-xl border border-white/5 hover:border-white/15 transition-all duration-300 hover:-translate-y-1 group flex flex-col"
          >
            <!-- Image -->
            <div class="relative h-48 overflow-hidden">
              <img
                v-if="product.image"
                :src="product.image"
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div
                v-else
                class="w-full h-full bg-[var(--color-primary-light)] flex items-center justify-center text-[var(--color-text-muted)]"
              >
                No image
              </div>
              <!-- Price Tag -->
              <div
                class="absolute top-3 right-3 bg-black/70 backdrop-blur-sm text-[var(--color-accent-orange)] font-bold px-3 py-1 rounded-full text-sm"
              >
                ${{ product.price.toFixed(2) }}
              </div>
            </div>

            <!-- Content -->
            <div class="p-5 flex flex-col flex-1">
              <div class="flex items-start justify-between gap-2 mb-2">
                <h3 class="text-lg font-serif text-[var(--color-text-light)] font-bold">
                  {{ product.name }}
                </h3>
                <div class="flex items-center gap-1 shrink-0">
                  <span class="text-[var(--color-accent-gold)] text-sm">★</span>
                  <span class="text-[var(--color-text-light)] text-xs font-medium">{{
                    product.rating.toFixed(1)
                  }}</span>
                </div>
              </div>
              <p class="text-sm text-[var(--color-text-muted)] line-clamp-2 mb-4 flex-1">
                {{ product.description }}
              </p>

              <button
                @click="handleAddToCart(product)"
                class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white py-2.5 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 text-sm"
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
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
                  />
                </svg>
                Add to Cart
              </button>
            </div>
          </div>
        </div>
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
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { getProducts } from '@/services/api'
import { useAuth } from '@/composables/useAuth'
import { useCart } from '@/composables/useCart'

const router = useRouter()
const { isAuthenticated } = useAuth()
const { addItem } = useCart()

const products = ref([])
const loading = ref(true)
const error = ref(null)
const searchQuery = ref('')

const toast = ref({ show: false, message: '' })
const showToast = (message) => {
  toast.value = { show: true, message }
  setTimeout(() => {
    toast.value.show = false
  }, 2000)
}

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value
  const query = searchQuery.value.toLowerCase()
  return products.value.filter(
    (p) => p.name.toLowerCase().includes(query) || p.description.toLowerCase().includes(query),
  )
})

async function fetchProducts() {
  loading.value = true
  error.value = null
  try {
    const res = await getProducts()
    products.value = res.data
  } catch {
    error.value = 'Failed to load menu. Please try again.'
  } finally {
    loading.value = false
  }
}

function handleAddToCart(product) {
  if (!isAuthenticated.value) {
    router.push('/login')
    return
  }
  addItem(product)
  showToast(`${product.name} added to cart!`)
}

onMounted(fetchProducts)
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
