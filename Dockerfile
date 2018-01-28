FROM alpine:latest
WORKDIR /app
ENV APP_ENV prod
RUN apk add --no-cache \
        python3 \
        git \
        gcc \
        python3-dev \
        musl-dev \
        openssl-dev \
        php7 \
        php7-bcmath \
        php7-dom \
        php7-ctype \
        php7-curl \
        php7-fileinfo \
        php7-fpm \
        php7-gd \
        php7-iconv \
        php7-intl \
        php7-json \
        php7-mbstring \
        php7-mcrypt \
        php7-mysqlnd \
        php7-opcache \
        php7-openssl \
        php7-pdo \
        php7-pdo_mysql \
        php7-pdo_pgsql \
        php7-pdo_sqlite \
        php7-phar \
        php7-posix \
        php7-simplexml \
        php7-session \
        php7-soap \
        php7-tokenizer \
        php7-xml \
        php7-xmlreader \
        php7-xmlwriter \
        php7-zip \
	    php7-mysqli
RUN pip3 install requests && \
        pip3 install -U git+git://github.com/Netherdrake/steem-python && \
        git clone https://github.com/ety001/steem-mention.git && \
        cd /app/steem-mention && \
        php composer.phar install --no-dev --optimize-autoloader
CMD ['php -S 0.0.0.0:80 -t /app/steem-mention/public']

