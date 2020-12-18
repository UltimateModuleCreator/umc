FROM php:7.3.0-apache

# Set working directory
WORKDIR /var/www

# Install System Dependencies
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
	software-properties-common
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y \
	libfreetype6-dev \
	libicu-dev \
    libssl-dev \
	libjpeg62-turbo-dev \
	libmcrypt-dev \
	libedit-dev \
	libzip-dev \
	libedit2 \
	libxslt1-dev \
	libonig-dev \
	apt-utils \
	gnupg \
	vim \
	wget \
	curl \
	unzip \
	tar

# Install extensions
RUN docker-php-ext-configure \
  	gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/; \
  	docker-php-ext-install \
  	zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH="/var/www/.composer/vendor/bin/:${PATH}"

WORKDIR /var/www/html
