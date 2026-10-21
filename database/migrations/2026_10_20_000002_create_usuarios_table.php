<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pessoa_id')->unique()->constrained('pessoas')->restrictOnDelete();
            $table->string('email', 254)->unique();
            $table->string('password');
            $table->boolean('ativo')->default(true);
            $table->rememberToken();
            $table->timestampsTz();
        });

        // Evita variantes de caixa também em gravações SQL fora do Eloquent.
        DB::statement('CREATE UNIQUE INDEX usuarios_email_lower_unique ON usuarios (lower(email))');

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email', 254)->primary();
            $table->string('token');
            $table->timestampTz('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('usuarios');
    }
};
