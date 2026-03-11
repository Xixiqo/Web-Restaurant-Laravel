<template>
  <section id="menu" class="py-24 px-4 sm:px-6 lg:px-12 relative overflow-hidden">
    <!-- Subtle Background Gradient -->
    <div
      class="absolute inset-0 bg-gradient-to-b from-[var(--color-bg-darkest)] via-[#222] to-[var(--color-bg-darkest)] opacity-50 z-0"
    ></div>

    <div class="max-w-7xl mx-auto relative z-10">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-6xl font-serif text-[var(--color-text-light)]">Top Menu</h2>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div
          class="w-12 h-12 border-4 border-[var(--color-accent-orange)] border-t-transparent rounded-full animate-spin"
        ></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-20">
        <p class="text-red-400 text-lg mb-4">{{ error }}</p>
        <button
          @click="fetchProducts"
          class="bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-6 py-2 rounded-full font-medium transition-colors cursor-pointer"
        >
          Try Again
        </button>
      </div>

      <!-- Grid Layout (limited to 6 items) -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="pizza in previewItems"
          :key="pizza.id"
          class="bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group flex flex-col h-full"
        >
          <!-- Pizza Image -->
          <div class="w-full flex justify-center pt-8 pb-4 bg-white">
            <div
              class="w-36 h-36 rounded-full overflow-hidden shadow-lg border-4 border-gray-100 group-hover:scale-110 transition-transform duration-500"
            >
              <img :src="pizza.image" :alt="pizza.name" class="w-full h-full object-cover" />
            </div>
          </div>

          <!-- Content -->
          <div class="flex flex-col flex-1 px-6 pb-6 text-center">
            <h4 class="text-xl font-serif text-[#1e1e1e] font-bold mb-2">{{ pizza.name }}</h4>
            <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">{{ pizza.description }}</p>

            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
              <span class="text-2xl font-bold text-[#1e1e1e]">${{ pizza.price.toFixed(2) }}</span>
              <div class="flex items-center gap-3">
                <div class="flex items-center">
                  <span class="text-[var(--color-accent-orange)] text-sm">★</span>
                  <span class="text-xs font-bold text-gray-700 ml-1">{{
                    pizza.rating.toFixed(1)
                  }}</span>
                </div>
                <button
                  class="bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-5 py-2 rounded-full text-sm font-medium transition-colors shadow-md hover:shadow-lg cursor-pointer"
                >
                  Order
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View Full Menu Link -->
      <div v-if="!loading && !error && menuItems.length > 0" class="text-center mt-12">
        <router-link
          to="/menu"
          class="inline-flex items-center gap-2 border border-[var(--color-accent-orange)] text-[var(--color-accent-orange)] hover:bg-[var(--color-accent-orange)] hover:text-white px-8 py-3 rounded-full font-medium transition-all duration-300"
        >
          View Full Menu
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-4 h-4"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
            />
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { getProducts } from '@/services/api'

const menuItems = ref([])
const loading = ref(true)
const error = ref(null)

// Show only first 6 items on landing page
const previewItems = computed(() => menuItems.value.slice(0, 6))

const fetchProducts = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await getProducts()
    menuItems.value = response.data
  } catch (err) {
    console.error('Failed to fetch products:', err)
    error.value = 'Failed to load menu items. Please check that the API server is running.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProducts()
})
</script>
