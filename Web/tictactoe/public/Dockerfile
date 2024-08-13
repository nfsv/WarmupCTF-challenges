# Use the official PHP 8.3.2 image from Docker Hub
FROM php:8.3.2-apache

# Install SQLite extension for PHP
RUN apt-get update && \
    apt-get install -y libsqlite3-dev && \
    docker-php-ext-install pdo pdo_sqlite

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy the website files to the Apache document root
COPY . /var/www/html/

# Set proper permissions for the website files
RUN chown -R www-data:www-data /var/www/html

# Configure Apache to allow .htaccess overrides
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/sites-available/000-default.conf

# Set a default ServerName to suppress the warning
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Expose port 80 for the web server
EXPOSE 80
