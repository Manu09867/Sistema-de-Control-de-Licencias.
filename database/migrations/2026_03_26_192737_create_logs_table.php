<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 100)->nullable();
            $table->string('accion', 50);
            $table->string('tabla', 100)->nullable();
            $table->text('query');
            $table->string('resultado', 50)->nullable();
            $table->integer('filas_afectadas')->default(0);
            $table->float('duracion', 10, 6)->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('accion');
            $table->index('tabla');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};