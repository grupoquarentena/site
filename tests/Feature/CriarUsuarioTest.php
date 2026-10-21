<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriarUsuarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejeita_id_zero_sem_criar_pessoa(): void
    {
        $this->artisan('usuario:criar', [
            'email' => 'lia@example.invalid', '--pessoa' => '0',
        ])->assertFailed();

        $this->assertDatabaseCount('pessoas', 0);
        $this->assertDatabaseCount('usuarios', 0);
    }

    public function test_rejeita_id_inexistente_sem_criar_pessoa(): void
    {
        $this->artisan('usuario:criar', [
            'email' => 'lia@example.invalid', '--pessoa' => '999999',
        ])->assertFailed();

        $this->assertDatabaseCount('pessoas', 0);
    }

    public function test_provisiona_conta_sem_permissoes_com_email_normalizado(): void
    {
        $this->artisan('usuario:criar', [
            'email' => '  Lia@Example.invalid  ', '--nome' => 'Lia',
        ])->expectsQuestion('Senha de acesso (mínimo 12 caracteres)', 'senha-exemplo-segura')->assertSuccessful();

        $usuario = Usuario::query()->firstOrFail();
        $this->assertSame('lia@example.invalid', $usuario->email);
        $this->assertSame('Lia', $usuario->pessoa->nome_exibicao);
        $this->assertNotSame('senha-exemplo-segura', $usuario->getRawOriginal('password'));
    }

    public function test_vincula_pessoa_existente_sem_criar_outra(): void
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Pessoa existente']);
        $this->artisan('usuario:criar', [
            'email' => 'pessoa@example.invalid', '--pessoa' => (string) $pessoa->id,
        ])->expectsQuestion('Senha de acesso (mínimo 12 caracteres)', 'senha-exemplo-segura')->assertSuccessful();

        $this->assertDatabaseCount('pessoas', 1);
        $this->assertDatabaseHas('usuarios', ['pessoa_id' => $pessoa->id]);
    }

    public function test_rejeita_pessoa_inativa(): void
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Pessoa inativa', 'ativo' => false]);
        $this->artisan('usuario:criar', [
            'email' => 'pessoa@example.invalid', '--pessoa' => (string) $pessoa->id,
        ])->assertFailed();

        $this->assertDatabaseCount('usuarios', 0);
    }
}
