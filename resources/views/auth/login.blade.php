{{-- 01 · Iniciar sesión --}}
<x-layouts.app title="Iniciar sesión" dark :pad="false">
    <div class="flex flex-1 flex-col gap-5 px-7 pt-10">
        <div class="font-display text-[30px] font-bold tracking-tight">hypeo<span class="text-hype">.</span></div>

        <div class="mt-2.5 flex flex-col gap-2.5" aria-hidden="true">
            <div class="-rotate-3 self-start rounded-[14px] bg-hype px-3.5 py-2.5 font-display text-[17px] font-semibold text-ink">“Enciende la calma.”</div>
            <div class="rotate-2 self-end rounded-[14px] bg-paper px-3.5 py-2.5 font-mono text-sm font-medium text-ink">#velasartesanales #hechoenchile</div>
            <div class="ml-7 -rotate-1 self-start rounded-[14px] border-[1.5px] border-[#4A443D] px-3.5 py-2.5 text-[15px] font-medium text-line-2">Compra por DM →</div>
        </div>

        <h1 class="mt-2 font-display text-[38px] leading-[1.02] font-bold tracking-tight">Tu campaña,<br>lista en segundos.</h1>
    </div>

    <form method="POST" action="{{ route('login') }}" novalidate
          class="mt-8 flex flex-col gap-3.5 rounded-t-[32px] bg-paper px-6 pt-7 pb-11 text-ink">
        @csrf
        <div class="flex flex-col gap-1.5">
            <label for="email" class="label">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus
                   placeholder="tu@correo.cl" @class(['field h-[52px]', 'field-error' => $errors->has('email')])>
            @error('email') <span class="error-text">{{ $message }}</span> @enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label for="password" class="label">Contraseña</label>
            <div class="relative">
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       placeholder="••••••••" @class(['field h-[52px] pr-12', 'field-error' => $errors->has('password')])>
                <button type="button" data-toggle-pass="#password" aria-label="Mostrar contraseña"
                        class="absolute inset-y-0 right-3 flex items-center text-muted hover:text-ink">
                    <i data-lucide="eye" class="size-5"></i>
                </button>
            </div>
            @error('password') <span class="error-text">{{ $message }}</span> @enderror
        </div>
        <label class="flex items-center gap-2 text-sm text-muted">
            <input type="checkbox" name="remember" class="size-4 accent-ink"> Mantener sesión iniciada
        </label>
        <button type="submit" class="btn btn-hype mt-1.5"><span>Entrar</span><i data-lucide="arrow-right" class="size-5"></i></button>
        <p class="text-center text-[15px] text-muted">¿Aún no tienes cuenta? <a href="{{ route('register') }}" class="font-bold text-ink underline-offset-2 hover:underline">Crear cuenta</a></p>
    </form>
</x-layouts.app>
