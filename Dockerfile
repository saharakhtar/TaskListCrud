FROM serversideup/php:8.2

WORKDIR /var/www

COPY . /var/www

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
