FROM php:8.0-cli

# Add required packages: libzip-dev, zip, unzip, and git
RUN apt-get update && \
    apt-get install -y nano zip unzip git libzip-dev && \
    docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Set the working directory
WORKDIR /app

# Copy project files
COPY . .

# Install dependencies
RUN composer install

# Keep the shell open
CMD ["tail", "-f", "/dev/null"]
