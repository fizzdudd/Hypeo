{{-- Elegir producto antes de generar (botón central de la barra) --}}
<x-layouts.app title="Generar campaña" nav="productos">
    <x-top-bar :back="route('productos.index')" title="Generar campaña" />

    <div class="flex flex-col gap-2 px-5 pt-3">
        <h1 class="font-display text-[28px] leading-tight font-bold tracking-tight">¿Para qué producto?</h1>
        <p class="text-[15px] text-muted">La IA usa su descripción y público objetivo.</p>
    </div>

    <div class="flex flex-col gap-2 px-5 pt-4">
        @forelse ($productos as $producto)
            <a href="{{ route('campanas.create', $producto) }}" class="card flex items-center gap-3 p-3 transition hover:border-ink">
                <div class="placeholder-photo size-12 flex-none rounded-xl"></div>
                <div class="flex min-w-0 flex-1 flex-col">
                    <span class="truncate font-semibold">{{ $producto->nombre }}</span>
                    <span class="truncate text-[13px] text-muted">{{ $producto->resumenPublico() }}</span>
                </div>
                <i data-lucide="chevron-right" class="size-5 text-muted-2"></i>
            </a>
        @empty
            <div class="flex flex-col items-center gap-3 rounded-[20px] border-[1.5px] border-dashed border-line-2 px-6 py-10 text-center">
                <p class="font-display text-xl font-semibold">Primero crea un producto</p>
                <a href="{{ route('productos.create') }}" class="text-sm font-semibold text-hype-dark">Nuevo producto →</a>
            </div>
        @endforelse
    </div>
</x-layouts.app>
