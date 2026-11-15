<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained()->cascadeOnDelete();
            $table->string('red', 20);                   // instagram | tiktok | facebook | linkedin
            $table->string('tono', 20);                  // cercano | profesional | divertido | elegante
            $table->string('eslogan', 200);
            $table->text('publicacion');
            $table->json('hashtags');
            $table->string('cta', 200);
            $table->string('idea_imagen', 300);
            $table->json('texto_ia')->nullable();        // respuesta de la IA sin editar
            $table->boolean('editada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campanas');
    }
};
