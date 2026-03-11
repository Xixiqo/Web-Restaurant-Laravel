/**
 * Cart Composable
 * Reactive cart state persisted in localStorage.
 */
import { ref, computed, watch } from 'vue'

const cartItems = ref(JSON.parse(localStorage.getItem('cart_items') || '[]'))

// Persist to localStorage on change
watch(
  cartItems,
  (items) => {
    localStorage.setItem('cart_items', JSON.stringify(items))
  },
  { deep: true },
)

const cartCount = computed(() => cartItems.value.reduce((sum, item) => sum + item.quantity, 0))

const cartTotal = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.product.price * item.quantity, 0),
)

function addItem(product, quantity = 1) {
  const existing = cartItems.value.find((i) => i.product.id === product.id)
  if (existing) {
    existing.quantity += quantity
  } else {
    cartItems.value.push({ product: { ...product }, quantity })
  }
}

function removeItem(productId) {
  cartItems.value = cartItems.value.filter((i) => i.product.id !== productId)
}

function updateQuantity(productId, quantity) {
  const item = cartItems.value.find((i) => i.product.id === productId)
  if (item) {
    if (quantity <= 0) {
      removeItem(productId)
    } else {
      item.quantity = quantity
    }
  }
}

function clearCart() {
  cartItems.value = []
}

export function useCart() {
  return {
    cartItems,
    cartCount,
    cartTotal,
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
  }
}
