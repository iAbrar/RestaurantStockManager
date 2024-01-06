FROM php:8-fpm-alpine

# Define arguments for PHP_USER and PHP_GROUP
ARG PHP_USER
ARG PHP_GROUP

# Set environment variables
ENV PHP_USER=${USER:-defaultuser}
ENV PHP_GROUP=${GROUP:-defaultgroup}

# Create the group if it does not exist
RUN addgroup -S ${PHP_GROUP} || true

# Add the user
RUN adduser -g "${PHP_GROUP}" -s /bin/sh -D ${PHP_USER}

# Update www.conf
RUN sed -i "s/user = www-data/user = ${PHP_USER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHP_GROUP}/g" /usr/local/etc/php-fpm.d/www.conf

# Create necessary directory
RUN mkdir -p /var/www/html/public

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set the command
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
