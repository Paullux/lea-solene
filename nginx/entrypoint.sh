#!/bin/sh
# Supprime le fichier default.conf s'il existe
rm -f /etc/nginx/conf.d/default.conf
# Corrige les permissions du volume monté
chown -R nginx:nginx /var/www/html
chmod 644 /var/www/html/index.php
# Démarre Nginx
exec "$@"
