FROM alpine:3.12

# install PHP
RUN apk --no-cache update && apk --no-cache upgrade \
    && apk add git curl alpine-sdk bash vim php7 php7-fpm \
    php7-opcache php7-curl php7-openssl php7-json php7-phar \
    php7-iconv php7-mbstring php7-xml php7-xdebug php7-dom \
    php7-xmlwriter php7-tokenizer php7-pdo php7-pdo_mysql \
    php7-ast php7-igbinary

# install composer globally
# https://www.hostinger.com/tutorials/how-to-install-composer
COPY composer-installer.sh composer-installer.sh
RUN sh composer-installer.sh \
    && mv composer.phar /usr/local/bin/composer

# enable xDebug
RUN sed -i -r 's@^;zend_extension=xdebug.so@zend_extension=xdebug.so@' /etc/php7/conf.d/xdebug.ini
