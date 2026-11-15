<?php

namespace App\Http\Controllers;

use App\Models\Campana;
use App\Models\Producto;
use App\Services\GroqException;
use App\Services\GroqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CampanaController extends Controller
{
    public function __construct(private GroqService $groq) {}

    /** 11 · Todas las campañas, agrupadas por fecha y filtrables por red */
    public function index(Request $request): View
    {
        $red = $request->query('red');
        $red = array_key_exists((string) $red, Campana::REDES) ? $red : null;

        $base = $request->user()->campanas();

        $campanas = (clone $base)->with('producto')
            ->when($red, fn ($q) => $q->where('red', $red))
            ->latest('campanas.created_at')
            ->get()
            ->groupBy(fn (Campana $c) => $c->created_at->gte(now()->startOfWeek()) ? 'ESTA SEMANA' : 'ANTES');

        return view('campanas.index', [
            'grupos' => $campanas,
            'redActiva' => $red,
            'total' => $base->count(),
        ]);
    }

    /** Botón central de la barra: elegir para qué producto generar */
    public function elegirProducto(Request $request): View|RedirectResponse
    {
        $productos = $request->user()->productos()->latest()->get();

        if ($productos->count() === 1 && ! $request->boolean('todos')) {
            return redirect()->route('campanas.create', $productos->first());
        }

        return view('campanas.elegir', ['productos' => $productos]);
    }

    /** 06 · Red y tono */
    public function create(Producto $producto): View
    {
        Gate::authorize('update', $producto);

        return view('campanas.create', ['producto' => $producto]);
    }

    /** 07 → 08 · Generar con IA y guardar en el historial */
    public function store(Request $request, Producto $producto): RedirectResponse
    {
        Gate::authorize('update', $producto);

        $datos = $request->validate([
            'red' => ['required', Rule::in(array_keys(Campana::REDES))],
            'tono' => ['required', Rule::in(array_keys(Campana::TONOS))],
        ]);

        try {
            $generado = $this->groq->generarCampana($producto, $datos['red'], $datos['tono']);
        } catch (GroqException $e) {
            // 13 · Error IA: volvemos conservando la selección del usuario
            return back()->withInput()->with('error_ia', [
                ...$e->mensajeUsuario(),
                'tipo' => $e->tipo,
                'espera' => $e->tipo === GroqException::OCUPADA ? $e->reintentarEn : 0,
            ]);
        }

        $campana = $producto->campanas()->make($datos);
        $campana->aplicarGeneracion($generado);
        $campana->save();

        return redirect()->route('campanas.show', $campana);
    }

    /** 08 / 09 · Campaña generada (contenido + vista previa) */
    public function show(Campana $campana): View
    {
        Gate::authorize('view', $campana);

        return view('campanas.show', ['campana' => $campana->load('producto.user')]);
    }

    /** 10 · Editar texto */
    public function edit(Request $request, Campana $campana): View
    {
        Gate::authorize('update', $campana);

        $pieza = $request->query('pieza');

        return view('campanas.edit', [
            'campana' => $campana,
            'piezaActiva' => array_key_exists((string) $pieza, Campana::PIEZAS) ? $pieza : 'publicacion',
        ]);
    }

    public function update(Request $request, Campana $campana): RedirectResponse
    {
        Gate::authorize('update', $campana);

        $limite = Campana::REDES[$campana->red]['limite'];

        $datos = $request->validate([
            'eslogan' => ['required', 'string', 'max:200'],
            'publicacion' => ['required', 'string', "max:{$limite}"],
            'hashtags' => ['nullable', 'string', 'max:500'],
            'cta' => ['required', 'string', 'max:200'],
            'idea_imagen' => ['required', 'string', 'max:300'],
        ]);

        $datos['hashtags'] = collect(preg_split('/[\s,]+/', (string) $datos['hashtags']))
            ->filter()
            ->map(fn ($h) => '#'.ltrim($h, '#'))
            ->unique()->values()->all();

        $campana->fill($datos);
        $campana->editada = $campana->texto_ia !== null && collect(Campana::PIEZAS)
            ->keys()
            ->contains(fn ($k) => $campana->{$k} != ($campana->texto_ia[$k] ?? null));
        $campana->save();

        return redirect()->route('campanas.show', $campana)->with('ok', 'Texto actualizado.');
    }

    /** Regenerar: misma red y tono, nueva respuesta de la IA */
    public function regenerate(Campana $campana): RedirectResponse
    {
        Gate::authorize('update', $campana);

        try {
            $generado = $this->groq->generarCampana($campana->producto, $campana->red, $campana->tono);
        } catch (GroqException $e) {
            return back()->with('error_ia', [
                ...$e->mensajeUsuario(),
                'tipo' => $e->tipo,
                'espera' => $e->tipo === GroqException::OCUPADA ? $e->reintentarEn : 0,
            ]);
        }

        $campana->aplicarGeneracion($generado);
        $campana->save();

        return redirect()->route('campanas.show', $campana)->with('ok', 'Campaña regenerada.');
    }

    /** Volver al texto original que entregó la IA */
    public function restore(Campana $campana): RedirectResponse
    {
        Gate::authorize('update', $campana);

        if ($campana->texto_ia) {
            $campana->aplicarGeneracion($campana->texto_ia);
            $campana->save();
        }

        return redirect()->route('campanas.show', $campana)->with('ok', 'Se restauró el texto original.');
    }

    public function destroy(Request $request, Campana $campana): RedirectResponse
    {
        Gate::authorize('delete', $campana);

        $producto = $campana->producto;
        $campana->delete();

        return $request->input('volver') === 'producto'
            ? redirect()->route('productos.show', $producto)->with('ok', 'Campaña eliminada.')
            : redirect()->route('campanas.index')->with('ok', 'Campaña eliminada.');
    }
}
