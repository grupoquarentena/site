<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase {
        migrateFreshUsing as protected defaultMigrateFreshUsing;
    }

    protected function migrateFreshUsing(): array
    {
        $options = $this->defaultMigrateFreshUsing();
        if ((getenv('QUARENTENA_TEST_SCHEMA') ?: 'direct') === 'direct') {
            $path = database_path('schema/.quarentena-sem-snapshot.sql');
            if (file_exists($path)) {
                throw new \RuntimeException('O caminho de exclusão do snapshot deve permanecer inexistente.');
            }
            $options['--schema-path'] = $path;
        }

        return $options;
    }
}
