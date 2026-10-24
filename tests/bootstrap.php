<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

require __DIR__.'/../vendor/autoload.php';

// Bootstrapping Laravel em processo separado não interfere nos handlers do PHPUnit.
if (($_SERVER['argv'][1] ?? null) !== '--verificar') {
    (new Process([PHP_BINARY, __FILE__, '--verificar'], dirname(__DIR__)))->mustRun();

    return;
}

try {
    $app = require __DIR__.'/../bootstrap/app.php';
    if ($app->configurationIsCached()) {
        throw new RuntimeException('Proteção: testes não podem carregar configuração cacheada.');
    }
    $app->make(Kernel::class)->bootstrap();
    $driver = getenv('QUARENTENA_TEST_DRIVER') ?: 'sqlite';

    // Executado antes de qualquer RefreshDatabase/migrate:fresh, não depois do dano.
    if (! $app->environment('testing') || config('database.default') !== $driver || ! in_array($driver, ['sqlite', 'pgsql'], true)) {
        throw new RuntimeException('Proteção: ambiente ou driver de testes incorreto.');
    }
    $connection = config('database.connections.'.$driver);
    if (! empty($connection['url'])) {
        throw new RuntimeException('Proteção: DB_URL não pode substituir o destino de testes.');
    }
    if ($driver === 'sqlite') {
        if ($connection['database'] !== ':memory:') {
            throw new RuntimeException('Proteção: SQLite de testes somente em memória.');
        }
    } else {
        $hostPermitido = $connection['host'] === 'postgres_test'
            || (getenv('GITHUB_ACTIONS') === 'true' && $connection['host'] === '127.0.0.1');
        if (! $hostPermitido || (string) $connection['port'] !== '5432'
            || $connection['database'] !== (getenv('DB_TEST_DATABASE') ?: 'quarentena_test')) {
            throw new RuntimeException('Proteção: destino PostgreSQL não é o banco de testes isolado.');
        }
    }
    if (DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) !== $driver) {
        throw new RuntimeException('Proteção: driver PDO diferente do solicitado.');
    }
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage().PHP_EOL);
    exit(1);
}
