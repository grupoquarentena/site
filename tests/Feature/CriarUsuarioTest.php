<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CriarUsuarioTest extends TestCase
{
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
        $this->assertTrue(Hash::check('senha-exemplo-segura', $usuario->getRawOriginal('password')));
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

    public function test_rejeita_senha_curta_sem_criar_registros(): void
    {
        $this->artisan('usuario:criar', ['email' => 'curta@example.invalid', '--nome' => 'Pessoa sintética'])
            ->expectsQuestion('Senha de acesso (mínimo 12 caracteres)', 'curta')->assertFailed();
        $this->assertDatabaseCount('pessoas', 0);
        $this->assertDatabaseCount('usuarios', 0);
    }

    public function test_rejeita_senha_acima_do_limite_bcrypt_em_bytes(): void
    {
        foreach ([str_repeat('a', 73), str_repeat('á', 37)] as $senha) {
            $this->artisan('usuario:criar', ['email' => 'longa@example.invalid', '--nome' => 'Pessoa sintética'])
                ->expectsQuestion('Senha de acesso (mínimo 12 caracteres)', $senha)->assertFailed();
        }
        $this->assertDatabaseCount('pessoas', 0);
        $this->assertDatabaseCount('usuarios', 0);
    }

    public function test_rejeita_pessoa_ja_vinculada(): void
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Pessoa sintética']);
        Usuario::query()->create(['pessoa_id' => $pessoa->id, 'email' => 'existente@example.invalid', 'password' => 'senha-exemplo-segura']);
        $this->artisan('usuario:criar', ['email' => 'outro@example.invalid', '--pessoa' => (string) $pessoa->id])->assertFailed();
        $this->assertDatabaseCount('pessoas', 1);
        $this->assertDatabaseCount('usuarios', 1);
    }

    public function test_rejeita_email_duplicado_sem_criar_pessoa(): void
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Pessoa sintética']);
        Usuario::query()->create(['pessoa_id' => $pessoa->id, 'email' => 'existente@example.invalid', 'password' => 'senha-exemplo-segura']);
        $this->artisan('usuario:criar', ['email' => 'EXISTENTE@example.invalid', '--nome' => 'Outra pessoa'])
            ->expectsQuestion('Senha de acesso (mínimo 12 caracteres)', 'senha-exemplo-segura')->assertFailed();
        $this->assertDatabaseCount('pessoas', 1);
        $this->assertDatabaseCount('usuarios', 1);
    }
}
