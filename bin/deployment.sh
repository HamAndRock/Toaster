#!/bin/sh

# Jump back to project root
cd ..;

# Pull changes from GitHub server
git pull;

# Update composer dependencies
composer update --no-dev;

# Clear application cache
rm -rf temp/cache/*;

# Clear Opcache
service php-fpm reload

# Set Nginx persmissions
chown -R www-data:www-data /var/www