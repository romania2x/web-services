FROM debian:stretch
RUN apt update && apt -y install lsb-release apt-transport-https ca-certificates wget curl git && \
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php7.3.list && \
    apt update
RUN apt -y install \
    php7.3-fpm \
    php7.3-mongodb \
    php7.3-amqp \
    php7.3-dom \
    php7.3-xml \
    php7.3-phar \
    php7.3-xdebug \
    php7.3-zip \
    php7.3-curl \
    php7.3-iconv \
    php7.3-json \
    php7.3-mbstring \
    php7.3-bcmath \
    php7.3-pdo \
    php7.3-pdo-pgsql \
    php7.3-dev \
    php7.3-redis \
    libgeos-dev osm2pgsql unzip locales locales-all

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

RUN git clone https://git.osgeo.org/gitea/geos/php-geos.git /opt/geos
RUN cd /opt/geos && \
    ./autogen.sh && \
    ./configure && \
    make && make install


COPY symfony.ini /etc/php/7.3/fpm/conf.d/
COPY symfony.ini /etc/php/7.3/cli/conf.d/
COPY xdebug.ini  /etc/php/7.3/fpm/conf.d/20-xdebug.ini
COPY xdebug.ini  /etc/php/7.3/cli/conf.d/20-xdebug.ini
COPY geos.ini  /etc/php/7.3/fpm/conf.d/20-geos.ini
COPY geos.ini  /etc/php/7.3/cli/conf.d/20-geos.ini

COPY pool.conf /etc/php/7.3/fpm/pool.d/www.conf

RUN wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
RUN dpkg -i google-chrome-stable_current_amd64.deb; apt-get -fy install

CMD ["php-fpm7.3", "-F"]

WORKDIR /app
EXPOSE 9001
RUN mkdir /var/www && usermod -u 1000 www-data && groupmod -g 1000 www-data && \
    touch /var/log/php7.3-fpm.log && chown www-data:www-data /var/log/php7.3-fpm.log && \
    mkdir /run/php && chown www-data:www-data /run/php -R && chown www-data:www-data /var/www -R
RUN echo 'kernel.unprivileged_userns_clone=1' > /etc/sysctl.d/00-local-userns.conf
USER 1000:1000
