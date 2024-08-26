FROM php:7.4-apache

# 필요한 PHP 확장 모듈 설치 (예: mysqli, pdo_mysql)
RUN docker-php-ext-install mysqli pdo_mysql

# Xdebug 설치
RUN apt-get update \
    && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mysqli pdo_mysql zip

# Xdebug 설치
RUN pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug
# PHP 설정 파일에 extension_dir 설정 추가 및 PHP 오류 로그 설정 추가
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-custom.ini \
    && echo "error_log = /var/log/php_errors.log" >> /usr/local/etc/php/conf.d/docker-php-ext-custom.ini

# PHP 오류 로그 파일 생성 및 권한 설정
RUN touch /var/log/php_errors.log \
    && chown www-data:www-data /var/log/php_errors.log \
    && chmod 644 /var/log/php_errors.log
RUN touch /var/log/xdebug.log \
    && chown www-data:www-data /var/log/xdebug.log \
    && chmod 644 /var/log/xdebug.log
# Xdebug 설정 파일 복사
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer 설치
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 작업 디렉토리 설정
WORKDIR /var/www/html

# Composer를 사용하여 PHPSpreadsheet 설치
RUN composer require phpoffice/phpspreadsheet:^1.18

# Apache 설정을 다시 로드하도록 설정
CMD ["apache2-foreground"]
