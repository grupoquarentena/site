<?php

namespace App\Console\Commands;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CriarUsuario extends Command
{
    protected $signature = 'usuario:criar {email : Email institucional pessoal} {--pessoa= : ID de pessoa existente}';

    protected $description = 'Provisiona um login pessoal vinculado a uma pessoa, sem perfil administrativo automático';

    public function handle(): int
    {
        $email = mb_strtolower(trim((string) $this->argument('email')));
        $pessoaId = $this->option('pessoa');
        $nome = $pessoaId ? null : trim((string) $this->ask('Primeiro nome ou apelido da pessoa'));
        $senha = $this->secret('Senha de acesso (mínimo 12 caracteres)');

        $dados = compact('email', 'senha');
        if (! $pessoaId) {
            $dados['nome'] = $nome;
        }
        $validator = Validator::make($dados, [
            'email' => ['required', 'email', 'max:254', 'unique:usuarios,email'],
            'senha' => ['required', 'string', 'min:12'],
            'nome' => [$pessoaId ? 'nullable' : 'required', 'string', 'min:2', 'max:150'],
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return self::FAILURE;
        }
        if ($pessoaId && (! ctype_digit((string) $pessoaId) || ! Pessoa::query()->whereKey((int) $pessoaId)->where('ativo', true)->exists())) {
            $this->error('Pessoa existente e ativa não encontrada.');
            return self::FAILURE;
        }
        if ($pessoaId && Pessoa::query()->whereKey((int) $pessoaId)->whereHas('usuario')->exists()) {
            $this->error('Essa pessoa já possui conta pessoal.');
            return self::FAILURE;
        }

        DB::transaction(function () use ($email, $senha, $nome, $pessoaId): void {
            $pessoa = $pessoaId ? Pessoa::query()->findOrFail((int) $pessoaId) : Pessoa::query()->create([
                'nome_exibicao' => $nome,
                'ativo' => true,
            ]);
            Usuario::query()->create([
                'pessoa_id' => $pessoa->id,
                'email' => $email,
                'password' => $senha,
                'ativo' => true,
            ]);
        });
        $this->info('Conta pessoal criada; nenhuma permissão de encargo atribuída.');
        return self::SUCCESS;
    }
}
