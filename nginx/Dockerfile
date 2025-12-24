FROM nginx:alpine
COPY entrypoint.sh /entrypoint.sh
COPY conf.d/app.conf /etc/nginx/conf.d/app.conf
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
