<template>
  <nav
    :class="`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${isScrolled ? 'bg-[var(--color-primary-dark)] shadow-lg py-3' : 'bg-transparent py-6'}`"
  >
    <div class="max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between">
      <!-- Logo Container -->
      <div class="flex items-center gap-3">
        <div
          class="w-10 h-10 rounded-full bg-[var(--color-text-light)]/20 backdrop-blur-sm flex items-center justify-center border border-white/10"
        >
          <span class="text-[var(--color-text-light)] text-sm font-semibold font-serif">SH</span>
        </div>
        <router-link
          to="/"
          class="hidden sm:block text-2xl font-serif tracking-wide text-[var(--color-text-light)]"
        >
          Sunuy House
        </router-link>
      </div>

      <!-- Desktop Links -->
      <div class="hidden md:flex items-center gap-10">
        <router-link
          v-for="link in navLinks"
          :key="link.name"
          :to="link.to"
          class="text-[15px] font-medium text-[var(--color-text-light)] hover:text-[var(--color-accent-orange)] transition-colors duration-300"
        >
          {{ link.name }}
        </router-link>
      </div>

      <!-- Right Side: Cart + Auth -->
      <div class="flex items-center gap-3">
        <!-- Cart Icon (only when authenticated) -->
        <button
          v-if="isAuthenticated"
          @click="showCart = !showCart"
          class="relative p-2 rounded-lg hover:bg-white/10 transition-colors text-[var(--color-text-light)] cursor-pointer"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
            />
          </svg>
          <span
            v-if="cartCount > 0"
            class="absolute -top-1 -right-1 w-5 h-5 bg-[var(--color-accent-orange)] text-white text-xs font-bold rounded-full flex items-center justify-center"
          >
            {{ cartCount > 9 ? '9+' : cartCount }}
          </span>
        </button>

        <!-- Auth Button / User Menu -->
        <div v-if="!isAuthenticated">
          <router-link
            to="/login"
            class="bg-[var(--color-primary-light)] border border-white/10 hover:border-white/30 text-[var(--color-text-light)] px-6 py-2 rounded-full font-medium transition-all duration-300 hover:scale-105 cursor-pointer flex items-center gap-2"
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
                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
              />
            </svg>
            <span class="hidden sm:inline">Log in</span>
          </router-link>
        </div>
        <div v-else class="relative">
          <button
            @click="showUserMenu = !showUserMenu"
            class="bg-[var(--color-primary-light)] border border-white/10 hover:border-white/30 text-[var(--color-text-light)] px-4 py-2 rounded-full font-medium transition-all duration-300 cursor-pointer flex items-center gap-2"
          >
            <div
              class="w-6 h-6 rounded-full bg-[var(--color-accent-orange)] flex items-center justify-center text-white text-xs font-bold"
            >
              {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <span class="hidden sm:inline text-sm">{{ user?.name }}</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
              class="w-3 h-3"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
          </button>

          <!-- Dropdown -->
          <Transition name="dropdown">
            <div
              v-if="showUserMenu"
              class="absolute right-0 mt-2 w-48 bg-[var(--color-primary-dark)] border border-white/10 rounded-xl shadow-2xl py-2 z-50"
            >
              <router-link
                v-if="isAdmin"
                to="/admin/dashboard"
                @click="showUserMenu = false"
                class="flex items-center gap-2 px-4 py-2.5 text-sm text-[var(--color-text-light)] hover:bg-white/10 transition-colors"
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
                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
                  />
                </svg>
                Admin Panel
              </router-link>
              <router-link
                to="/profile"
                @click="showUserMenu = false"
                class="flex items-center gap-2 px-4 py-2.5 text-sm text-[var(--color-text-light)] hover:bg-white/10 transition-colors"
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
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                  />
                </svg>
                Profile
              </router-link>
              <router-link
                to="/orders"
                @click="showUserMenu = false"
                class="flex items-center gap-2 px-4 py-2.5 text-sm text-[var(--color-text-light)] hover:bg-white/10 transition-colors"
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
                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"
                  />
                </svg>
                My Orders
              </router-link>
              <hr class="border-white/10 my-1" />
              <button
                @click="handleLogout"
                class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-400 hover:bg-white/10 transition-colors cursor-pointer"
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
                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"
                  />
                </svg>
                Logout
              </button>
            </div>
          </Transition>
        </div>

        <!-- Mobile Menu Button -->
        <button
          @click="showMobileMenu = !showMobileMenu"
          class="md:hidden text-[var(--color-text-light)] p-2 cursor-pointer"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-7 h-7"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
            />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition name="slide-down">
      <div
        v-if="showMobileMenu"
        class="md:hidden bg-[var(--color-primary-dark)] border-t border-white/10 px-6 py-4 space-y-3"
      >
        <router-link
          v-for="link in navLinks"
          :key="link.name"
          :to="link.to"
          @click="showMobileMenu = false"
          class="block text-[15px] font-medium text-[var(--color-text-light)] hover:text-[var(--color-accent-orange)] transition-colors py-2"
        >
          {{ link.name }}
        </router-link>
      </div>
    </Transition>

    <!-- Cart Sidebar -->
    <CartSidebar v-if="showCart" @close="showCart = false" />
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useCart } from '@/composables/useCart'
import CartSidebar from '@/components/CartSidebar.vue'

const { user, isAuthenticated, isAdmin, logout } = useAuth()
const { cartCount } = useCart()

const navLinks = [
  { name: 'Home', to: '/' },
  { name: 'About', to: '/about' },
  { name: 'Menu', to: '/menu' },
  { name: 'Reservation', to: '/reservation' },
  { name: 'Contact', to: '/#footer' },
]

const isScrolled = ref(false)
const showUserMenu = ref(false)
const showMobileMenu = ref(false)
const showCart = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

const handleLogout = () => {
  showUserMenu.value = false
  logout()
}

// Close dropdown on click outside
const handleClickOutside = (e) => {
  if (!e.target.closest('.relative')) {
    showUserMenu.value = false
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  max-height: 0;
}
.slide-down-enter-to,
.slide-down-leave-from {
  opacity: 1;
  max-height: 300px;
}
</style>
