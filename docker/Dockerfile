# =============================================================================
# Imagem PHP + Apache -- ambiente de TESTE LOCAL do MVP historico (2026)
# =============================================================================
# Projeto arquivado: o MVP do site do Grupo QuarenteNA nunca foi publicado em
# producao e nao deve ser publicado. Este container existe apenas para executar,
# inspecionar e testar o codigo da branch legado/site-2026, em 127.0.0.1.
#
# O layout espelha o repositorio (exigido por tests/run.php, que resolve
# caminhos com dirname(__DIR__)):
#   /var/www/site/public  -> DocumentRoot (somente arquivos publicos)
#   /var/www/site/app     -> codigo interno, fora do DocumentRoot
#   /var/www/site/tests   -> suite de verificacoes do acervo
#   /var/www/site/tmp     -> contadores agregados do analytics (gravavel)
# =============================================================================

FROM php:8.2-apache

# curl: usado apenas pelo HEALTHCHECK abaixo.
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates \
    curl \
    && rm -rf /var/lib/apt/lists/*

# mod_headers: cabecalhos de seguranca definidos no VirtualHost local.
RUN a2enmod headers

# ServerName local (nao ha dominio publico): evita o aviso AH00558 do Apache.
RUN printf "ServerName localhost\n" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/apache/php-custom.ini $PHP_INI_DIR/conf.d/99-custom.ini
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/site

COPY public/ /var/www/site/public/
COPY app/ /var/www/site/app/
COPY tests/ /var/www/site/tests/

# Unico diretorio gravavel em runtime (tmp/analytics.json, descartavel).
RUN mkdir -p /var/www/site/tmp \
    && chown -R www-data:www-data /var/www/site/tmp

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
    CMD curl -fsS http://localhost/ >/dev/null || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 80

CMD ["apache2-foreground"]
