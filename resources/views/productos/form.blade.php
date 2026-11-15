{{-- 04 · Nuevo producto / Editar producto (mismo formulario) --}}
@php($editando = $producto->exists)
<x-layouts.app :title="$editando ? 'Editar producto' : 'Nuevo producto'">
    <x-top-bar :back="$editando ? route('productos.show', $producto) : route('productos.index')" icon="x"
               :title="$editando ? 'Editar producto' : 'Nuevo producto'" />

    <form id="form-producto" method="POST" novalidate
          action="{{ $editando ? route('productos.update', $producto) : route('productos.store') }}"
          class="flex flex-col gap-2.5 px-5">
        @csrf
        @if ($editando) @method('PUT') @endif

        <div class="eyebrow text-xs text-hype-dark">01 · El producto</div>

        <div class="flex flex-col gap-1.5">
            <label for="nombre" class="label">Nombre</label>
            <input id="nombre" name="nombre" maxlength="120" required value="{{ old('nombre', $producto->nombre) }}"
                   placeholder="Vela Lavanda & Eucalipto" @class(['field h-12', 'field-error' => $errors->has('nombre')])>
            @error('nombre') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="descripcion" class="label">Descripción</label>
            <div @class(['flex flex-col gap-1.5 rounded-[14px] border-[1.5px] bg-white px-3.5 py-3 focus-within:border-ink', 'border-danger' => $errors->has('descripcion'), 'border-line' => ! $errors->has('descripcion')])>
                <textarea id="descripcion" name="descripcion" rows="3" maxlength="300" required data-counter="#descripcion-count"
                          placeholder="¿Qué es, de qué está hecho, qué lo hace especial?"
                          class="resize-none bg-transparent text-[15px] leading-snug outline-none placeholder:text-muted-2">{{ old('descripcion', $producto->descripcion) }}</textarea>
                <span id="descripcion-count" class="self-end font-mono text-[11px] font-medium text-muted-2"></span>
            </div>
            @error('descripcion') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="precio" class="label">Precio (CLP)</label>
            <div class="relative">
                <span class="absolute top-1/2 left-3.5 -translate-y-1/2 text-base">$</span>
                <input id="precio" name="precio" inputmode="numeric" required data-precio
                       value="{{ old('precio', $producto->precio) }}" placeholder="12.990"
                       @class(['field h-12 pl-7', 'field-error' => $errors->has('precio')])>
            </div>
            @error('precio') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="my-1 h-px bg-line"></div>
        <div class="eyebrow text-xs text-hype-dark">02 · ¿A quién se lo vendes?</div>

        <fieldset class="flex flex-col gap-2">
            <legend class="label mb-2">Edad</legend>
            <div class="grid grid-cols-4 gap-1.5">
                @foreach (\App\Models\Producto::EDADES as $edad)
                    <label class="opt">
                        <input type="radio" name="edad" value="{{ $edad }}" @checked(old('edad', $producto->edad ?? '25–34') === $edad)>
                        <span class="opt-seg">{{ $edad }}</span>
                    </label>
                @endforeach
            </div>
            @error('edad') <span class="error-text">{{ $message }}</span> @enderror
        </fieldset>

        <div class="flex flex-col gap-2" data-tags>
            <label for="interes-input" class="label">Intereses</label>
            @php($intereses = old('intereses', $producto->intereses ?? []))
            <input type="hidden" name="intereses" value="{{ is_array($intereses) ? implode(',', $intereses) : $intereses }}">
            <div class="flex flex-wrap items-center gap-1.5">
                <div data-tags-list class="contents"></div>
                <input id="interes-input" type="text" placeholder="+ Agregar" enterkeyhint="done"
                       class="w-28 rounded-xl border-[1.5px] border-dashed border-line-2 bg-transparent px-3 py-[6px] text-sm text-muted outline-none placeholder:text-muted focus:w-40 focus:border-ink focus:text-ink">
            </div>
            <span class="text-[12px] text-muted-2">Escribe un interés y presiona Enter (máx. 8).</span>
            @error('intereses') <span class="error-text">{{ $message }}</span> @enderror
            @error('intereses.*') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="ubicacion" class="label">Ubicación</label>
            <div class="relative">
                <i data-lucide="map-pin" class="absolute top-1/2 left-3.5 size-[18px] -translate-y-1/2 text-muted"></i>
                <input id="ubicacion" name="ubicacion" maxlength="120" required value="{{ old('ubicacion', $producto->ubicacion) }}"
                       placeholder="Santiago, Chile" @class(['field h-12 pl-10', 'field-error' => $errors->has('ubicacion')])>
            </div>
            @error('ubicacion') <span class="error-text">{{ $message }}</span> @enderror
        </div>
    </form>

    <x-slot:footer>
        <button type="submit" form="form-producto" class="btn btn-dark">
            <span>{{ $editando ? 'Guardar cambios' : 'Guardar producto' }}</span><i data-lucide="check" class="size-5"></i>
        </button>
    </x-slot:footer>
</x-layouts.app>
