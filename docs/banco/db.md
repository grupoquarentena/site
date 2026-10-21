# Banco de dados — Grupo QuarenteNA

Catálogo gerado do PostgreSQL local pelo script `scripts/banco-atualizar.sh`. Não editar manualmente.

| Tabela | Coluna | Tipo | Nulo |
|---|---|---|---|
| cache | key | character varying | NO |
| cache | value | text | NO |
| cache | expiration | integer | NO |
| cache_locks | key | character varying | NO |
| cache_locks | owner | character varying | NO |
| cache_locks | expiration | integer | NO |
| failed_jobs | id | bigint | NO |
| failed_jobs | uuid | character varying | NO |
| failed_jobs | connection | text | NO |
| failed_jobs | queue | text | NO |
| failed_jobs | payload | text | NO |
| failed_jobs | exception | text | NO |
| failed_jobs | failed_at | timestamp with time zone | NO |
| job_batches | id | character varying | NO |
| job_batches | name | character varying | NO |
| job_batches | total_jobs | integer | NO |
| job_batches | pending_jobs | integer | NO |
| job_batches | failed_jobs | integer | NO |
| job_batches | failed_job_ids | text | NO |
| job_batches | options | text | YES |
| job_batches | cancelled_at | integer | YES |
| job_batches | created_at | integer | NO |
| job_batches | finished_at | integer | YES |
| jobs | id | bigint | NO |
| jobs | queue | character varying | NO |
| jobs | payload | text | NO |
| jobs | attempts | smallint | NO |
| jobs | reserved_at | integer | YES |
| jobs | available_at | integer | NO |
| jobs | created_at | integer | NO |
| migrations | id | integer | NO |
| migrations | migration | character varying | NO |
| migrations | batch | integer | NO |
| password_reset_tokens | email | character varying | NO |
| password_reset_tokens | token | character varying | NO |
| password_reset_tokens | created_at | timestamp with time zone | YES |
| pessoas | id | bigint | NO |
| pessoas | nome_exibicao | text | NO |
| pessoas | nome_busca | character varying | YES |
| pessoas | ativo | boolean | NO |
| pessoas | notas_restritas | text | YES |
| pessoas | created_at | timestamp with time zone | YES |
| pessoas | updated_at | timestamp with time zone | YES |
| sessions | id | character varying | NO |
| sessions | user_id | bigint | YES |
| sessions | ip_address | character varying | YES |
| sessions | user_agent | text | YES |
| sessions | payload | text | NO |
| sessions | last_activity | integer | NO |
| usuarios | id | bigint | NO |
| usuarios | pessoa_id | bigint | NO |
| usuarios | email | character varying | NO |
| usuarios | password | character varying | NO |
| usuarios | ativo | boolean | NO |
| usuarios | remember_token | character varying | YES |
| usuarios | created_at | timestamp with time zone | YES |
| usuarios | updated_at | timestamp with time zone | YES |
