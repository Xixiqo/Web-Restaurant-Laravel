<template>
  <section id="about" class="py-24 px-4 sm:px-6 lg:px-12">
    <div class="max-w-7xl mx-auto">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-serif text-[var(--color-text-light)]">About Us</h2>
      </div>

      <!-- 3 Column Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">

        <!-- Left Image -->
        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl hover:scale-[1.02] transition-transform duration-500">
          <img src="https://images.unsplash.com/photo-1590947132387-155cc02f3212?q=80&w=800&auto=format&fit=crop" alt="Cheese Pull Pizza" class="w-full h-full object-cover" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>

        <!-- Center Text -->
        <div class="bg-[#2a2a2a] rounded-2xl p-8 lg:p-10 flex flex-col items-center justify-center text-center shadow-xl border border-white/5 group">
          <!-- Decorative Line -->
          <div class="w-16 h-0.5 bg-[var(--color-accent-orange)] mb-6 transition-all duration-500 group-hover:w-24"></div>

          <p class="text-xs uppercase tracking-[0.2em] text-[var(--color-accent-orange)] mb-4 font-medium">True Art</p>
          <h3 class="text-3xl lg:text-4xl font-serif text-[var(--color-text-light)] mb-6 leading-tight">Traditional <br/> & Modern</h3>

          <p class="text-[15px] text-[var(--color-text-muted)] font-light leading-relaxed mb-8">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem sed justo rhoncus bibendum. Nulla porta maximus stum id justo, sed justo. Mauris accumsan.
          </p>

          <button class="border border-[var(--color-accent-orange)] text-[var(--color-accent-orange)] hover:bg-[var(--color-accent-orange)] hover:text-white px-6 py-2 rounded-full font-medium transition-all duration-300 text-sm cursor-pointer">
            Learn More
          </button>
        </div>

        <!-- Right Image -->
        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl hover:scale-[1.02] transition-transform duration-500">
          <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?q=80&w=800&auto=format&fit=crop" alt="Wood fired pizza" class="w-full h-full object-cover" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
</script>
