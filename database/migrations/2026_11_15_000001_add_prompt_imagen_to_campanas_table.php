<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campanas', function (Blueprint $table) {
            // Prompt en inglés que Groq escribe para el generador de imágenes
            $table->string('prompt_imagen', 500)->nullable()->after('idea_imagen');
        });
    }

    public function down(): void
    {
        Schema::table('campanas', function (Blueprint $table) {
            $table->dropColumn('prompt_imagen');
        });
    }
};
