<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre', 120);
            $table->string('descripcion', 300);
            $table->unsignedInteger('precio');           // CLP, sin decimales
            $table->string('edad', 10);                  // 18–24, 25–34, 35–44, 45+
            $table->json('intereses');                   // ["bienestar", "yoga"]
            $table->string('ubicacion', 120);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
