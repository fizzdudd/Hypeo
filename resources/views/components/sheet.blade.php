@props(['id', 'icon' => 'trash-2', 'title', 'message' => null])
{{-- Hoja inferior de confirmación (eliminar, cerrar sesión) --}}
<dialog id="{{ $id }}" class="sheet" aria-labelledby="{{ $id }}-title">
    <div class="mx-auto flex w-full max-w-[480px] flex-col gap-4 rounded-t-[32px] bg-paper px-6 pt-3 pb-10 text-ink">
        <div class="h-[5px] w-10 self-center rounded-full bg-line-2"></div>
        <div class="mt-1.5 flex size-12 items-center justify-center rounded-full bg-hype-soft">
            <i data-lucide="{{ $icon }}" class="size-[22px] text-hype-dark"></i>
        </div>
        <div class="flex flex-col gap-1.5">
            <h2 id="{{ $id }}-title" class="font-display text-2xl font-bold tracking-tight">{{ $title }}</h2>
            @if ($message)
                <p class="text-[15px] leading-snug text-muted">{{ $message }}</p>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-2">
            <button type="button" data-close-dialog
                    class="flex h-[52px] items-center rounded-2xl border-[1.5px] border-ink px-[18px] font-semibold">Cancelar</button>
            {{ $slot }}
        </div>
    </div>
</dialog>
