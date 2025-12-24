FROM nginx:alpine
COPY entrypoint.sh /entrypoint.sh
COPY conf.d/app.conf /etc/nginx/conf.d/app.conf
RUN chmod +x /entrypoint.sh && \
    mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html
ENTRYPOINT ["/entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]

