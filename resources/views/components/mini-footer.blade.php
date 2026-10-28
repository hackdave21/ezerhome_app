<footer class="border-t border-ezer-green-borderLight bg-white px-6 lg:px-12 py-5 text-sm text-ezer-muted">
    <div class="max-w-[1400px] mx-auto flex flex-wrap justify-between gap-2 lg:gap-6">
        <span>© {{ date('Y') }} Ezer Home</span>
        <nav aria-label="Liens utiles" class="flex flex-wrap gap-1 lg:gap-4">
            <a href="{{ url('/confidentialite') }}" class="py-1.5 text-ezer-green hover:underline">Confidentialité</a>
            <a href="{{ url('/cgu') }}" class="py-1.5 text-ezer-green hover:underline">CGU</a>
            <a href="{{ url('/contact') }}" class="py-1.5 text-ezer-green hover:underline">Contact</a>
        </nav>
    </div>
</footer>
