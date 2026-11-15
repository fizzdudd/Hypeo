{{-- 02 · Crear cuenta --}}
<x-layouts.app title="Crear cuenta" :pad="false">
    <x-top-bar :back="route('login')" />

    <form method="POST" action="{{ route('register') }}" novalidate class="flex flex-1 flex-col">
        @csrf
        <div class="flex flex-col gap-2 px-6 pt-3">
            <h1 class="font-display text-4xl leading-[1.02] font-bold tracking-tight">Crea tu cuenta</h1>
            <p class="text-base leading-snug text-muted">Tus productos y campañas quedan guardados solo para ti.</p>
        </div>

        <div class="flex flex-col gap-4 px-6 pt-7">
            <div class="flex flex-col gap-1.5">
                <label for="name" class="label">Nombre</label>
                <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus
                       placeholder="Valentina Rojas" @class(['field h-[52px]', 'field-error' => $errors->has('name')])>
                @error('name') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="email" class="label">Correo</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required
                       placeholder="tu@correo.cl" @class(['field h-[52px]', 'field-error' => $errors->has('email')])>
                @error('email') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="password" class="label">Contraseña</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required minlength="8"
                       data-strength @class(['field h-[52px]', 'field-error' => $errors->has('password')])>
                <div class="mt-1 grid grid-cols-4 gap-1" aria-hidden="true">
                    @for ($i = 0; $i < 4; $i++) <div data-bar class="h-1 rounded-full bg-line"></div> @endfor
                </div>
                <span data-strength-text class="text-[13px] text-muted">Mínimo 8 caracteres</span>
                @error('password') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="password_confirmation" class="label">Confirmar contraseña</label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           data-confirm="#password" class="field h-[52px] pr-12">
                    <i data-lucide="check" data-confirm-ok class="invisible absolute top-1/2 right-4 size-5 -translate-y-1/2 text-ok"></i>
                </div>
            </div>
        </div>

        <div class="mt-auto flex flex-col gap-3.5 px-6 pt-8 pb-11">
            <button type="submit" class="btn btn-dark"><span>Crear cuenta</span><i data-lucide="arrow-right" class="size-5"></i></button>
            <p class="text-center text-[15px] text-muted">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="font-bold text-ink hover:underline">Inicia sesión</a></p>
        </div>
    </form>
</x-layouts.app>
