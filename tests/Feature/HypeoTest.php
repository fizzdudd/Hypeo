<?php

namespace Tests\Feature;

use App\Models\Campana;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HypeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['services.groq.key' => 'test-key']);
    }

    private function respuestaGroq(array $piezas): array
    {
        return ['choices' => [['message' => ['content' => json_encode($piezas)]]]];
    }

    public function test_las_rutas_protegidas_redirigen_al_login(): void
    {
        $this->get('/productos')->assertRedirect('/login');
        $this->get('/generar')->assertRedirect('/login');
    }

    public function test_registro_crea_usuario_e_inicia_sesion(): void
    {
        $this->post('/registro', [
            'name' => 'Valentina Rojas',
            'email' => 'vale@example.com',
            'password' => 'secreta123',
            'password_confirmation' => 'secreta123',
        ])->assertRedirect(route('productos.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'vale@example.com']);
    }

    public function test_login_con_credenciales_incorrectas_conserva_el_correo(): void
    {
        User::factory()->create(['email' => 'vale@example.com']);

        $this->from('/login')->post('/login', ['email' => 'vale@example.com', 'password' => 'mala'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email')
            ->assertSessionHasInput('email', 'vale@example.com');

        $this->assertGuest();
    }

    public function test_crear_producto_normaliza_precio_e_intereses(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/productos', [
            'nombre' => 'Vela Lavanda',
            'descripcion' => 'Cera de soya.',
            'precio' => '12.990',
            'edad' => '25–34',
            'intereses' => 'Bienestar, yoga, ',
            'ubicacion' => 'Santiago',
        ])->assertRedirect();

        $producto = $user->productos()->first();
        $this->assertSame(12990, $producto->precio);
        $this->assertSame(['bienestar', 'yoga'], $producto->intereses);
    }

    public function test_un_usuario_no_puede_ver_ni_editar_productos_ajenos(): void
    {
        $ajeno = Producto::factory()->create();
        $campana = Campana::factory()->for($ajeno)->create();
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('productos.show', $ajeno))->assertForbidden();
        $this->actingAs($user)->delete(route('productos.destroy', $ajeno))->assertForbidden();
        $this->actingAs($user)->get(route('campanas.show', $campana))->assertForbidden();
        $this->actingAs($user)->delete(route('campanas.destroy', $campana))->assertForbidden();
        $this->assertDatabaseCount('productos', 1);
    }

    public function test_generar_campana_con_groq_la_guarda_en_el_historial(): void
    {
        Http::fake(['api.groq.com/*' => Http::response($this->respuestaGroq([
            'eslogan' => 'Enciende la calma.',
            'publicacion' => 'Texto de prueba.',
            'hashtags' => ['velas', '#HechoEnChile'],
            'cta' => 'Escríbenos por DM',
            'idea_imagen' => 'Vela encendida',
        ]))]);

        $producto = Producto::factory()->create();

        $this->actingAs($producto->user)
            ->post(route('campanas.store', $producto), ['red' => 'instagram', 'tono' => 'cercano'])
            ->assertRedirect();

        $campana = $producto->campanas()->first();
        $this->assertSame('Enciende la calma.', $campana->eslogan);
        $this->assertSame(['#velas', '#hechoenchile'], $campana->hashtags);

        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer test-key')
            && $request['model'] === config('services.groq.model'));
    }

    public function test_error_429_de_groq_muestra_mensaje_y_conserva_la_seleccion(): void
    {
        Http::fake(['api.groq.com/*' => Http::response(['error' => 'rate'], 429, ['retry-after' => '42'])]);

        $producto = Producto::factory()->create();

        $this->actingAs($producto->user)
            ->from(route('campanas.create', $producto))
            ->post(route('campanas.store', $producto), ['red' => 'tiktok', 'tono' => 'divertido'])
            ->assertRedirect(route('campanas.create', $producto))
            ->assertSessionHas('error_ia.tipo', 'ocupada')
            ->assertSessionHas('error_ia.espera', 42)
            ->assertSessionHasInput('red', 'tiktok');

        $this->assertDatabaseCount('campanas', 0);
    }

    public function test_respuesta_incompleta_de_groq_no_guarda_nada(): void
    {
        Http::fake(['api.groq.com/*' => Http::response($this->respuestaGroq(['eslogan' => 'Solo eslogan']))]);

        $producto = Producto::factory()->create();

        $this->actingAs($producto->user)
            ->post(route('campanas.store', $producto), ['red' => 'instagram', 'tono' => 'cercano'])
            ->assertSessionHas('error_ia.tipo', 'formato');

        $this->assertDatabaseCount('campanas', 0);
    }

    public function test_todas_las_pantallas_cargan(): void
    {
        $campana = Campana::factory()->create();
        $user = $campana->producto->user;

        foreach ([
            route('productos.index'),
            route('productos.create'),
            route('productos.show', $campana->producto),
            route('productos.edit', $campana->producto),
            route('campanas.create', $campana->producto),
            route('campanas.show', $campana),
            route('perfil'),
        ] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }

        auth()->logout();
        $this->get('/login')->assertOk();
        $this->get('/registro')->assertOk();
    }

    public function test_token_csrf_vencido_vuelve_al_formulario_con_aviso(): void
    {
        $this->app['env'] = 'local'; // en testing Laravel omite la verificación CSRF
        $producto = Producto::factory()->create();

        $this->actingAs($producto->user)
            ->from(route('campanas.create', $producto))
            ->withSession(['_token' => 'bueno'])
            ->post(route('campanas.store', $producto), ['_token' => 'viejo', 'red' => 'tiktok', 'tono' => 'divertido'])
            ->assertRedirect(route('campanas.create', $producto))
            ->assertSessionHas('aviso')
            ->assertSessionHasInput('red', 'tiktok');
    }

    public function test_url_de_imagen_usa_prompt_de_la_ia_y_formato_de_la_red(): void
    {
        $campana = Campana::factory()->create(['red' => 'tiktok', 'prompt_imagen' => 'lavender candle on nightstand']);

        $url = $campana->urlImagen();
        $this->assertStringStartsWith('https://image.pollinations.ai/prompt/', $url);
        $this->assertStringContainsString(rawurlencode('lavender candle on nightstand'), $url);
        $this->assertStringContainsString('width=576&height=1024', $url);

        // Sin prompt en inglés, se usa la idea de imagen
        $campana->prompt_imagen = null;
        $campana->idea_imagen = 'Vela en la playa';
        $this->assertStringContainsString(rawurlencode('Vela en la playa'), $campana->urlImagen());

        config(['services.imagenes.activa' => false]);
        $this->assertNull($campana->urlImagen());
    }
}
