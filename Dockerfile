FROM php:8.2-apache

# --------------------------------------------------
# Extensiones PHP
# --------------------------------------------------
RUN apt-get update \
    && apt-get install -y --no-install-recommends libcurl4-openssl-dev \
    && docker-php-ext-install curl \
    && rm -rf /var/lib/apt/lists/*

# --------------------------------------------------
# Apache
# --------------------------------------------------
# rewrite  -> URLs amigables / .htaccess
# remoteip -> permite que Apache reconozca la IP real
#             enviada por el proxy mediante X-Real-IP
# --------------------------------------------------
RUN a2enmod rewrite remoteip

# --------------------------------------------------
# IP real del cliente
# --------------------------------------------------
RUN printf '%s\n' \
    'RemoteIPHeader X-Real-IP' \
    > /etc/apache2/conf-available/remoteip.conf \
    && a2enconf remoteip

WORKDIR /var/www/html

COPY . .

# --------------------------------------------------
# Directorio de sesiones
# --------------------------------------------------
RUN mkdir -p /var/www/html/sessions \
    && chmod 777 /var/www/html/sessions

# --------------------------------------------------
# Configuración de la aplicación
# --------------------------------------------------
RUN printf '%s\n' \
    '<Directory /var/www/html>' \
    '    AllowOverride All' \
    '    Require all granted' \
    '</Directory>' \
    > /etc/apache2/conf-available/app.conf \
    && a2enconf app

# --------------------------------------------------
# Puerto
# --------------------------------------------------
# Railway puede inyectar PORT en tiempo de ejecución.
# Se mantiene 8080 como valor por defecto.
# --------------------------------------------------
ENV PORT=8080

EXPOSE 8080

# --------------------------------------------------
# Arranque de Apache
# --------------------------------------------------
CMD ["bash", "-lc", "set -e; \
    a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true; \
    rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* 2>/dev/null || true; \
    a2enmod mpm_prefork >/dev/null; \
    sed -i \"s/^Listen .*/Listen ${PORT}/\" /etc/apache2/ports.conf; \
    sed -i \"s/<VirtualHost \\*:[0-9]*>/<VirtualHost *:${PORT}>/\" /etc/apache2/sites-available/000-default.conf; \
    apache2ctl -t; \
    exec apache2-foreground"]
