<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pessoas', function (Blueprint $table): void {
            $table->id();
            $table->text('nome_exibicao');
            $table->string('nome_busca')->nullable()->index();
            $table->boolean('ativo')->default(true);
            $table->text('notas_restritas')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
