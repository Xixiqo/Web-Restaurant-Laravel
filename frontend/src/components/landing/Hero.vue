<template>
  <section class="pt-28 pb-16 px-4 sm:px-6 lg:px-12">
    <div class="max-w-7xl mx-auto">
      <!-- Main Hero Card -->
      <div class="w-full bg-[#242424] rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

          <!-- Text Content Left -->
          <div class="p-8 md:p-12 lg:p-16 xl:p-20 flex flex-col justify-center text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[var(--color-primary-light)] border border-white/10 mb-6 w-fit mx-auto md:mx-0">
              <span class="w-2 h-2 rounded-full bg-[var(--color-accent-orange)]"></span>
              <span class="text-xs uppercase tracking-widest text-[var(--color-text-muted)] font-medium">Est. 2010</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-serif text-[var(--color-text-light)] mb-6 leading-[1.1]">
              Welcome to <br/>
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-[#a0a0a0]">Sunuy House</span>
            </h1>

            <p class="text-base sm:text-[17px] text-[var(--color-text-muted)] mb-10 max-w-md mx-auto md:mx-0 font-light leading-relaxed">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem sed justo rhoncus bibendum. Nulla porta maximus stum id justo, sed justo.
            </p>

            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
              <button class="bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-8 py-3.5 rounded-full font-medium transition-all duration-300 hover:scale-105 hover:shadow-[0_0_20px_rgba(240,138,75,0.4)] cursor-pointer">
                Order Now
              </button>

              <div class="flex gap-6">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-[var(--color-primary-light)] flex items-center justify-center text-[var(--color-accent-orange)] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                      <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="text-left">
                    <p class="text-white font-bold text-sm">4.8/5</p>
                    <p class="text-[var(--color-text-muted)] text-xs">Experience</p>
                  </div>
                </div>

                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-[var(--color-primary-light)] flex items-center justify-center text-[var(--color-accent-orange)] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                      <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="text-left">
                    <p class="text-white font-bold text-sm">100+</p>
                    <p class="text-[var(--color-text-muted)] text-xs">Food Quality</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Image Right -->
          <div class="flex items-center justify-center p-6 md:p-8 lg:p-12">
            <div class="w-full max-w-[450px] aspect-square rounded-full overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,0.8)] border-8 border-[#333]/50 hover:scale-105 transition-transform duration-700">
              <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1000&auto=format&fit=crop" alt="Delicious Pepperoni Pizza" class="w-full h-full object-cover" />
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
</script>
