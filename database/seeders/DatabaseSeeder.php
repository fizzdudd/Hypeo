<?php

namespace Database\Seeders;

use App\Models\Campana;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Datos de demostración basados en la persona de la propuesta (Valentina, velas artesanales).
 * Usuario: valentina@luzdecerro.cl · Contraseña: password
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Valentina Rojas',
            'email' => 'valentina@luzdecerro.cl',
            'password' => Hash::make('password'),
        ]);

        $lavanda = $user->productos()->create([
            'nombre' => 'Vela Lavanda & Eucalipto',
            'descripcion' => 'Vela de cera de soya, 40 h de duración, mecha de algodón. Aroma relajante para el final del día.',
            'precio' => 12990,
            'edad' => '25–34',
            'intereses' => ['bienestar', 'yoga', 'decoración'],
            'ubicacion' => 'Santiago, Chile',
        ]);

        $cedro = $user->productos()->create([
            'nombre' => 'Vela Cedro Andino',
            'descripcion' => 'Vela en vaso de vidrio reciclado con notas de cedro y madera. 50 h de duración, hecha a mano en Santiago.',
            'precio' => 14990,
            'edad' => '35–44',
            'intereses' => ['hogar', 'diseño'],
            'ubicacion' => 'Santiago, Chile',
        ]);

        $set = $user->productos()->create([
            'nombre' => 'Set Mini Velas Regalo',
            'descripcion' => 'Caja con 4 mini velas de soya (lavanda, cedro, vainilla y cítricos). Ideal para regalar.',
            'precio' => 19990,
            'edad' => '18–24',
            'intereses' => ['regalos', 'autocuidado'],
            'ubicacion' => 'Chile',
        ]);

        $this->campana($lavanda, 'instagram', 'cercano', now(), [
            'eslogan' => 'Enciende la calma después del día.',
            'publicacion' => 'Llegas a casa, apagas el celular y prendes tu vela de lavanda y eucalipto. 40 horas de cera de soya hecha a mano en Santiago para esos minutos que son solo tuyos. ¿Cuál es tu ritual para desconectarte?',
            'hashtags' => ['#velasdesoya', '#hechoenchile', '#autocuidado', '#santiago', '#ritualnocturno', '#velasartesanales'],
            'cta' => 'Escríbenos por DM y reserva la tuya',
            'idea_imagen' => 'Vela encendida en velador, luz cálida, libro abierto',
        ]);

        $this->campana($lavanda, 'tiktok', 'divertido', now()->subDays(2), [
            'eslogan' => 'POV: tu pieza huele a spa.',
            'publicacion' => 'POV: llegaste destruida del trabajo y tu pieza huele a spa 🧘‍♀️ Lavanda + eucalipto, 40 horas de pura calma.',
            'hashtags' => ['#velas', '#pov', '#selfcare', '#hechoenchile', '#emprendimiento'],
            'cta' => 'Link en la bio',
            'idea_imagen' => 'Video vertical: abrir la puerta, encender la vela y tirarse a la cama',
        ]);

        $this->campana($lavanda, 'linkedin', 'profesional', now()->subDays(6), [
            'eslogan' => 'Regalos corporativos con aroma local.',
            'publicacion' => 'En Luz de Cerro fabricamos velas de soya a mano en Santiago. Si buscas un regalo corporativo sustentable para tu equipo o clientes, armamos packs personalizados con tu marca y despacho a todo Chile.',
            'hashtags' => ['#regaloscorporativos', '#pyme', '#hechoenchile', '#sustentable', '#bienestarlaboral'],
            'cta' => 'Cotiza tu pack corporativo por mensaje',
            'idea_imagen' => 'Cajas de regalo apiladas sobre escritorio de oficina, luz natural',
        ]);

        $this->campana($cedro, 'facebook', 'elegante', now()->subDays(3), [
            'eslogan' => 'El aroma del bosque, en tu living.',
            'publicacion' => 'Cedro y madera en un vaso de vidrio reciclado: una vela que decora apagada y transforma el ambiente encendida. 50 horas de duración, hecha a mano en Santiago.',
            'hashtags' => ['#decoracion', '#hogar', '#velasdesoya', '#diseñolocal', '#santiago'],
            'cta' => 'Pídela hoy con despacho en la RM',
            'idea_imagen' => 'Vela sobre mesa de centro de madera, plantas y manta tejida',
        ]);

        $this->campana($set, 'instagram', 'divertido', now()->subDays(10), [
            'eslogan' => 'Cuatro aromas, cero excusas para no regalar.',
            'publicacion' => '¿Cumpleaños, amigo secreto o “porque sí”? 🎁 Nuestro set trae 4 mini velas para que elijan su aroma favorito.',
            'hashtags' => ['#regalos', '#amigosecreto', '#velas', '#hechoenchile', '#emprendechile'],
            'cta' => 'Compra por DM',
            'idea_imagen' => 'Caja abierta con las 4 mini velas y papel kraft, vista cenital',
        ]);
    }

    private function campana($producto, string $red, string $tono, $fecha, array $datos): void
    {
        $campana = $producto->campanas()->make(['red' => $red, 'tono' => $tono]);
        $campana->aplicarGeneracion($datos);
        $campana->created_at = $fecha;
        $campana->updated_at = $fecha;
        $campana->save();
    }
}
