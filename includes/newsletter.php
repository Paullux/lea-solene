<!-- Titre Newsletter -->
<div class="max-w-4xl mx-auto flex items-center gap-6 my-8">
    <div class="flex-1 h-px bg-black/30"></div>
    <h3 class="font-elite text-2xl font-bold tracking-wide text-center whitespace-nowrap">
        Newsletter
    </h3>
    <div class="flex-1 h-px bg-black/30"></div>
</div>
<section class="w-full max-w-lg mx-auto mt-8 mb-20 px-4">
    <div class="relative bg-[#8a7f2f] rounded-lg shadow-xl border border-[#D1CFC0] p-8 text-center overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#556B2F] via-[#D4AF37] to-[#8B4513] opacity-60"></div>

        <h3 class="font-elite text-2xl md:text-3xl text-[#1f1f1f] mb-3 tracking-wide">
            Recevoir une pensée douce
        </h3>

        <p class="font-serif text-[#1f1f1f] mb-6 text-sm md:text-base leading-relaxed italic">
            "Juste de la musique et des mots pour apaiser.<br>Pas de bruit inutile."
        </p>

        <form id="newsletterForm" class="flex flex-col sm:flex-row gap-3 items-center justify-center">
            
            <input 
                type="email" 
                name="email" 
                placeholder="Ton adresse email..." 
                required
                class="w-full sm:w-auto flex-1 font-serif px-4 py-2 bg-white/50 border border-gray-300 rounded focus:outline-none focus:border-[#556B2F] focus:ring-1 focus:ring-[#556B2F] placeholder-[#737373] text-gray-700 transition-all"
            >
            
            <button 
                type="submit" 
                id="btnSubmit"
                class="w-full sm:w-auto font-elite px-6 py-2 bg-[#556B2F] hover:bg-[#435525] text-[#F9F7F2] rounded shadow-md transition-colors duration-300 tracking-wider uppercase text-sm"
            >
                S'inscrire
            </button>
        </form>

        <div id="newsletter-message" class="font-serif text-sm mt-4 hidden"></div>
        <p class="text-xs text-[#1E2D08]/60 mt-3 font-serif italic">
            En t'inscrivant, tu acceptes de recevoir mes actualités par email.<br>
            Pas de spam, promis. Désinscription possible à tout moment.
        </p>
    </div>
</section>