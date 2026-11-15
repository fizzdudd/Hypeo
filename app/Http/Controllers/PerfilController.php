<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('perfil', [
            'user' => $user,
            'totalProductos' => $user->productos()->count(),
            'totalCampanas' => $user->campanas()->count(),
        ]);
    }
}
