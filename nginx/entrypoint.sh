#!/bin/sh
# Supprime le fichier default.conf s'il existe
rm -f /etc/nginx/conf.d/default.conf
# Démarre Nginx
exec "$@"
