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

        $campana = $producto->campanas()->create([...$datos, ...$generado]);

        return redirect()->route('campanas.show', $campana);
    }

    /** 08 / 09 · Campaña generada (contenido + vista previa) */
    public function show(Campana $campana): View
    {
        Gate::authorize('view', $campana);

        return view('campanas.show', ['campana' => $campana->load('producto.user')]);
    }

    public function destroy(Campana $campana): RedirectResponse
    {
        Gate::authorize('delete', $campana);

        $producto = $campana->producto;
        $campana->delete();

        return redirect()->route('productos.show', $producto)->with('ok', 'Campaña eliminada.');
    }
}
