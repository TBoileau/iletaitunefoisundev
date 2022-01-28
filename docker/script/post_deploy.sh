#!/bin/sh

# start the application

php-fpm -D &&  nginx -g "daemon off;"