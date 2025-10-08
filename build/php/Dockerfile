FROM php:fpm

# Login to container as root user
USER root

# Symfony dev environement
ENV APP_ENV=dev

# Set working directory
RUN mkdir -p /app/api
COPY ./api/ /app/api/
WORKDIR /app/api/

RUN ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo "Europe/Paris" > /etc/timezone

# Configure PHP timezone
RUN echo "date.timezone = Europe/Paris" > /usr/local/etc/php/conf.d/timezone.ini

# Arguments defined in compose.yml
ARG USER_NAME
ARG USER_ID
ARG GROUP_NAME
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# RUN composer install --no-dev --optimize-autoloader

# Create system user to run Composer and PHP Commands
RUN if [ ! -z ${USER_NAME} ] && [ ! -z ${GROUP_NAME} ] && [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ] ; then \
    useradd -G www-data,root -u $USER_ID -d /home/$USER_NAME $USER_NAME && \
    mkdir -p /home/$USER_NAME/.composer  && \
    chown -Rf ${USER_NAME}:${GROUP_NAME} /home/$USER_NAME  && \
    chown -R ${USER_NAME}:${GROUP_NAME} /app \
    ; fi

# Login to container as non-root user
USER ${USER_ID:-0}:${GROUP_ID:-0}
