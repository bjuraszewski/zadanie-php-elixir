FROM php:8.4-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y --fix-missing \
    zip

RUN a2enmod rewrite

# Change Apache Listen port to 8080 (non-privileged)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# Configure Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure Apache to run in directories writable by non-root user (if needed for stricter setups)
# But with userns_mode: keep-id, standard paths often work if permissions are mapped correctly.
# However, for maximum robustness against random permission errors:
ENV APACHE_RUN_DIR=/tmp/apache2/run
ENV APACHE_PID_FILE=/tmp/apache2/apache2.pid
ENV APACHE_LOCK_DIR=/tmp/apache2/lock
ENV APACHE_LOG_DIR=/tmp/apache2/log

RUN mkdir -p ${APACHE_RUN_DIR} ${APACHE_LOCK_DIR} ${APACHE_LOG_DIR} && \
    chmod -R 777 /tmp/apache2

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction
