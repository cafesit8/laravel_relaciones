<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('url', 255);
            $table->unsignedBigInteger('user_id') // Una foto pertenece a un usuario
                  ->unique(); // Un usuario solo puede tener una foto

            $table->foreign('user_id')
                  ->references('id') // En la columna 'user_id'
                  ->on('users') // En la tabla 'users'
                  ->onDelete('cascade'); // Si se borra el usuario se borran sus fotos
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_profiles');
    }
};
