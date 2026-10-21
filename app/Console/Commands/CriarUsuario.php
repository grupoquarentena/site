<?php

namespace App\Console\Commands;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CriarUsuario extends Command
{
    protected $signature = 'usuario:criar {email : E-mail pessoal} {--pessoa= : ID de pessoa existente} {--nome= : Nome exibido para pessoa nova}';

    protected $description = 'Cria conta humana vinculada a pessoa, sem permissão de encargo automática';

    public function handle(): int
    {
        $email = mb_strtolower(trim((string) $this->argument('email')));
        $opcaoPessoa = $this->option('pessoa');
        $temPessoa = $opcaoPessoa !== null && $opcaoPessoa !== '';

        // "0" e valores inválidos não podem ser tratados como ausência da opção.
        if ($temPessoa && (! ctype_digit((string) $opcaoPessoa) || (int) $opcaoPessoa < 1)) {
            $this->error('Informe um ID positivo de pessoa existente.');

            return self::FAILURE;
        }
        $pessoaId = $temPessoa ? (int) $opcaoPessoa : null;

        if ($temPessoa && ! Pessoa::query()->whereKey($pessoaId)->where('ativo', true)->exists()) {
            $this->error('Pessoa existente e ativa não encontrada.');

            return self::FAILURE;
        }
        if ($temPessoa && Pessoa::query()->whereKey($pessoaId)->whereHas('usuario')->exists()) {
            $this->error('Essa pessoa já possui conta pessoal.');

            return self::FAILURE;
        }

        $nome = $temPessoa ? null : trim((string) ($this->option('nome') ?? $this->ask('Primeiro nome ou apelido da pessoa')));
        // A senha só é fornecida em prompt oculto, nunca por argumento de linha de comando.
        $senha = (string) $this->secret('Senha de acesso (mínimo 12 caracteres)');
        if (strlen($senha) > 72) {
            $this->error('Senha deve ter no máximo 72 bytes UTF-8 (limite bcrypt).');

            return self::FAILURE;
        }
        $dados = ['email' => $email, 'senha' => $senha];
        if (! $temPessoa) {
            $dados['nome'] = $nome;
        }

        $validator = Validator::make($dados, [
            'email' => ['required', 'email', 'max:254', 'unique:usuarios,email'],
            'senha' => ['required', 'string', 'min:12'],
            'nome' => [$temPessoa ? 'nullable' : 'required', 'string', 'min:2', 'max:150'],
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }

        DB::transaction(function () use ($email, $senha, $nome, $pessoaId): void {
            $pessoa = $pessoaId !== null
                ? Pessoa::query()->findOrFail($pessoaId)
                : Pessoa::query()->create(['nome_exibicao' => $nome, 'ativo' => true]);

            Usuario::query()->create([
                'pessoa_id' => $pessoa->id,
                'email' => $email,
                'password' => $senha,
                'ativo' => true,
            ]);
        });
        $this->info('Conta pessoal criada, sem permissão institucional automática.');

        return self::SUCCESS;
    }
}
