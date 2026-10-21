<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class AmbienteTest extends TestCase
{
    public function test_driver_real_corresponde_ao_gate_solicitado(): void
    {
        $esperado = getenv('QUARENTENA_TEST_DRIVER') ?: 'sqlite';
        $this->assertContains($esperado, ['sqlite', 'pgsql']);
        $this->assertSame($esperado, DB::connection()->getDriverName());
        if ($esperado === 'sqlite') {
            $this->assertSame(':memory:', DB::connection()->getDatabaseName());
        } else {
            $this->assertSame(getenv('DB_TEST_DATABASE') ?: 'quarentena_test', DB::connection()->getDatabaseName());
            $this->assertStringStartsWith('17.', DB::selectOne('SHOW server_version')->server_version);
        }
    }

    public function test_preflight_recusa_cache_antes_de_carregar_conexao(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'quarentena-config-');
        file_put_contents($path, '<?php return [];');
        try {
            $process = new Process([PHP_BINARY, base_path('tests/bootstrap.php'), '--verificar'], base_path(), ['APP_CONFIG_CACHE' => $path]);
            $process->run();
            $this->assertFalse($process->isSuccessful());
            $this->assertStringContainsString('testes não podem carregar configuração cacheada', $process->getErrorOutput());
        } finally {
            unlink($path);
        }
    }

    public function test_preflight_recusa_host_persistente_antes_de_conectar(): void
    {
        $process = new Process([PHP_BINARY, base_path('tests/bootstrap.php'), '--verificar'], base_path(), [
            'APP_ENV' => 'testing', 'APP_CONFIG_CACHE' => '/tmp/quarentena-tests-config.php',
            'QUARENTENA_TEST_DRIVER' => 'pgsql', 'DB_CONNECTION' => 'pgsql', 'DB_HOST' => 'postgres',
            'DB_DATABASE' => 'quarentena_dev', 'DB_URL' => '',
        ]);
        $process->run();
        $this->assertFalse($process->isSuccessful());
        $this->assertStringContainsString('destino PostgreSQL não é o banco de testes isolado', $process->getErrorOutput());
    }
}
