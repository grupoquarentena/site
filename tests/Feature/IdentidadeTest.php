<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IdentidadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_pessoa_pode_existir_sem_usuario(): void
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Pessoa Exemplo']);
        $this->assertNull($pessoa->usuario);
        $this->assertDatabaseHas('pessoas', ['id' => $pessoa->id]);
    }

    public function test_usuario_exige_pessoa_existente(): void
    {
        $this->expectException(QueryException::class);
        DB::table('usuarios')->insert([
            'pessoa_id' => 999999, 'email' => 'teste@example.invalid', 'password' => 'hash', 'ativo' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_uma_pessoa_nao_pode_ter_duas_contas(): void
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Lia Exemplo']);
        Usuario::query()->create(['pessoa_id' => $pessoa->id, 'email' => 'a@example.invalid', 'password' => 'uma-senha-exemplo']);
        $this->expectException(QueryException::class);
        Usuario::query()->create(['pessoa_id' => $pessoa->id, 'email' => 'b@example.invalid', 'password' => 'outra-senha-exemplo']);
    }

    public function test_email_unico_sem_diferenciar_maiusculas_no_banco(): void
    {
        $pessoaA = Pessoa::query()->create(['nome_exibicao' => 'Primeira pessoa']);
        $pessoaB = Pessoa::query()->create(['nome_exibicao' => 'Segunda pessoa']);
        Usuario::query()->create(['pessoa_id' => $pessoaA->id, 'email' => 'lia@example.invalid', 'password' => 'senha-de-exemplo']);

        $this->expectException(QueryException::class);
        DB::table('usuarios')->insert([
            'pessoa_id' => $pessoaB->id, 'email' => 'LIA@example.invalid',
            'password' => 'hash-de-exemplo', 'ativo' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_senha_e_armazenada_com_hash(): void
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Lia Exemplo']);
        $usuario = Usuario::query()->create(['pessoa_id' => $pessoa->id, 'email' => 'a@example.invalid', 'password' => 'uma-senha-exemplo']);
        $this->assertNotSame('uma-senha-exemplo', $usuario->getRawOriginal('password'));
    }
}
