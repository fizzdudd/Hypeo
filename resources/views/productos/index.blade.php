{{-- 03 · Mis productos (inicio) --}}
<x-layouts.app title="Mis productos" nav="productos">
    <div class="flex items-center justify-between px-5 pt-5">
        <div class="font-display text-2xl font-bold tracking-tight">hypeo<span class="text-hype">.</span></div>
        <a href="{{ route('perfil') }}" aria-label="Perfil"
           class="flex size-10 items-center justify-center rounded-full bg-ink text-[15px] font-semibold text-paper">{{ auth()->user()->iniciales() }}</a>
    </div>

    <div class="flex flex-col gap-1 px-5 pt-5">
        <span class="text-base text-muted">Hola, {{ auth()->user()->primerNombre() }}</span>
        <h1 class="font-display text-[34px] leading-[1.04] font-bold tracking-tight">¿Qué vamos a<br>vender hoy?</h1>
    </div>

    <div class="mx-5 mt-[18px] flex gap-2">
        <form method="GET" action="{{ route('productos.index') }}" role="search"
              class="flex h-[46px] flex-1 items-center gap-2.5 rounded-[14px] border-[1.5px] border-line bg-white px-3.5 focus-within:border-ink">
            <i data-lucide="search" class="size-[18px] text-muted-2"></i>
            <input name="q" value="{{ $busqueda }}" placeholder="Buscar producto" aria-label="Buscar producto"
                   class="w-full bg-transparent text-[15px] outline-none placeholder:text-muted-2">
        </form>
        <a href="{{ route('productos.create') }}"
           class="flex h-[46px] items-center gap-1.5 rounded-[14px] bg-ink px-3.5 text-[15px] font-semibold text-paper hover:bg-ink-2">
            <i data-lucide="plus" class="size-[18px]"></i>Nuevo
        </a>
    </div>

    <div class="flex justify-between px-5 pt-5 font-mono text-xs font-medium tracking-[.06em] text-muted">
        <span>{{ $totalProductos }} {{ $totalProductos === 1 ? 'PRODUCTO' : 'PRODUCTOS' }}</span>
        <span>{{ $totalCampanas }} {{ $totalCampanas === 1 ? 'CAMPAÑA' : 'CAMPAÑAS' }}</span>
    </div>

    <div class="flex flex-col gap-2.5 px-5 pt-2.5">
        @forelse ($productos as $producto)
            <div class="card relative grid grid-cols-[76px_1fr] gap-3.5 p-3 transition hover:border-ink">
                <div class="placeholder-photo flex size-[76px] items-end rounded-[14px] p-1.5 font-mono text-[9px] font-medium text-muted-2">FOTO</div>
                <div class="flex min-w-0 flex-col gap-1">
                    <a href="{{ route('productos.show', $producto) }}" class="truncate text-[17px] font-semibold after:absolute after:inset-0">{{ $producto->nombre }}</a>
                    <span class="truncate text-sm text-muted">{{ $producto->precioFormateado() }} · {{ $producto->resumenPublico() }}</span>
                    <div class="mt-1 flex items-center justify-between">
                        <span @class(['font-mono text-xs font-medium', 'text-hype-dark' => $loop->first && $producto->campanas_count, 'text-muted' => ! ($loop->first && $producto->campanas_count)])>
                            {{ $producto->campanas_count }} {{ $producto->campanas_count === 1 ? 'CAMPAÑA' : 'CAMPAÑAS' }}
                        </span>
                        <a href="{{ route('campanas.create', $producto) }}"
                           class="relative z-10 flex items-center gap-1 rounded-[10px] bg-hype-soft px-2.5 py-[5px] text-[13px] font-semibold text-hype-dark hover:bg-[#f9d6c6]">
                            <i data-lucide="sparkles" class="size-3.5"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="mt-4 flex flex-col items-center gap-3 rounded-[20px] border-[1.5px] border-dashed border-line-2 px-6 py-10 text-center">
                <i data-lucide="{{ $busqueda ? 'search-x' : 'package-plus' }}" class="size-8 text-hype"></i>
                @if ($busqueda)
                    <p class="font-display text-xl font-semibold">Sin resultados para “{{ $busqueda }}”</p>
                    <a href="{{ route('productos.index') }}" class="text-sm font-semibold text-hype-dark">Ver todos</a>
                @else
                    <p class="font-display text-xl font-semibold">Aún no tienes productos</p>
                    <p class="text-sm text-muted">Cuéntanos qué vendes y a quién; con eso la IA escribe tus campañas.</p>
                    <a href="{{ route('productos.create') }}" class="btn btn-dark mt-2 w-auto gap-3"><span>Crear mi primer producto</span><i data-lucide="plus" class="size-5"></i></a>
                @endif
            </div>
        @endforelse
    </div>
</x-layouts.app>
