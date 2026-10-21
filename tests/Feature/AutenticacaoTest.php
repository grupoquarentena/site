<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AutenticacaoTest extends TestCase
{
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

    public function test_email_com_caixa_alta_e_aceito_apos_normalizacao(): void
    {
        $this->usuario();
        $this->post('/entrar', ['email' => '  LIA@EXAMPLE.INVALID  ', 'password' => 'uma-senha-segura'])
            ->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_usuario_inativo_nao_entra(): void
    {
        $this->usuario(false);
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'uma-senha-segura'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_nao_aceita_sufixo_apos_limite_bcrypt(): void
    {
        $usuario = $this->usuario();
        $senha = str_repeat('á', 36);
        $usuario->update(['password' => $senha]);
        $this->post('/entrar', ['email' => $usuario->email, 'password' => $senha.'x'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_aceita_email_gravado_em_caixa_alta_por_sql(): void
    {
        $usuario = $this->usuario();
        DB::table('usuarios')->where('id', $usuario->id)->update(['email' => 'LIA@EXAMPLE.INVALID']);
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'uma-senha-segura'])
            ->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_usuario_inativado_perde_acesso_com_sessao_existente(): void
    {
        $usuario = $this->usuario();
        $this->actingAs($usuario)->get('/admin')->assertOk();
        $usuario->update(['ativo' => false]);
        $this->get('/admin')->assertRedirect('/entrar');
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
        $usuario = $this->usuario();
        $erro = ['email' => 'Credenciais inválidas ou conta indisponível.'];
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'incorreta'])->assertSessionHasErrors($erro);
        $this->post('/entrar', ['email' => 'inexistente@example.invalid', 'password' => 'incorreta'])->assertSessionHasErrors($erro);
        $usuario->update(['ativo' => false]);
        $this->post('/entrar', ['email' => 'lia@example.invalid', 'password' => 'uma-senha-segura'])->assertSessionHasErrors($erro);
        $this->assertGuest();
    }
}
