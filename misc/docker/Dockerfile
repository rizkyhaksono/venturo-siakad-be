FROM dunglas/frankenphp:1.1-builder-php8.2.16

# Set Caddy server name to "http://" to serve on 80 and not 443
# Read more: https://frankenphp.dev/docs/config/#environment-variables
ENV SERVER_NAME="http://"

RUN apt-get update \
  && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
  git \
  unzip \
  librabbitmq-dev \
  libpq-dev \
  supervisor

RUN install-php-extensions \
  gd \
  pcntl \
  opcache \
  pdo \
  pdo_mysql \
  redis \
  amqp \
  sockets

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the Laravel application files into the container.
COPY . .

# Start with base PHP config, then add extensions.
COPY ./misc/php.ini /usr/local/etc/php/
COPY ./misc/etc/supervisor.d/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Install Laravel dependencies using Composer.
# RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts
RUN composer install

# Set permissions for Laravel.
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80 443

# Start Supervisor.
CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/conf.d/supervisord.conf"]
