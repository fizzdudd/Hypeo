{{-- 11 · Todas las campañas --}}
<x-layouts.app title="Campañas" nav="campanas">
    <div class="flex flex-col gap-3.5 px-5 pt-6">
        <h1 class="font-display text-[34px] font-bold tracking-tight">Campañas</h1>
        <nav class="-mx-5 flex gap-1.5 overflow-x-auto px-5 text-[13px] font-medium [scrollbar-width:none]" aria-label="Filtrar por red">
            <a href="{{ route('campanas.index') }}" @class(['rounded-xl px-3 py-[7px] whitespace-nowrap', 'bg-ink text-paper' => ! $redActiva, 'border-[1.5px] border-line hover:border-ink' => $redActiva])>Todas · {{ $total }}</a>
            @foreach (\App\Models\Campana::REDES as $clave => $red)
                <a href="{{ route('campanas.index', ['red' => $clave]) }}"
                   @class(['rounded-xl px-3 py-[7px] whitespace-nowrap', 'bg-ink text-paper' => $redActiva === $clave, 'border-[1.5px] border-line hover:border-ink' => $redActiva !== $clave])>{{ $red['nombre'] }}</a>
            @endforeach
        </nav>
    </div>

    <div class="flex flex-col gap-2 px-5 pt-[18px]">
        @forelse ($grupos as $grupo => $campanas)
            <span @class(['font-mono text-[11px] font-medium tracking-[.08em] text-muted', 'mt-2' => ! $loop->first])>{{ $grupo }}</span>
            @foreach ($campanas as $campana)
                <div class="group relative">
                    <x-campana-card :campana="$campana" mostrar-producto abreviado />
                    <button type="button" data-open-dialog="eliminar-{{ $campana->id }}" aria-label="Eliminar campaña"
                            class="absolute right-3 bottom-3 flex size-8 items-center justify-center rounded-lg text-muted-2 opacity-100 hover:bg-hype-soft hover:text-danger sm:opacity-0 sm:group-hover:opacity-100 sm:focus:opacity-100">
                        <i data-lucide="trash-2" class="size-4"></i>
                    </button>
                </div>
            @endforeach
        @empty
            <div class="mt-4 flex flex-col items-center gap-3 rounded-[20px] border-[1.5px] border-dashed border-line-2 px-6 py-10 text-center">
                <i data-lucide="layers" class="size-8 text-hype"></i>
                <p class="font-display text-xl font-semibold">{{ $redActiva ? 'Sin campañas para esta red' : 'Aún no generas campañas' }}</p>
                <a href="{{ route('campanas.elegir') }}" class="text-sm font-semibold text-hype-dark">Generar una campaña →</a>
            </div>
        @endforelse
    </div>

    <x-slot:after>
        @foreach ($grupos->flatten() as $campana)
            <x-sheet id="eliminar-{{ $campana->id }}" title="¿Eliminar campaña?"
                     :message="'“'.$campana->eslogan.'” se quitará del historial. Esta acción no se puede deshacer.'">
                <form method="POST" action="{{ route('campanas.destroy', $campana) }}">
                    @csrf @method('DELETE')
                    <button class="flex h-[52px] w-full items-center rounded-2xl bg-danger px-[18px] font-semibold text-white">Eliminar</button>
                </form>
            </x-sheet>
        @endforeach
    </x-slot:after>
</x-layouts.app>
