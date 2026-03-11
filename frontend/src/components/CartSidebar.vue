<template>
  <!-- Overlay -->
  <div class="fixed inset-0 z-[60]" @click.self="$emit('close')">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="$emit('close')"></div>

    <!-- Sidebar -->
    <div
      class="absolute right-0 top-0 h-full w-full max-w-md bg-[var(--color-primary-dark)] shadow-2xl border-l border-white/10 flex flex-col"
    >
      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
        <h2 class="text-xl font-serif text-[var(--color-text-light)]">Your Cart</h2>
        <button
          @click="$emit('close')"
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

      <!-- Empty State -->
      <div v-if="cartItems.length === 0" class="flex-1 flex items-center justify-center p-6">
        <div class="text-center">
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
              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
            />
          </svg>
          <p class="text-[var(--color-text-muted)]">Your cart is empty</p>
          <button
            @click="$emit('close')"
            class="mt-4 text-[var(--color-accent-orange)] hover:underline cursor-pointer text-sm"
          >
            Browse Menu
          </button>
        </div>
      </div>

      <!-- Cart Items -->
      <div v-else class="flex-1 overflow-y-auto p-6 space-y-4">
        <div
          v-for="item in cartItems"
          :key="item.product.id"
          class="flex gap-4 bg-[var(--color-primary-light)] rounded-xl p-3 border border-white/5"
        >
          <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 bg-[var(--color-bg-darkest)]">
            <img
              v-if="item.product.image"
              :src="item.product.image"
              :alt="item.product.name"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-medium text-[var(--color-text-light)] truncate">
              {{ item.product.name }}
            </h4>
            <p class="text-xs text-[var(--color-accent-orange)] font-bold">
              ${{ item.product.price.toFixed(2) }}
            </p>
            <div class="flex items-center gap-2 mt-1">
              <button
                @click="updateQuantity(item.product.id, item.quantity - 1)"
                class="w-6 h-6 rounded bg-white/10 text-white text-xs flex items-center justify-center hover:bg-white/20 cursor-pointer"
              >
                -
              </button>
              <span class="text-white text-sm w-4 text-center">{{ item.quantity }}</span>
              <button
                @click="updateQuantity(item.product.id, item.quantity + 1)"
                class="w-6 h-6 rounded bg-white/10 text-white text-xs flex items-center justify-center hover:bg-white/20 cursor-pointer"
              >
                +
              </button>
            </div>
          </div>
          <button
            @click="removeItem(item.product.id)"
            class="self-start p-1 text-[var(--color-text-muted)] hover:text-red-400 cursor-pointer"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-4 h-4"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="cartItems.length > 0" class="border-t border-white/10 p-6 space-y-4">
        <!-- Notes -->
        <div>
          <label class="block text-xs text-[var(--color-text-muted)] mb-1"
            >Order Notes (optional)</label
          >
          <input
            v-model="orderNotes"
            type="text"
            placeholder="Special requests..."
            class="w-full px-3 py-2 rounded-lg bg-[var(--color-form-input)] border border-white/10 text-[var(--color-text-light)] text-sm placeholder-[var(--color-text-muted)]/60 focus:outline-none focus:border-[var(--color-accent-orange)]"
          />
        </div>

        <div class="flex items-center justify-between text-lg">
          <span class="text-[var(--color-text-muted)]">Total</span>
          <span class="font-bold text-[var(--color-text-light)]">${{ cartTotal.toFixed(2) }}</span>
        </div>
        <button
          @click="handleCheckout"
          :disabled="checkingOut"
          class="w-full bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] disabled:opacity-50 text-white py-3 rounded-xl font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
        >
          <div
            v-if="checkingOut"
            class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
          ></div>
          {{ checkingOut ? 'Placing Order...' : 'Place Order' }}
        </button>
        <button
          @click="clearCart"
          class="w-full text-[var(--color-text-muted)] hover:text-red-400 text-sm py-2 cursor-pointer transition-colors"
        >
          Clear Cart
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCart } from '@/composables/useCart'
import { createOrder } from '@/services/api'

const emit = defineEmits(['close'])
const router = useRouter()
const { cartItems, cartTotal, removeItem, updateQuantity, clearCart } = useCart()

const orderNotes = ref('')
const checkingOut = ref(false)

async function handleCheckout() {
  checkingOut.value = true
  try {
    const items = cartItems.value.map((item) => ({
      product_id: item.product.id,
      quantity: item.quantity,
    }))
    await createOrder({ items, notes: orderNotes.value || null })
    clearCart()
    orderNotes.value = ''
    emit('close')
    router.push('/orders')
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to place order. Please try again.')
  } finally {
    checkingOut.value = false
  }
}
</script>
