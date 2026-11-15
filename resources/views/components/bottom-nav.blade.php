@props(['active' => 'productos'])
<nav aria-label="Navegación principal"
     class="fixed inset-x-0 bottom-0 z-20 mx-auto grid h-[84px] w-full max-w-[480px] grid-cols-3 items-start border-t border-line bg-paper pt-2.5 pb-[env(safe-area-inset-bottom)]">
    <a href="{{ route('productos.index') }}" @class([
        'flex flex-col items-center gap-1 text-xs',
        'font-semibold text-ink' => $active === 'productos',
        'font-medium text-muted-2 hover:text-ink' => $active !== 'productos',
    ]) @if($active === 'productos') aria-current="page" @endif>
        <i data-lucide="package" class="size-6"></i>Productos
    </a>
    <div class="flex justify-center">
        <a href="{{ route('campanas.elegir') }}" aria-label="Generar campaña"
           class="-mt-[26px] flex size-14 items-center justify-center rounded-full bg-hype shadow-[0_8px_20px_-6px_rgba(238,90,47,.6)] transition hover:scale-105">
            <i data-lucide="sparkles" class="size-[26px] text-ink"></i>
        </a>
    </div>
    <a href="{{ route('campanas.index') }}" @class([
        'flex flex-col items-center gap-1 text-xs',
        'font-semibold text-ink' => $active === 'campanas',
        'font-medium text-muted-2 hover:text-ink' => $active !== 'campanas',
    ]) @if($active === 'campanas') aria-current="page" @endif>
        <i data-lucide="layers" class="size-6"></i>Campañas
    </a>
</nav>
