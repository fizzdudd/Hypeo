@props(['texto', 'limite' => 120, 'etiqueta' => 'más', 'hashtags' => []])
{{-- Texto con "ver más" / "ver menos" como en las redes --}}
@php($largo = mb_strlen($texto) > $limite)
<span data-desplegable data-abierto="false" class="group/mas">
    @if ($largo)
        <span class="group-data-[abierto=true]/mas:hidden">{{ rtrim(\Illuminate\Support\Str::limit($texto, $limite, '')) }}…</span>
        <span class="hidden whitespace-pre-line group-data-[abierto=true]/mas:inline">{{ $texto }}</span>
        <button type="button" data-toggle-mas class="cursor-pointer text-muted-2 hover:text-ink">
            <span class="group-data-[abierto=true]/mas:hidden">{{ $etiqueta }}</span>
            <span class="hidden group-data-[abierto=true]/mas:inline">ver menos</span>
        </button>
    @else
        <span class="whitespace-pre-line">{{ $texto }}</span>
    @endif
    @if ($hashtags && ! $largo)
        <span class="mt-1.5 block text-[13px] text-hype-dark">{{ implode(' ', $hashtags) }}</span>
    @elseif ($hashtags)
        <span class="mt-1.5 block text-[13px] text-hype-dark">
            {{ implode(' ', array_slice($hashtags, 0, 3)) }}@if (count($hashtags) > 3)<span class="hidden group-data-[abierto=true]/mas:inline"> {{ implode(' ', array_slice($hashtags, 3)) }}</span>@endif
        </span>
    @endif
</span>
