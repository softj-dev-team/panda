# php:7.3-apache 이미지를 기반으로
FROM php:7.3-apache

# 필요한 PHP 확장 모듈 설치 (예: mysqli, pdo_mysql)
RUN docker-php-ext-install mysqli pdo_mysql

# PHP 설정 파일에 extension_dir 설정 추가
RUN echo "extension_dir = \"/usr/local/lib/php/extensions/no-debug-non-zts-20180731\"" >> /usr/local/etc/php/conf.d/docker-php-ext-custom.ini


# PHP 설정 파일에 extension_dir 설정 추가 및 PHP 오류 로그 설정 추가
RUN echo "extension_dir = \"/usr/local/lib/php/extensions/no-debug-non-zts-20180731\"" >> /usr/local/etc/php/conf.d/docker-php-ext-custom.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-custom.ini \
    && echo "error_log = /var/log/php_errors.log" >> /usr/local/etc/php/conf.d/docker-php-ext-custom.ini

# PHP 오류 로그 파일 생성 및 권한 설정
RUN touch /var/log/php_errors.log \
    && chown www-data:www-data /var/log/php_errors.log \
    && chmod 644 /var/log/php_errors.log


# Apache 설정을 다시 로드하도록 설정
CMD ["apache2-foreground"]