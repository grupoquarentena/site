<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutenticacaoTest extends TestCase
{
    use RefreshDatabase;

    private function usuario(bool $usuarioAtivo = true, bool $pessoaAtiva = true): Usuario
    {
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Lia Exemplo', 'ativo' => $pessoaAtiva]);
        return Usuario::query()->create([
            'pessoa_id' => $pessoa->id,
            'email' => 'lia@example.invalid',
            'password' => 'uma-senha-segura',
            'ativo' => $usuarioAtivo,
        ]);
    }

    public function test_portal_publico_nao_exige_autenticacao(): void
    {
        $this->get('/')->assertOk()->assertSee('Grupo QuarenteNA');
        $this->get('/up')->assertOk();
    }

    public function test_admin_redireciona_visitante(): void
    {
        $this->get('/admin')->assertRedirect('/entrar');
    }

    public function test_login_pessoal_valido_e_logout(): void
    {
        $this->usuario();
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'uma-senha-segura'])->assertRedirect('/admin');
        $this->assertAuthenticated();
        $this->get('/admin')->assertOk()->assertSee('Lia Exemplo');
        $this->post('/sair')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_pessoa_inativa_nao_faz_login(): void
    {
        $this->usuario(true, false);
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'uma-senha-segura'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_usuario_inativo_nao_entra(): void
    {
        $this->usuario(false);
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'uma-senha-segura'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_pessoa_inativa_perde_acesso_mesmo_com_sessao(): void
    {
        $usuario = $this->usuario(true, false);
        $this->actingAs($usuario)->get('/admin')->assertRedirect('/entrar');
        $this->assertGuest();
    }

    public function test_nao_existe_cadastro_publico(): void
    {
        $this->get('/registrar')->assertNotFound();
        $this->post('/registrar')->assertNotFound();
    }

    public function test_login_invalido_nao_revela_existencia_da_conta(): void
    {
        $this->usuario();
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'incorreta'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
