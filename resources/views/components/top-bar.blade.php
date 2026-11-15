@props(['back' => null, 'title' => null, 'icon' => 'chevron-left'])
<div class="flex items-center justify-between px-5 pt-4 pb-2">
    @if ($back)
        <a href="{{ $back }}" class="btn-icon" aria-label="Volver"><i data-lucide="{{ $icon }}" class="size-[22px]"></i></a>
    @else
        <div class="w-11"></div>
    @endif
    @if ($title)
        <span class="text-[17px] font-semibold">{{ $title }}</span>
    @endif
    {{ $center ?? '' }}
    <div class="flex min-w-11 justify-end gap-2">{{ $slot }}</div>
</div>
