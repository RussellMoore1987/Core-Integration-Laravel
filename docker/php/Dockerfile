# Using the php 7.4 fpm base image
FROM php:7.4-fpm

# Update the apt-get
RUN apt-get update && apt-get install -y \
    # Node and NPM
    nodejs \
    npm \
    # Needed for php zip extension
    libzip-dev \
    zip \
    # Needed for php intl extension
    libicu-dev \
    g++

# Install pcov for code coverage
RUN pecl install pcov
RUN docker-php-ext-enable pcov

# install php extensions
RUN docker-php-ext-install pdo pdo_mysql pcntl intl zip

# Install composer
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the source code
COPY . /var/www/app/

# Default working directory
WORKDIR /var/www/app/

# Copy dependencies for npm and install
COPY ./package*.json .
RUN npm install --production

# Copy the dependancies for composer and install
COPY ./composer*.json .

# TODO: If we ever use this image in production then use the "--no-dev" option with the composer install
RUN composer install

# Default command when server starts
CMD ["php-fpm"]
