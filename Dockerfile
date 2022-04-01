
FROM phpswoole/swoole:4.8.8-php7.4-alpine

ENV TIMEZONE=${timezone:-"Asia/Shanghai"}

RUN docker-php-source extract \
    && echo "http://mirrors.aliyun.com/alpine/v3.12/community/" > /etc/apk/repositories \
    && echo "http://mirrors.aliyun.com/alpine/v3.12/main/" >> /etc/apk/repositories \
    && apk update \
    && apk add libpng-dev freetype-dev libjpeg-turbo-dev tzdata\
    && docker-php-ext-install bcmath pdo_mysql sysvmsg sysvsem sysvshm gd\
    && curl -L -o /tmp/redis-5.3.7.tgz https://pecl.php.net/get/redis-5.3.7.tgz \
    && tar xfz /tmp/redis-5.3.7.tgz \
    && rm -r /tmp/redis-5.3.7.tgz \
    && mv redis-5.3.7 /usr/src/php/ext/redis \
    && docker-php-ext-install redis \
    && curl -L -o /tmp/mongodb-1.13.0.tgz https://pecl.php.net/get/mongodb-1.13.0.tgz \
    && tar xfz /tmp/mongodb-1.13.0.tgz \
    && rm -r /tmp/mongodb-1.13.0.tgz \
    && mv mongodb-1.13.0 /usr/src/php/ext/mongodb \
    && docker-php-ext-configure mongodb --with-php-config=php-config \
    && docker-php-ext-install mongodb \
    #hyperf
    &&  echo "swoole.use_shortname = 'Off'" >> /usr/local/etc/php/conf.d/docker-php-ext-swoole.ini \
    && php -m \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /www
