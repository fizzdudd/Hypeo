{{-- 12 · Perfil y cerrar sesión --}}
<x-layouts.app title="Perfil" nav="perfil">
    <x-top-bar :back="route('productos.index')" />

    <div class="flex flex-col gap-5 px-5 pt-2">
        <div class="flex items-center gap-3.5">
            <div class="flex size-16 items-center justify-center rounded-full bg-ink text-[22px] font-semibold text-paper">{{ $user->iniciales() }}</div>
            <div class="flex min-w-0 flex-col gap-0.5">
                <h1 class="truncate font-display text-2xl font-bold tracking-tight">{{ $user->name }}</h1>
                <span class="truncate text-[15px] text-muted">{{ $user->email }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 border-t-2 border-b border-t-ink border-b-line">
            <a href="{{ route('productos.index') }}" class="flex flex-col gap-0.5 border-r border-line py-3.5">
                <span class="font-display text-3xl font-bold">{{ $totalProductos }}</span>
                <span class="font-mono text-[11px] font-medium text-muted">PRODUCTOS</span>
            </a>
            <div class="flex flex-col gap-0.5 px-4 py-3.5">
                <span class="font-display text-3xl font-bold">{{ $totalCampanas }}</span>
                <span class="font-mono text-[11px] font-medium text-muted">CAMPAÑAS</span>
            </div>
        </div>

        <div class="flex flex-col text-base font-medium">
            <div class="flex items-center justify-between border-b border-line py-3.5">
                <span class="flex items-center gap-3"><i data-lucide="user" class="size-5"></i>Cuenta</span>
                <span class="text-[13px] font-normal text-muted">Desde {{ $user->created_at->locale('es')->translatedFormat('F Y') }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-line py-3.5">
                <span class="flex items-center gap-3"><i data-lucide="shield-check" class="size-5"></i>Privacidad</span>
                <span class="text-[13px] font-normal text-muted">Solo tú ves tu contenido</span>
            </div>
            <div class="flex items-center justify-between border-b border-line py-3.5">
                <span class="flex items-center gap-3"><i data-lucide="sparkles" class="size-5"></i>Modelo de IA</span>
                <span class="font-mono text-[12px] font-normal text-muted">{{ config('services.groq.model') }}</span>
            </div>
            <button type="button" data-open-dialog="cerrar-sesion"
                    class="flex items-center gap-3 py-3.5 text-left text-hype-dark"><i data-lucide="log-out" class="size-5"></i>Cerrar sesión</button>
        </div>
    </div>

    <x-slot:after>
        <x-sheet id="cerrar-sesion" icon="log-out" title="¿Cerrar sesión?"
                 message="Tus productos y campañas quedan guardados. Podrás verlos al volver a entrar.">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex h-[52px] w-full items-center rounded-2xl bg-ink px-[18px] font-semibold text-paper">Cerrar sesión</button>
            </form>
        </x-sheet>
    </x-slot:after>
</x-layouts.app>
