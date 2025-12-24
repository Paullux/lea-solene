#!/bin/sh
# Supprime le fichier default.conf s'il existe
rm -f /etc/nginx/conf.d/default.conf
# Corrige les permissions du volume monté
chown -R www-data:www-data /var/www/html
# Démarre Nginx
exec "$@"
