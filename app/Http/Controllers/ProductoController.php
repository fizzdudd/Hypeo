<?php

namespace App\Http\Controllers;

use App\Models\Campana;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductoController extends Controller
{
    /** 03 · Mis productos */
    public function index(Request $request): View
    {
        $user = $request->user();
        $busqueda = trim((string) $request->query('q'));

        $productos = $user->productos()
            ->withCount('campanas')
            ->when($busqueda, fn ($q) => $q->where('nombre', 'like', "%{$busqueda}%"))
            ->latest()
            ->get();

        return view('productos.index', [
            'productos' => $productos,
            'busqueda' => $busqueda,
            'totalProductos' => $user->productos()->count(),
            'totalCampanas' => $user->campanas()->count(),
        ]);
    }

    /** 04 · Nuevo producto */
    public function create(): View
    {
        return view('productos.form', ['producto' => new Producto(['intereses' => []])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $producto = $request->user()->productos()->create($this->validar($request));

        return redirect()->route('productos.show', $producto)->with('ok', 'Producto guardado.');
    }

    /** 05 · Detalle del producto + historial de campañas */
    public function show(Request $request, Producto $producto): View
    {
        Gate::authorize('view', $producto);

        $red = $request->query('red');
        $red = array_key_exists((string) $red, Campana::REDES) ? $red : null;

        return view('productos.show', [
            'producto' => $producto,
            'campanas' => $producto->campanas()->when($red, fn ($q) => $q->where('red', $red))->get(),
            'totalCampanas' => $producto->campanas()->count(),
            'redActiva' => $red,
        ]);
    }

    public function edit(Producto $producto): View
    {
        Gate::authorize('update', $producto);

        return view('productos.form', ['producto' => $producto]);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        Gate::authorize('update', $producto);

        $producto->update($this->validar($request));

        return redirect()->route('productos.show', $producto)->with('ok', 'Cambios guardados.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        Gate::authorize('delete', $producto);

        $producto->delete();

        return redirect()->route('productos.index')->with('ok', 'Producto eliminado.');
    }

    private function validar(Request $request): array
    {
        // El precio llega como "12.990" o "$ 12.990": dejamos solo dígitos.
        $request->merge([
            'precio' => preg_replace('/\D/', '', (string) $request->input('precio')),
            'intereses' => collect(explode(',', (string) $request->input('intereses')))
                ->map(fn ($i) => mb_strtolower(trim($i)))
                ->filter()->unique()->values()->all(),
        ]);

        return $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['required', 'string', 'max:300'],
            'precio' => ['required', 'integer', 'min:0', 'max:100000000'],
            'edad' => ['required', Rule::in(Producto::EDADES)],
            'intereses' => ['required', 'array', 'min:1', 'max:8'],
            'intereses.*' => ['string', 'max:30'],
            'ubicacion' => ['required', 'string', 'max:120'],
        ], [
            'intereses.required' => 'Agrega al menos un interés.',
            'intereses.min' => 'Agrega al menos un interés.',
        ]);
    }
}
