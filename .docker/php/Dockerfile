FROM php:7.3.6-fpm-alpine

RUN apk update \
  && apk add --no-cache \
    libmcrypt-dev \
    zlib-dev \
    libzip-dev \
    freetype \
    freetype-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    bash git openssh \
  && docker-php-ext-configure bcmath --enable-bcmath \
  && docker-php-ext-configure zip \
  && docker-php-ext-install zip mysqli pdo pdo_mysql bcmath opcache xml \
  && docker-php-ext-install xml \
  && docker-php-ext-configure gd \
       --with-freetype=/usr/include/ \
       --with-jpeg=/usr/include/ && \
     NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
     docker-php-ext-install -j${NPROC} gd && \
     apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

# Install composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

COPY --chown=www-data:www-data . /app

EXPOSE 9000
CMD ["php-fpm"]
