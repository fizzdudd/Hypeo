{{-- 05 · Detalle del producto + historial --}}
<x-layouts.app :title="$producto->nombre" :pad="false">
    <div class="placeholder-photo relative h-[230px] flex-none">
        <div class="flex justify-between px-5 pt-4">
            <a href="{{ route('productos.index') }}" aria-label="Volver" class="btn-icon border-0"><i data-lucide="chevron-left" class="size-[22px]"></i></a>
            <div class="flex gap-2">
                <a href="{{ route('productos.edit', $producto) }}" aria-label="Editar producto" class="btn-icon border-0"><i data-lucide="pencil" class="size-[18px]"></i></a>
                <button type="button" data-open-dialog="eliminar-producto" aria-label="Eliminar producto" class="btn-icon border-0"><i data-lucide="trash-2" class="size-[18px]"></i></button>
            </div>
        </div>
        <span class="absolute bottom-10 left-5 font-mono text-[11px] font-medium text-muted-2">FOTO DEL PRODUCTO</span>
    </div>

    <div class="relative -mt-6 flex flex-col gap-3 rounded-t-[28px] bg-paper px-5 pt-[22px] pb-10">
        <div class="flex items-start justify-between gap-3">
            <h1 class="font-display text-[28px] leading-[1.05] font-bold tracking-tight">{{ $producto->nombre }}</h1>
            <span class="text-lg font-semibold whitespace-nowrap">{{ $producto->precioFormateado() }}</span>
        </div>
        <p class="text-[15px] leading-snug text-ink-2">{{ $producto->descripcion }}</p>
        <div class="flex flex-wrap gap-1.5">
            <span class="chip bg-sand">{{ $producto->edad }} años</span>
            <span class="chip bg-sand">{{ implode(' · ', $producto->intereses) }}</span>
            <span class="chip bg-sand">{{ $producto->ubicacion }}</span>
        </div>

        <a href="{{ route('campanas.create', $producto) }}" class="btn btn-hype h-[54px]">
            <span class="flex items-center gap-2"><i data-lucide="sparkles" class="size-5"></i>Generar campaña</span>
            <i data-lucide="arrow-right" class="size-5"></i>
        </a>

        <div class="mt-1.5 flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold">Historial · {{ $totalCampanas }}</h2>
            <nav class="flex gap-1 font-mono text-xs font-medium" aria-label="Filtrar por red">
                <a href="{{ route('productos.show', $producto) }}" @class(['rounded-lg px-2 py-[5px]', 'bg-ink text-paper' => ! $redActiva, 'border border-line hover:border-ink' => $redActiva])>TODAS</a>
                @foreach (\App\Models\Campana::REDES as $clave => $red)
                    <a href="{{ route('productos.show', [$producto, 'red' => $clave]) }}" title="{{ $red['nombre'] }}"
                       @class(['rounded-lg px-2 py-[5px]', 'bg-ink text-paper' => $redActiva === $clave, 'border border-line hover:border-ink' => $redActiva !== $clave])>{{ $red['abrev'] }}</a>
                @endforeach
            </nav>
        </div>

        <div class="flex flex-col gap-2">
            @forelse ($campanas as $campana)
                <x-campana-card :campana="$campana" />
            @empty
                <p class="rounded-2xl border-[1.5px] border-dashed border-line-2 px-4 py-6 text-center text-sm text-muted">
                    {{ $redActiva ? 'No hay campañas para esta red.' : 'Todavía no hay campañas. ¡Genera la primera!' }}
                </p>
            @endforelse
        </div>
    </div>

    <x-slot:after>
        <x-sheet id="eliminar-producto" title="¿Eliminar producto?"
                 :message="$totalCampanas ? 'Se eliminarán también sus '.$totalCampanas.' campañas. Esta acción no se puede deshacer.' : 'Esta acción no se puede deshacer.'">
            <form method="POST" action="{{ route('productos.destroy', $producto) }}">
                @csrf @method('DELETE')
                <button class="flex h-[52px] w-full items-center rounded-2xl bg-danger px-[18px] font-semibold text-white">Eliminar</button>
            </form>
        </x-sheet>
    </x-slot:after>
</x-layouts.app>
