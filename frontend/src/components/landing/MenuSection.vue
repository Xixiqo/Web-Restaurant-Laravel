<template>
  <section id="menu" class="py-24 px-4 sm:px-6 lg:px-12 relative overflow-hidden">
    <!-- Subtle Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-[var(--color-bg-darkest)] via-[#222] to-[var(--color-bg-darkest)] opacity-50 z-0"></div>

    <div class="max-w-7xl mx-auto relative z-10">

      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-6xl font-serif text-[var(--color-text-light)]">Menu</h2>
      </div>

      <!-- Grid Layout -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div v-for="pizza in menuItems" :key="pizza.id" class="bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group flex flex-col h-full">

          <!-- Pizza Image — inline, not absolute -->
          <div class="w-full flex justify-center pt-8 pb-4 bg-white">
            <div class="w-36 h-36 rounded-full overflow-hidden shadow-lg border-4 border-gray-100 group-hover:scale-110 transition-transform duration-500">
              <img :src="pizza.image" :alt="pizza.name" class="w-full h-full object-cover" />
            </div>
          </div>

          <!-- Content -->
          <div class="flex flex-col flex-1 px-6 pb-6 text-center">
            <h4 class="text-xl font-serif text-[#1e1e1e] font-bold mb-2">{{ pizza.name }}</h4>
            <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">{{ pizza.description }}</p>

            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
              <span class="text-2xl font-bold text-[#1e1e1e]">{{ pizza.price }}</span>
              <div class="flex items-center gap-3">
                <div class="flex items-center">
                  <span class="text-[var(--color-accent-orange)] text-sm">★</span>
                  <span class="text-xs font-bold text-gray-700 ml-1">{{ pizza.rating }}</span>
                </div>
                <button class="bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-5 py-2 rounded-full text-sm font-medium transition-colors shadow-md hover:shadow-lg cursor-pointer">
                  Order
                </button>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
const menuItems = [
  {
    id: 1,
    name: "Pizza Pepperoni",
    description: "Classic pepperoni with extra cheese and our signature sauce.",
    price: "$22",
    rating: "4.9",
    image: "https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=400&auto=format&fit=crop"
  },
  {
    id: 2,
    name: "Pizza Margherita",
    description: "Fresh tomatoes, mozzarella, and basil. A true Italian classic.",
    price: "$18",
    rating: "4.8",
    image: "https://images.unsplash.com/photo-1574071318508-1cdbab80d002?q=80&w=400&auto=format&fit=crop"
  },
  {
    id: 3,
    name: "Pizza Supreme",
    description: "Loaded with all the fixings: meats, veggies, and extra cheese.",
    price: "$26",
    rating: "5.0",
    image: "https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=400&auto=format&fit=crop"
  },
  {
    id: 4,
    name: "Pizza BBQ Chicken",
    description: "Grilled chicken, BBQ sauce, red onions, and cilantro.",
    price: "$24",
    rating: "4.7",
    image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=400&auto=format&fit=crop"
  },
  {
    id: 5,
    name: "Pizza Hawaiian",
    description: "Ham, pineapple, and mozzarella cheese.",
    price: "$20",
    rating: "4.5",
    image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=400&auto=format&fit=crop"
  },
  {
    id: 6,
    name: "Pizza Veggie",
    description: "Bell peppers, onions, mushrooms, olives, and tomatoes.",
    price: "$21",
    rating: "4.6",
    image: "https://images.unsplash.com/photo-1574071318508-1cdbab80d002?q=80&w=400&auto=format&fit=crop"
  }
]
</script>
