<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pessoa extends Model
{
    protected $table = 'pessoas';

    protected $fillable = [
        'nome_exibicao',
        'nome_busca',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
            'notas_restritas' => 'encrypted',
        ];
    }

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class);
    }
}
