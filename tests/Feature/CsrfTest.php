<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\Usuario;
use Tests\TestCase;

class CsrfTest extends TestCase
{
    public function test_login_e_logout_exigem_token_csrf_valido(): void
    {
        // Laravel dispensa CSRF em env=testing. Reativar a proteção HTTP real.
        $this->app['env'] = 'local';
        $pessoa = Pessoa::query()->create(['nome_exibicao' => 'Pessoa sintética']);
        Usuario::query()->create(['pessoa_id' => $pessoa->id, 'email' => 'csrf@example.invalid', 'password' => 'senha-exemplo-segura']);
        $dados = ['email' => 'csrf@example.invalid', 'password' => 'senha-exemplo-segura'];

        $this->post('/entrar', $dados)->assertStatus(419);
        $token = str_repeat('t', 40);
        $this->withSession(['_token' => $token])->post('/entrar', [...$dados, '_token' => 'invalido'])->assertStatus(419);
        $this->withSession(['_token' => $token])->post('/entrar', [...$dados, '_token' => $token])->assertRedirect('/admin');
        $this->assertAuthenticated();
        $this->post('/sair')->assertStatus(419);
        $token = session()->token();
        $this->post('/sair', ['_token' => $token])->assertRedirect('/');
        $this->assertGuest();
    }
}
